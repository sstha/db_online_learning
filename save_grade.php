<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');


$enrollment_id = $_POST['enrollment_id'];

$assignment_grade = $_POST['assignment_grade'];
$exam_grade = $_POST['exam_grade'];
$participation_grade = $_POST['participation_grade'];
// check if grade exists for enrollment id
$select_query = "SELECT * FROM grade WHERE enrollment_id='$enrollment_id'";
$result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));

if(mysqli_num_rows($result) > 0) {
  // update existing grade


  $update_query = "UPDATE grade g
        INNER JOIN Enrollment e ON g.enrollment_id = e.id
        INNER JOIN grade_weightage w ON e.course_id = w.course_id
        SET g.assignment_grade = $assignment_grade * w.assignment_weightage / 100,
            g.exam_grade = $exam_grade * w.exam_weightage / 100,
            g.participation_grade = $participation_grade * w.participation_weightage / 100
        WHERE e.id = $enrollment_id";

  mysqli_query($conn, $update_query) or die(mysqli_error($conn));

} else {
  // insert new grade
  $insert_query = "INSERT INTO grade (enrollment_id, assignment_grade, exam_grade, participation_grade)
        SELECT e.id, $assignment_grade * w.assignment_weightage / 100, $exam_grade * w.exam_weightage / 100, $participation_grade * w.participation_weightage / 100
        FROM Enrollment e
        INNER JOIN grade_weightage w ON e.course_id = w.course_id
        WHERE e.id = '$enrollment_id'";

  mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
}


$course_id = $_POST['crn'];

$select_query = "SELECT e.id,e.student_id, s.firstname, s.lastname, g.assignment_grade, g.exam_grade, g.participation_grade
                 FROM Enrollment e 
                 INNER JOIN Student s ON e.student_id = s.student_id 
                 INNER JOIN grade_weightage w ON e.course_id = w.course_id
                 LEFT JOIN grade g ON e.id = g.enrollment_id
                 WHERE e.course_id ='$course_id' 
                 ORDER BY s.student_id";

$result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));



echo "<html>";
echo "<head>";
echo "<title>Grades</title>";
echo "<link rel='stylesheet' href='styling.css'>";
echo "</head>";
echo "<body>";
echo "<h3>Grades:</h3>";
echo "<table>";
echo "<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Assignment Grade</th><th>Exam Grade</th><th>Participation Grade</th></tr>";

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $enrollment_id = $row['id'];
  $student_id=$row['student_id'];
  $fname = $row['firstname'];
  $lname = $row['lastname'];
  $assignment_grade = $row['assignment_grade'];
  $exam_grade = $row['exam_grade'];
  $participation_grade=$row['participation_grade'];

  

echo "<tr><td>$student_id</td><td>$fname</td><td>$lname</td><td>$assignment_grade</td><td>$exam_grade</td><td>$participation_grade</td></tr>";
 
}
echo "</table>";

echo "<br>";

echo "<form action='course_info_instructor.php' method='post'>";
echo "<input type='hidden' name='crn' value='$course_id'>";
echo "<input type='submit' value='Back to Course Page'>";
echo "</form>";

echo "<hr>";
echo "<p><a href='save_grade.txt>Contents of this page</a></p>";
echo "</body>";
echo "</html>";
mysqli_close($conn);
?>
