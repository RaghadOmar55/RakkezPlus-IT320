<?php
include("db_connection.php");

if(isset($_POST['id'])){
    $id = (int) $_POST['id'];

    $query = "DELETE FROM notification WHERE notification_id = $id";
    mysqli_query($conn, $query);

}
?>