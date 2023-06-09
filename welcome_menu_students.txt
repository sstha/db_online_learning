<?php
include('DatabaseConnection.txt');

session_start();

$conn = mysqli_connect($server, $user, $pass, $dbname, $port) 
or die('Error connecting to MySQL server.');

if (isset($_POST['student_id'])) {
    $_SESSION['student_id'] = $_POST['student_id'];
} else {
    if (!isset($_SESSION['student_id'])) {
    header("Location: student_main.php");
    exit;
}}

// $_SESSION['student_id'] = $_POST['student_id'];

$query = "SELECT CONCAT(firstname,' ',lastname) AS student_name ,email, phone,CONCAT( a.street,', ',a.city,' ,',a.state,',USA, ',a.zip_code ) AS address
          FROM Student s JOIN Address a on s.address_id=a.id
          WHERE student_id =" . $_SESSION['student_id'];
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$student_name=$row['student_name'];
$student_email=$row['email'];
$student_phone=$row['phone'];
$student_address=$row['address'];

mysqli_free_result($result);
mysqli_close($conn);
?>

<html>
<head>
<link rel="stylesheet" href="styling.css">

</head>
<body>
<h2> <center>Welcome <?php echo $student_name ?>!!</center> </h2>
<div>
  <p>Name:<?php echo $student_name ?></p>
  <p>Email:<?php echo $student_email ?></p>
  <p>Phone: <?php echo $student_phone ?></p>
  <p>Address: <?php echo $student_address?></p>
</div>
<div id="content" align="center">
  <div class="button-container">
    <button class="button" onclick="document.location.href='my_courses.php'">Enrolled Courses</button>
    <button class="button" onclick="document.location.href='register.php'">Register</button>
    <button class="button" onclick="document.location.href='view_grades.php'">View Grades</button>
  </div>

  <button class="logout" onclick="location.href='homepage.html'" >Logout</button>
</div>

<hr>
<p><a href="welcome_menu_students.txt">Contents for this page</a></p>

</body>
</html>
