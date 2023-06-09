<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: student_main.php');
    exit();
} 

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (isset($_POST['submit'])) {
  
    $selected_course_crn = $_POST['crn'];


    $query = "SELECT * FROM Enrollment WHERE student_id = {$_SESSION['student_id']} AND course_id = {$selected_course_crn};";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $query2="SELECT * FROM Course WHERE crn = $selected_course_crn";
    $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
    $newrow=mysqli_fetch_array($result2, MYSQLI_ASSOC);
    $name=$newrow['name'];
 

    $query = "INSERT INTO Enrollment (student_id, course_id) VALUES ({$_SESSION['student_id']}, {$selected_course_crn});";
    
    if (!mysqli_query($conn, $query)) {
        $error = mysqli_error($conn);
        if (strpos($error, 'uc_enrollment') !== false) {
            $message = "You are already enrolled in this course.";
        } else {
            die($error);
        }
    } else {

    // Remove selected course from list of available courses
        $query = "SELECT c.crn, c.name, CONCAT(i.fname, ' ', i.lname) AS instructor_name
            FROM Course c
            JOIN Instructor i ON c.instructor = i.ssn
            WHERE c.crn NOT IN (
            SELECT course_id
            FROM Enrollment
            WHERE student_id = {$_SESSION['student_id']}
            );";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // $name=$row['name'];
        // Display success message and updated list of available courses
        $message = "You have successfully enrolled in (CRN: {$selected_course_crn}-{$name}).";
        $class = "success";}
    
} else {
    // Display list of available courses
    $query = "SELECT c.crn, c.name, CONCAT(i.fname, ' ', i.lname) AS instructor_name
            FROM Course c
            JOIN Instructor i ON c.instructor = i.ssn
            WHERE c.crn NOT IN (
            SELECT course_id
            FROM Enrollment
            WHERE student_id = {$_SESSION['student_id']}
            );";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register for Courses</title>
    <link rel="stylesheet" href="styling.css">

</head>
<body>
    <h2>Available Courses</h2>
    <?php
    if (isset($message)) {
        echo '<div class="' . $class . '">' . $message . '</div>';
        echo '<br>';
    }
    ?>
    <form method="POST">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<label><input type="radio" name="crn" value="' . $row['crn'] . '" required> CRN '. $row['crn'] . ' - '. $row['name'] . ' (' . $row['instructor_name'] . ')</label><br>';
        }
        mysqli_free_result($result);
        ?>
        <br>
        <input type="submit" name="submit" value="Register">
    </form>
<br>
    <button class="button" onclick="location.href='my_courses.php'">Back to My Courses</button>
    <button class="button" onclick="location.href='welcome_menu_student.php'">Back to Home Page</button>
    <hr>
    <p><a href="register.txt">Contents of this page</a></p>
</body>
</html>
