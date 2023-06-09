<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_main.php");
    exit;
}

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (isset($_POST['submit_rating'])) {
    $rating = $_POST['rating'];
    $student_id = $_SESSION['student_id'];
    $course_id = $_POST['course_id'];
    $query = "INSERT INTO rating (course_id, student_id, rating) VALUES ('$course_id', '$student_id', '$rating')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    $referrer = basename($_SERVER['HTTP_REFERER']);

    $redirect_url = "student_course_page.php?crn=$course_id";
        if ($referrer == "course_info_instructor.php") {
            $redirect_url .= "&from=course_info_instructor";
        } else {
            $redirect_url .= "&from=other_page";
        }

    
    $_SESSION['success_message'] = "Rating submitted successfully!";
    header("Location: $redirect_url?crn=$course_id");

    exit;
}


?>