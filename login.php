<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['username_pegawai']) && isset($_POST['pass_pegawai'])) {
 
    // menerima parameter POST ( username dan password )
    $username_pegawai = $_POST['username_pegawai'];
    $pass_pegawai = $_POST['pass_pegawai'];
 
    // get the user by username and password
    // get user berdasarkan username dan password
    $user = $db->getUserByusernameAndPassword($username_pegawai, $pass_pegawai);
 
    if ($user != false) {
        // user ditemukan
        $response["error"] = FALSE;
        $response["user"]['kode_pegawai'] = $user["kode_pegawai"];
        $response["user"]["nama_pegawai"] = $user["nama_pegawai"];
        $response["user"]["jabatan_pegawai"] = $user["jabatan_pegawai"];
        $response["user"]["email_pegawai"] = $user["email_pegawai"];
        $response["user"]["telp_pegawai"] = $user["telp_pegawai"];
        $response["user"]["no_rekening_pegawai"] = $user["no_rekening_pegawai"];
        $response["user"]["jalan_pegawai"] = $user["jalan_pegawai"];
        $response["user"]["no_rumah_pegawai"] = $user["no_rumah_pegawai"];
        $response["user"]["rt_rw_pegawai"] = $user["rt_rw_pegawai"];
        $response["user"]["kec_pegawai"] = $user["kec_pegawai"];
        $response["user"]["kota_pegawai"] = $user["kota_pegawai"];
        $response["user"]["kode_pos_pegawai"] = $user["kode_pos_pegawai"];
        $response["user"]["username_pegawai"] = $user["username_pegawai"];
        $response["user"]["pass_pegawai"] = $user["pass_pegawai"];
        echo json_encode($response);
    } else {
        // user tidak ditemukan password/username salah
        $response["error"] = TRUE;
        $response["error_msg"] = "Maaf, username atau password salah";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (username atau password) ada yang kurang";
    echo json_encode($response);
}
?>