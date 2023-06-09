<?php

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<html>
<head>
  <title>Teacher Main Page</title>
  </head>

  <body bgcolor="white">
  
  <hr>

  <h3>Instructor Page</h3>

<hr>

<p>
Select instructor name and course.

<p>  
  
<?php

$query = "SELECT CONCAT(fname,' ',lname) AS fullname, Instructor.ssn FROM Instructor ORDER BY 1";

?>
<form action="course_page.php" method="POST">
    
    <label>Name of Instructor</label>
    <select name="instructor" onchange="this.form.submit()" required>
    <option value="">Select Instructor</option>
    <?php
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
      {
        echo '<option value="'.$row['ssn'].'">'.$row['fullname'].'</option>';
      }
    
    mysqli_free_result($result);
    
    ?>
    </select>
    </form>
    
    <?php
    if(isset($_POST['instructor'])) {
      $instructor_id = $_POST['instructor'];
      $course_query = "SELECT c.name FROM Course c WHERE c.instructor='$instructor_id'";
      $course_result = mysqli_query($conn, $course_query) or die(mysqli_error($conn));
      if(mysqli_num_rows($course_result) > 0) {
        echo '<br><br><label>Course Taught by Instructor:</label><select name="course" required>';
        echo '<option value="">Select Course</option>';
        while($row = mysqli_fetch_array($course_result, MYSQLI_BOTH)) {
          echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
        }
        echo '</select>';
        echo '<input type="submit" name="submit" value="Submit">';
      } else {
        echo '<br><br>No courses found for the selected instructor.';
      }
      mysqli_free_result($course_result);
    }
    
    mysqli_close($conn);
    ?>
       
</body>
</html>
