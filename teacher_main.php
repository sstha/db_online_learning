<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<html>
<head>
  <title>Instructor Main Page</title>
  <link rel="stylesheet" href="styling.css">

</head>


 

  <h2>Instructor Page</h2>



  <h3> Click to add a new course</h3>

  <button class="button" onclick="document.location.href='add_new_course.php'">Add New Course</button>
<hr>
  <h3>Select your name to login to the course page</h3>  

  <form action="" method="POST">
  
    <label>Name of Instructor:</label>
    <select name="ssn" required>
      <option value="">Select Name</option>
      <?php
        $query = "SELECT CONCAT(fname,' ',lname) AS fullname, ssn FROM Instructor ORDER BY 1";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          echo '<option value="' . $row['ssn'] . '">' . $row['fullname'] . '</option>';
        }
        mysqli_free_result($result);
      ?>
    </select>

    <br><br>
   
    <input type="submit" name="submit" value="Submit">

  </form>

  <?php
    if (isset($_POST['ssn'])) {
      $instructor_id = $_POST['ssn'];
      $_SESSION['instructor_ssn'] = $instructor_id;
      $query="SELECT CONCAT(fname,' ',lname) AS fullname 
            FROM Instructor WHERE ssn='$instructor_id'";
      $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
      $row = mysqli_fetch_array($result, MYSQLI_BOTH);
      echo "<h3>Hi " . $row['fullname']. "!!!</h3>";
      echo "<p>Select Your course:</p>";
      echo "<form action='course_info_instructor.php' method='POST'>";
      echo "<select name='crn' required>";
      echo "<option value=''>Select Course</option>";
      $query = "SELECT c.crn, c.name FROM Course c WHERE c.instructor = '$instructor_id' ORDER BY 1";
      $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
      while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
        echo '<option value="' . $row['crn'] . '">' . $row['name'] . '</option>';
      }
      echo "</select>";
      echo "<br><br>";
      echo "<input type='submit' name='submit' value='View Course Information'>";
      echo "</form>";
      mysqli_free_result($result);
    }

    mysqli_close($conn);
  ?>

<hr>
<p><a href="teacher_main.txt">Contents for this page</a></p>
</body>
</html>
