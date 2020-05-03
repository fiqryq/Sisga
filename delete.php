<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['username'])) {
 
    $username = $_POST['username'];

    $user = $db->deleteAbsen($username);
    if ($user) {
        $response["error"] = FALSE;
        $response["user"]["username"] = $user["username"];
        echo json_encode($response);
    } else {
        // gagal menyimpan user
        $response["error"] = TRUE;
        $response["error_msg"] = "Terjadi kesalahan saat menghapus";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter ada yang kurang";
    echo json_encode($response);
}
?>