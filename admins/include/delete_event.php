<?php
include '../../database.php';

$id = $_GET['id'];
$conn->query("DELETE FROM events WHERE event_id = $id");

header("Location: ../manage_event.php");
exit();
?>
