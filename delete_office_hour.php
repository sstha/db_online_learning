<?php
include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');


$office_hour_id = $_POST['id'];


$sql = "SELECT o.id,o.start_time,o.end_time,o.day_of_week,o.instructor_id,c.crn,c.name,concat(i.fname,' ',i.lname) as iname
        FROM OfficeHours o 
        JOIN Course c on o.course_id=c.crn 
        JOIN Instructor i on c.instructor=i.ssn
        WHERE o.id = $office_hour_id ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Error: No office hour found with ID $office_hour_id";
    exit;
}


if (isset($_POST['confirm'])) {
    $sql = "DELETE FROM OfficeHours WHERE id = $office_hour_id";
    if ($conn->query($sql) === TRUE) {
        echo "Scheduled office hour deleted successfully";
    }else{
    echo "Error deleting scheduled office hour: " . $conn->error;
    }
        
    echo "<form id='redirect-form' method='POST' action='schedule_office_hours.php'>";
    echo "<input type='hidden' name='crn' value='" . $row['crn'] . "'>";
    echo "<input type='hidden' name='instructor' value='" . $row['instructor_id'] . "'>";
    echo "</form>";
    echo "<script>document.getElementById('redirect-form').submit();</script>";

    $conn->close();
    exit;
}

if (isset($_POST['cancel'])) {
    echo "<form id='redirect-form' method='POST' action='schedule_office_hours.php'>";
    echo "<input type='hidden' name='crn' value='" . $row['crn'] . "'>";
    echo "<input type='hidden' name='instructor' value='" . $row['instructor_id'] . "'>";
    echo "</form>";
    echo "<script>document.getElementById('redirect-form').submit();</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Scheduled Office Hour</title>
    <link rel="stylesheet" href="styling.css">

</head>
<body>
    <h2>Are you sure you want to delete the following scheduled office hour?</h2>
    <p>Course CRN: <?php echo $row['crn']; ?></p>
    <p>Course Name: <?php echo $row['name']; ?></p>
    <p>Instructor Name: <?php echo $row['iname']; ?></p>
    <p>Day: <?php echo $row['day_of_week']; ?></p>
    <p>Start Time: <?php echo $row['start_time']; ?></p>
    <p>End Time: <?php echo $row['end_time']; ?></p>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $office_hour_id; ?>">
        <input type="submit" name="confirm" value="Confirm Deletion">
        <input type="submit" name="cancel" value="Cancel">
        
    </form>
    <hr>
    <p><a href="delete_office_hour.txt">Contents of this Page</a></p>
</body>
</html>
