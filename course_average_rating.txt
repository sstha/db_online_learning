<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (!isset($_GET['crn'])) {
    echo "Error: Course CRN not specified.";
    exit;
}
$crn = $_GET['crn'];

$query = "SELECT AVG(rating) AS avg_rating
          FROM rating
          WHERE course_id = '$crn'";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$row = mysqli_fetch_assoc($result);
$avg_rating = $row['avg_rating'];

$query = "SELECT COUNT(*) AS rating_count
          FROM rating
          WHERE course_id = '$crn'";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$row = mysqli_fetch_assoc($result);
$rating_count = $row['rating_count'];

if ($rating_count == 0) {
    $_SESSION['average_rating'] = "No ratings yet";
} else {
    $_SESSION['average_rating'] = $avg_rating;
}

if (isset($_GET['view_rating'])) {
    header("Location: course_info_instructor.php?crn=$crn&avg_rating=$avg_rating");
}
if (isset($_GET['view_rating_student'])) {
    header("Location: student_course_page.php?crn=$crn&avg_rating=$avg_rating");
}


echo "<hr>";
echo"<a href='course_average_rating.txt'> Contents of this page</a>";
mysqli_close($conn);
?>

