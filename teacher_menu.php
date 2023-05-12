<?php
session_start();
if(isset($_POST['submit'])) {
    $instructor_id = $_POST['ssn'];
    echo "The selected instructor ID is: " . $instructor_id;
}
?>
