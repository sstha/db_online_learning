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

$course_id = $_POST['course_id'];
$instructor_id = $_POST['instructor'];


if (isset($_POST['id'])) {
    // If an appointment has been selected for booking
    $id = $_POST['id'];

    echo "<h1>Booking Confirmed</h1>";
    
    $query = "SELECT day_of_week, start_time, end_time
              FROM OfficeHours
              WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
 
    echo "<p>You have successfully booked an appointment for " . 
            $row['day_of_week'] . " from " . $row['start_time'] . " to " 
            . $row['end_time'] . ".</p>";

    // Delete the appointment from the OfficeHours table
    $query = "DELETE FROM OfficeHours WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    
}

// Display all available appointments for the given course and instructor
$query = "SELECT id, day_of_week, start_time, end_time,CONCAT(fname,' ',lname) AS iname
          FROM OfficeHours 
          LEFT JOIN Instructor on OfficeHours.instructor_id=Instructor.ssn
          WHERE course_id = '$course_id' AND instructor_id = '$instructor_id'";
$result = mysqli_query($conn, $query);
// $row = mysqli_fetch_array($result, MYSQLI_BOTH);


if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

echo "<h2>Book Office Hours</h2>";
echo "<h3>Course CRN: $course_id</h3>";
echo "<h3>Instructor name:" .$row['iname']. "</h3>";

if (mysqli_num_rows($result) > 0) {
    // Display the list of available appointments
    echo "<h3>Available Appointments</h3>";
    echo "<table>";
    echo "<tr><th>Date</th><th>Start Time</th><th>End Time</th><th>Book</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["day_of_week"] . "</td>";
        echo "<td>" . $row["start_time"] . "</td>";
        echo "<td>" . $row["end_time"] . "</td>";
        
        echo "<td>
        <form method='POST' action='book_office_hours.php'>
            <input type='hidden' name='id' value='" . $row["id"] . "'>
            <input type='hidden' name='course_id' value='" . $course_id . "'>
            <input type='hidden' name='instructor' value='" . $instructor_id . "'>
            <input type='submit' value='Book'>
        </form>
      </td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No available appointments found.</p>";
}

echo "<button class='button' onclick=\"document.location.href='welcome_menu_student.php'\">Back to Homepage</button>";
echo "<hr>";
echo "<p><a href='book_office_hours.txt'>Contents of this page</a></p>";

mysqli_close($conn);
?>
<html>
    <head>
    <link rel="stylesheet" href="styling.css">
    </head>
</html>
