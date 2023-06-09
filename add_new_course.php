<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if(isset($_POST['submit'])) {
  $instructor_id = $_POST['instructor'];
  $course_name = $_POST['course_name'];
  $course_crn = $_POST['course_crn'];
  $course_description = $_POST['course_description'];

  $query = "INSERT INTO Course (instructor, name, crn, description) VALUES ('$instructor_id', '$course_name', '$course_crn', '$course_description')";
  $result = mysqli_query($conn, $query);
  if($result) {
    echo "<p>Course added successfully!</p>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

?>

<html>
<head>
  <title>Add New Course</title>
  <link rel="stylesheet" href="styling.css">

</head>


<h2>Add new course</h2>
  <h3>Provide all details</h3>


  <form action="" method="POST">

    <label>Instructor:</label>
    <select name="instructor" required>
      <option value="">Select Instructor</option>
      <?php
        $query = "SELECT CONCAT(fname,' ',lname) AS fullname, ssn FROM Instructor ORDER BY 1";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          echo '<option value="' . $row['ssn'] . '">' . $row['fullname'] . '</option>';
        }
        mysqli_free_result($result);
      ?>
    </select>

<br>
    <label>Course Name:</label>
    <input type="text" name="course_name" required>

<br><br>
    <label>Course CRN:</label>
    <input type="text" name="course_crn" required>

<br><br>
    <label>Course Description:</label>
    <textarea name="course_description" rows="5" cols="40" required></textarea>

    <br><br>

    <input type="submit" name="submit" value="Add Course">
    
  </form>
  <h3>Assign grade weightage after adding course</h3>

  <form action='grade_weightage.php' method='post'>
<input type='hidden' name='crn' value=<?php echo $course_crn?>>
<input type='submit' value='Assign Grade weightage'>
      </form>
  <button class="button" onclick="document.location.href='teacher_main.php'">Back to Teachers Page</button>
  <hr>
  <p><a href="add_new_course.txt">Contents of this page</a></p>

</body>
</html>

