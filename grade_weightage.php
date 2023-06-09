<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (isset($_POST['crn'])){
    $course_id = $_POST['crn'];
}

if(isset($_POST['submit'])) {
  $assignment_weightage = $_POST['assignment_weightage'];
  $exam_weightage = $_POST['exam_weightage'];
  $participation_weightage = $_POST['participation_weightage'];
  $course_id = $_POST['crn'];

  $query = "SELECT * FROM grade_weightage WHERE course_id='$course_id'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
    $query = "UPDATE grade_weightage SET assignment_weightage='$assignment_weightage', exam_weightage='$exam_weightage', participation_weightage='$participation_weightage' WHERE course_id='$course_id'";
    $result = mysqli_query($conn, $query);
    if($result) {
      echo "<p>Grade weightage updated successfully!</p>";
    } else {
      echo "Error: " . mysqli_error($conn);
    }
  } 

  else {
    $query = "INSERT INTO grade_weightage (course_id, assignment_weightage,exam_weightage,participation_weightage) 
        VALUES 
        ('$course_id','$assignment_weightage', '$exam_weightage', '$participation_weightage')";
    $result = mysqli_query($conn, $query);
    if($result) {
      echo "<p>Grade weightage added successfully!</p>";
    } else {
      echo "Error: " . mysqli_error($conn);
    }
  }
}

?>

<html>
<head>
  <title>Add New Course</title>
  <link rel="stylesheet" href="styling.css">

</head>


<h2>Provide weightage</h2>
  <h3>Provide weightage in %</h3>


  <form action="" method="POST">

    <label>Assignment Weightage:</label>
    <input type="text" name="assignment_weightage" required>

<br><br>
    <label>Exam Weightage:</label>
    <input type="text" name="exam_weightage" required>

<br><br>
<label>Participation Weightage:</label>
    <input type="text" name="participation_weightage" required>

    <br><br>
    <input type="hidden" name="crn" value="<?php echo $course_id; ?>">
    <input type="submit" name="submit" value="Add/Update weightage">

  </form>
  <form action="course_info_instructor.php" method="post">
<input type="hidden" name="crn" value="<?php echo $course_id; ?>">
<input type="submit" value="Back to Course Page">
</form>
  <hr>
  <p><a href="grade_weightage.txt">Contents of this page</a></p>

</body>
</html>
