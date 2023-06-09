<?php

include('DatabaseConnection.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>

    <h2>Student Page</h2>

    <form class="centerform" action="welcome_menu_student.php" method="POST">
        <label for="student_id">Select your Student ID to login:</label>
        <select name="student_id" id="student_id" required>
            <option value="">Select ID</option>
            <?php
                $query = "SELECT student_id FROM Student order by student_id;";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
                {
                    echo '<option value="'.$row['student_id'].'">'.$row['student_id'].'</option>';
                }

                mysqli_free_result($result);
                mysqli_close($conn);
            ?>
        </select>
        <input type="submit" name="submit" value="Login">
    </form>

    <hr>
    <p><a href="student_main.txt">Contents for this page</a><p>

      </body>
      </html>
