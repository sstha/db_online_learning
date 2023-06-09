<?php
include('DatabaseConnection.txt');
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: student_main.php');
    exit();
} 

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

$student_id = $_SESSION['student_id'];

if (isset($_POST['submit'])) {
    $selected_enrollment_id = $_POST['id'];
    
    $sql = "DELETE FROM Grade WHERE enrollment_id = $selected_enrollment_id";
    if ($conn->query($sql) === TRUE) {
        // If the grade record was deleted successfully, delete the enrollment record
        $sql = "DELETE FROM Enrollment WHERE id = $selected_enrollment_id";
        if ($conn->query($sql) === TRUE) {
            echo "Course Dropped";
        } else {
            echo "Error Dropping Course: " . $conn->error;
        }
    } else {
        echo "Error Dropping Course: " . $conn->error;
    }
}

$query = "SELECT Course.crn, Course.name, CONCAT(Instructor.fname, ' ', Instructor.lname) AS instructor_name, Enrollment.student_id, Enrollment.id
FROM Enrollment
JOIN Course ON Enrollment.course_id = Course.crn
JOIN Instructor ON Course.instructor = Instructor.ssn
WHERE Enrollment.student_id = $student_id";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Drop course</title>
    <link rel="stylesheet" href="styling.css">

</head>
<body>

    <h2>Drop courses</h2>
    <h3>Select Course to Drop</h3>
    <form  method="POST">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<label><input type="radio" name="id" value="' . $row['id'] . '" required> CRN '. $row['crn'] . ' - '. $row['name'] . ' (' . $row['instructor_name'] . ')</label><br>';
        }
        mysqli_free_result($result);
        ?>
        <input id="sample1" type="submit" name="submit" value="Drop">
    </form>

   

    <br>
    <button  class="button" onclick="document.location.href='welcome_menu_student.php'">Back to Homepage</button>

    <hr>
    <a href="drop_course.txt">Contents of this page</a>

    <?php
    

    mysqli_free_result($result);
    mysqli_close($conn);
    ?>

</body>
</html>
