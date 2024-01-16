<?php
require 'database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_start_date = $_POST['event_start_date'];
    $event_end_date = $_POST['event_end_date'];

    $update_query = $con->prepare("UPDATE calendar_event_master SET event_name=?, event_start_date=?, event_end_date=? WHERE event_id=?");

    $update_query->bind_param("sssi", $event_name, $event_start_date, $event_end_date, $event_id);

    if ($update_query->execute()) {
        $response = array('status' => true, 'msg' => 'Event updated successfully');
    } else {
        $response = array('status' => false, 'msg' => 'Error updating event: ' . $con->error);
    }

    $update_query->close();
} else {
    $response = array('status' => false, 'msg' => 'Invalid request');
}

echo json_encode($response);
?>