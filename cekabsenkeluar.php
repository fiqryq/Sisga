<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['tanggal_absen']) && isset($_POST['kode_pegawai']) && isset($_POST['jam_keluar'])) {
 
    $tanggal_absen = $_POST['tanggal_absen'];
    $kode_pegawai = $_POST['kode_pegawai'];
    $jam_keluar = $_POST['jam_keluar'];

    $get = $db->getAbsenKeluar($tanggal_absen, $kode_pegawai, $jam_keluar);

    if($get != false) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Anda sudah melakukan absen keluar hari ini";
        echo json_encode($response);
    } else {
        $response["error"] = FALSE;
        $response["absen"] = "Silahkan untuk melakukan absen keluar";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter ada yang kurang";
    echo json_encode($response);
}
?>