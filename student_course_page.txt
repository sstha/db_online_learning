<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_main.php");
    exit;
}

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (!isset($_GET['crn'])) {
    echo "Error: Course CRN not specified.";
    exit;
}
$crn = $_GET['crn'];

$query = "SELECT name,instructor, CONCAT(Instructor.fname, ' ', Instructor.lname) AS instructor_name
          FROM Course
          JOIN Instructor ON Course.instructor = Instructor.ssn
          WHERE crn = '$crn'";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$row = mysqli_fetch_assoc($result);
$course_name = $row['name'];
$instructor_name = $row['instructor_name'];
$instructor_id = $row['instructor'];


$query = "SELECT Student.firstname, Student.lastname, Enrollment.grade
          FROM Enrollment
          JOIN Student ON Enrollment.student_id = Student.student_id
          WHERE Enrollment.course_id = '$crn'";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

echo "<h2>$course_name</h2>";
echo "<h2>Instructor: $instructor_name</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Enrolled Students</h3>";
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["firstname"] . "</td>";
        echo "<td>" . $row["lastname"] . "</td>";
        
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No students are enrolled in this course.</p>";
}

mysqli_close($conn);
?>

<html>
<head>
<link rel="stylesheet" href="styling.css">
</head>
<body>
<h3>Book Office Hours</h3>
<form id="noalignform" action="book_office_hours.php" method="post">
    <input type="hidden" name="course_id" value="<?php echo $crn; ?>">
    <input type="hidden" name="instructor" value="<?php echo $instructor_id; ?>">
    <input type='Submit' value='Book'>

</form>


<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>
<h3>Rate Course</h3>
<form action="rate_course.php" method="post">
    <input type="hidden" name="course_id" value="<?php echo $crn; ?>">
    <label for="rating">Rating:</label>
    <select name="rating" id="rating">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>


    <input type="submit" name="submit_rating" value="Rate">
</form>

<!-- // Display the average rating if it exists -->
<form method='get' action='course_average_rating.php'>
  <input type='hidden' name='crn' value= "<?php echo $crn; ?>">
  <input type='submit' name='view_rating_student' value='View Rating'>
</form>

<?php
if (isset($_SESSION['average_rating'])) {
    echo "<p><em>Current Average rating: " . $_SESSION['average_rating'] . "</em></p>";
    unset($_SESSION['average_rating']);
}
?>

    <button class="button" onclick="document.location.href='welcome_menu_student.php'">Back to Homepage</button>
<hr>
<p><a href="student_course_page.txt">Content for this page</a></p>
</div>
</body>
</html>
