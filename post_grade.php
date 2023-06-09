<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (isset($_GET['enrollment_id'])) {
  $enrollment_id = $_GET['enrollment_id'];
  $query = "SELECT s.firstname, s.lastname ,c.name,c.crn
            FROM Enrollment e 
            INNER JOIN Student s ON e.student_id = s.student_id 
            INNER JOIN Course c ON e.course_id=c.crn
            WHERE e.id = '$enrollment_id'";
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $fname = $row['firstname'];
  $lname = $row['lastname'];
  $course_name=$row['name'];
  $course_id=$row['crn'];
  mysqli_free_result($result);
  
  echo "<html>";
  echo "<head>";
  echo "<title>Post Grades</title>";
  echo " <link rel='stylesheet' href='styling.css'> ";
  echo "</head>";
  echo "<body>";
  echo "<h2>Grades for $course_name</h2>";
  echo "<h3>Post Grades for $fname $lname:</h3>";
  echo "<form action='save_grade.php' method='post'>";
  echo "<input type='hidden' name='enrollment_id' value='$enrollment_id'>";
  echo "<label for='assignment_grade'>Assignment Grade:</label>";
  echo "<input type='number' id='assignment_grade' name='assignment_grade' min='0' max='100' required><br>";
  echo "<label for='exam_grade'>Exam Grade:</label>";
  echo "<input type='number' id='exam_grade' name='exam_grade' min='0' max='100' required><br>";
  echo "<label for='participation_grade'>Participation Grade:</label>";
  echo "<input type='number' id='participation_grade' name='participation_grade' min='0' max='100' required><br>";
  echo "<input type='hidden' name='crn' value='$course_id'>";
  echo "<input type='submit' value='Save'>";
  echo "</form>";
  echo "<hr>";
  echo "<p><a href='post_grade.txt'>Contents of this page</a></p>";
  echo "</body>";
  echo "</html>";
}

mysqli_close($conn);
?>
