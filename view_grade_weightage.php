<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

if (isset($_POST['crn'])){
  $course_id = $_POST['crn'];
  $course_name = $_POST['cname'];
 
}

$query = "SELECT * FROM grade_weightage WHERE course_id='$course_id'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

echo "<html>";
echo "<head>";
echo "<title>Grade Weightage</title>";
echo "<link rel='stylesheet' href='styling.css'>";
echo "</head>";
echo "<body>";

if(mysqli_num_rows($result) >0){
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    $assignment_weight = $row['assignment_weightage'];
    $exam_weight = $row['exam_weightage'];
    $participation_weight = $row['participation_weightage'];


    echo "<h2>Grade Weightage for CRN $course_id:$course_name</h2>";
    echo "<table>";
    echo "<tr><th>Assignment Weight</th><th>Exam Weight</th><th>Participation Weight</th></tr>";
    echo "<tr><td>$assignment_weight%</td><td>$exam_weight%</td><td>$participation_weight%</td></tr>";
    echo "</table>";

} else {
  echo "<h3>No grade weightage found for this course.</h3>";
}
    echo "<br>";
    echo "<button class='button' onclick=\"document.location.href='course_info_instructor.php?crn=$course_id'\">Back to Course Information</button>";
    echo "<hr>";
    echo "<p><a href='view_grade_weightage.txt'>Contents of this page</a></p>";
    echo "</body>";
    echo "</html>";
mysqli_free_result($result);
mysqli_close($conn);
?>
