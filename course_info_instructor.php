<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');



if (isset($_POST['crn'])){
  $course_id = $_POST['crn'];
  
}
if (isset($_GET['crn'])){
  $course_id = $_GET['crn'];
}

if (isset($_POST['crn']) or (isset($_GET['crn'])) ){

  $query = "SELECT e.id,e.student_id, s.firstname, s.lastname, s.email,
            g.assignment_grade, g.exam_grade, g.participation_grade
            FROM Enrollment e 
            INNER JOIN Student s ON e.student_id = s.student_id 
            LEFT JOIN grade g ON e.id = g.enrollment_id
            WHERE e.course_id = '$course_id' ORDER BY 2";
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
  
  $query2="SELECT name,instructor FROM Course WHERE crn='$course_id'";
  $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
  $row1 = mysqli_fetch_array($result2, MYSQLI_BOTH);
  $course_name=$row1['name'];
  $instructor_id=$row1['instructor'];
  
  echo "<html>";
  echo "<head>";
  echo "<title>Course Information</title>";
  echo "
          <style>
          .logout{
            justify: center;
            color:red;
          }
          </style>
      ";

  echo "</head>";
  echo "<body>";
  echo "<h2>Course page for CRN $course_id - $course_name</h2>";
  
  echo "<h3>Enrolled Students:</h3>";
  echo "<table>";
  echo "<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Assignment Grade</th><th>Exam Grade</th>
        <th>Participation Grade</th><th>Overall Grade</th><th>Post Grade</th></tr>";
  
  while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $student_id=$row['student_id'];
    $enrollment_id=$row['id'];
    $fname = $row['firstname'];
    $lname = $row['lastname'];
    $email = $row['email'];
    $assignment_grade = ($row['assignment_grade']) ? $row['assignment_grade'] : 0;
    $exam_grade = ($row['exam_grade']) ? $row['exam_grade'] : 0;
    $participation_grade = ($row['participation_grade']) ? $row['participation_grade'] : 0;
    $overall_grade = $assignment_grade + $exam_grade + $participation_grade;
    
    echo "<tr><td>$student_id</td><td>$fname</td><td>$lname</td><td>$email</td><td>$assignment_grade</td>
        <td>$exam_grade</td><td>$participation_grade</td><td>$overall_grade</td><td><a href='post_grade.php?enrollment_id=$enrollment_id'>Post Grades</a></td></tr>";
  }echo "</table>";
echo "<br>";

echo"<h3>Click to view current course rating</h3>";
// echo "<button onclick=\"document.location.href='course_average_rating.php?crn=$course_id'\">Course Rating</button>";
echo"<form method='get' action='course_average_rating.php'>
  <input type='hidden' name='crn' value= $course_id>
  <input type='submit' name='view_rating' value='View Rating'>
</form>";

if (isset($_SESSION['average_rating'])) {
  echo "<p><em> Rating:" . $_SESSION['average_rating'] . "</em></p>";
  unset($_SESSION['average_rating']);
}



// echo"<br>";
// echo"<br>";
// Schedule Office Hours button
echo"<h3>Click to Schedule Office Hours</h3>";

echo "<form action='schedule_office_hours.php' method='post'>";
echo "<input type='hidden' name='crn' value='$course_id'>";
echo "<input type='hidden' name='instructor' value='$instructor_id'>";
echo "<input type='submit' value='Office Hours'>";
echo "</form>";
echo"<h3>Grade weightage</h3>";

echo"<form method='post' action='view_grade_weightage.php'>
  <input type='hidden' name='crn' value= $course_id>
  <input type='hidden' name='cname' value= '$course_name'>
  <input type='submit'  value='View weightage'>
</form>

<form action='grade_weightage.php' method='post'>
<input type='hidden' name='crn' value= $course_id>
<input type='submit' value='Assign Grade weightage'>

</form>
<br>
<button class=\"logout\" onclick=\"document.location.href='homepage.html'\" >Logout</button>
<hr>
<p><a href='course_info_instructor.txt'>Contents of this page</a></p> ";




mysqli_free_result($result);
  
mysqli_close($conn);
}
?>
<html>
  <head>
  <link rel="stylesheet" href="styling.css">

  </head>
</html>