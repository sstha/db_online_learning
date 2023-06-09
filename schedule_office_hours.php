<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');


if (isset($_POST['crn']) && isset($_POST['day_of_week']) && isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['instructor'])) {

    $course_id = $_POST['crn'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $instructor_id = $_POST['instructor'];
    
    $query = "INSERT INTO OfficeHours (course_id, instructor_id, day_of_week, start_time, end_time) 
    VALUES ('$course_id', '$instructor_id', '$day_of_week', '$start_time', '$end_time')";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

}


if (isset($_POST['crn']) && isset($_POST['instructor'])) {
    $course_id = $_POST['crn'];
    $instructor_id = $_POST['instructor'];

    $query = "SELECT c.name, o.id, o.day_of_week, o.start_time, o.end_time 
    FROM Course c
    LEFT JOIN OfficeHours o on c.crn=o.course_id
    WHERE c.crn='$course_id' AND c.instructor='$instructor_id'"; 
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    $course_name = $row['name'];
}


$query = "SELECT id, day_of_week, start_time, end_time 
        FROM OfficeHours WHERE course_id='$course_id' AND instructor_id='$instructor_id'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));


// Check if the form has been submitted to update the list of office hours

// if (isset($_POST['update'])) {
//     $query = "SELECT c.name, o.id,o.day_of_week, o.start_time, o.end_time 
//     FROM OfficeHours o
//     JOIN Course c on o.course_id=c.crn
//     WHERE o.course_id='$course_id' AND o.instructor_id='$instructor_id'";
//     $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
//     $row = mysqli_fetch_array($result, MYSQLI_BOTH);
//     echo "update";
//     $course_name = $row['name'];
//     echo $course_name;
    
// }

?>

<html>
<head>
    <title>Schedule Office Hours</title>
    <link rel="stylesheet" href="styling.css">

</head>
<body>
    <h2>Schedule Office Hours for CRN <?php echo $course_id; ?> - <?php echo $course_name;?></h2>

    <form method="POST" action="schedule_office_hours.php">
        <input type="hidden" name="crn" value="<?php echo $course_id; ?>">
        <input type="hidden" name="instructor" value="<?php echo $instructor_id; ?>">

<label for="day_of_week">Day of Week:</label>
<select id="day_of_week" name="day_of_week">
    <option value="Monday">Monday</option>
    <option value="Tuesday">Tuesday</option>
    <option value="Wednesday">Wednesday</option>
    <option value="Thursday">Thursday</option>
    <option value="Friday">Friday</option>
</select>
<br>

<label for="start_time">Start Time:</label>
<input type="time" id="start_time" name="start_time"><br>

<label for="end_time">End Time:</label>
<input type="time" id="end_time" name="end_time"><br>
<br>
<input type="submit" value="Schedule">
</form>


<h3>Currently Scheduled hours</h3>

<table >
<tr>
    <th>Day of Week</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)): ?>
<tr>
    <td><?php echo $row['day_of_week']; ?></td>
    <td><?php echo $row['start_time']; ?></td>
    <td><?php echo $row['end_time']; ?></td>
    <td>
        <form method="POST" action="delete_office_hour.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">
            <input type="hidden" name="day_of_week" value="<?php echo $row['day_of_week']; ?>">
            <input type="hidden" name="start_time" value="<?php echo $row['start_time']; ?>">
            <input type="hidden" name="end_time" value="<?php echo $row['end_time']; ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
<br>
<br>
<form action="course_info_instructor.php" method="post">
<input type="hidden" name="crn" value="<?php echo $course_id; ?>">
<input type="submit" value="Back to Course Page">
</form>
<hr>
<p><a href="schedule_office_hours.txt">Contents for this page</a></p>
</body>
</html>
