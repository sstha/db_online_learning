<?php
session_start();

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');
?>

<?php
if (!isset($_SESSION['student_id'])) {
    header("Location: student_main.php");
    exit;
}

$select_query = "SELECT e.id, g.assignment_grade, g.exam_grade, g.participation_grade, 
                ROUND((COALESCE(g.assignment_grade, 0) + COALESCE(g.exam_grade, 0) + COALESCE(g.participation_grade, 0)),2) AS overall_grade,
                c.crn,c.name
                FROM Enrollment e 
                INNER JOIN Course c ON e.course_id=c.crn
                INNER JOIN Student s ON e.student_id = s.student_id 
                LEFT JOIN grade g ON e.id = g.enrollment_id
                WHERE e.student_id =" . $_SESSION['student_id'];

$result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));


if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

echo "<h2 style='color:#00539C;'> Grades</h2>";
if (mysqli_num_rows($result) > 0) {
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>CRN</th><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>Course Name</th><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>Assignment</th><th>Exam</th><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>Participation</th><th style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>Overall Grade</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . $row["crn"] . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . $row["name"] . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . ($row["assignment_grade"] ?? 0) . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . ($row["exam_grade"] ?? 0) . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . ($row["participation_grade"] ?? 0) . "</td>";
        echo "<td style='padding: 5px; text-align: left; border-bottom: 1px solid #ddd;'>" . $row["overall_grade"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "You are not enrolled in any courses.";
}

mysqli_close($conn);
?>

<html>
<head>
<link rel="stylesheet" href="styling.css">

</head>
<body>
    <button  class="button" onclick="document.location.href='welcome_menu_student.php'">Back to Homepage</button>
</div>
<hr>
<p><a href="view_grades.txt">Contents for this page</a></p>
</body>
</html>
