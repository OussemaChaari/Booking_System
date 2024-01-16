<?php 
require 'database_connection.php'; 

if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $select_query = "SELECT event_name, event_start_date, event_end_date FROM calendar_event_master WHERE event_id = $event_id";
    $result = $con->query($select_query);

    if ($result->num_rows > 0) {
        $event_details = $result->fetch_assoc();
        $response = array('status' => true, 'data' => $event_details);
    } else {
        $response = array('status' => false, 'msg' => 'Event details not found.');
    }

    echo json_encode($response);
} else {
    $response = array('status' => false, 'msg' => 'Event ID not provided');
    echo json_encode($response);
}
?>