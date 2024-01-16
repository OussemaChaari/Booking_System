<?php 
require 'database_connection.php'; 

if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $delete_query = "DELETE FROM calendar_event_master WHERE event_id = $event_id";

    if ($con->query($delete_query) === TRUE) {
        $response = array('status' => true, 'msg' => 'Event deleted successfully');
    } else {
        $response = array('status' => false, 'msg' => 'Error deleting event: ' . $con->error);
    }
    echo json_encode($response);
} else {
    $response = array('status' => false, 'msg' => 'Event ID not provided');
    echo json_encode($response);
}
?>