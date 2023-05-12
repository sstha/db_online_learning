<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');
?>

<?php
// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_main.php");
    exit;
}

$query = "SELECT Course.crn, Course.name, CONCAT(Instructor.fname, ' ', Instructor.lname) AS instructor_name, Enrollment.student_id
          FROM Enrollment
          JOIN Course ON Enrollment.course_id = Course.crn
          JOIN Instructor ON Course.instructor = Instructor.ssn
          WHERE Enrollment.student_id = " . $_SESSION['student_id'];

$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

echo "<h2 style='color:#00539C;'>My Courses</h2>";
if (mysqli_num_rows($result) > 0) {
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>CRN</th><th style='padding: 8px; text-align: left; border-bottom: 1px solid #ddd;'>Course Name</th><th style='padding: 8px; text-align: left; border-bottom: 1px solid #ddd;'>Instructor</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . $row["crn"] . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'><a href='student_course_page.php?crn=" . $row["crn"] . "'>" . $row["name"] . "</a></td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . $row["instructor_name"] . "</td>";
        echo "</tr>";
        $student_id=$row["student_id"];

    }
    echo "</table>";
    
} else {
    echo "You are not enrolled in any courses.";
}

echo "<h2 style='color:#00539C;text-align:left;'>Drop course</h2>
<form method='POST' action='drop_course.php'>
            <input type='hidden' name='id' value= $student_id>
            <input type='submit' style='background-color: #00539C; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;aligh' value='Drop'>
        </form>";




mysqli_close($conn);
?>

<html>
<head>
<link rel="stylesheet" href="styling.css">

</head>
<body>

    <button style='background-color: #00539C; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;' onclick="document.location.href='welcome_menu_student.php'">Back to Homepage</button>
</div>
<hr>
<p><a href="my_courses.txt">Contents of this Page</a></p>
</body>
</html>
