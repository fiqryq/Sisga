<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['tanggal_absen']) && isset($_POST['kode_pegawai'])) {
 
    // menerima parameter POST ( tanggal absen, jam masuk, jam keluar, dan kode pegawai )
    $tanggal_absen = $_POST['tanggal_absen'];
    $kode_pegawai = $_POST['kode_pegawai'];

    $get = $db->getAbsenMasuk($tanggal_absen, $kode_pegawai);
    
    if($get != false) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Anda sudah melakukan absen masuk hari ini";
        echo json_encode($response);
    } else {
        $response["error"] = FALSE;
        $response["absen"] = "Silahkan untuk melakukan absen masuk";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter masukkan ada yang kurang";
    echo json_encode($response);
}
?>