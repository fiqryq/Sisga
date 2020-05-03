<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['tanggal_absen']) && isset($_POST['jam_masuk']) && isset($_POST['jam_keluar']) && isset($_POST['kode_pegawai'])) {
 
    // menerima parameter POST ( tanggal absen, jam masuk, jam keluar, dan kode pegawai )
    $tanggal_absen = $_POST['tanggal_absen'];
    $jam_masuk = $_POST['jam_masuk'];
    $jam_keluar = $_POST['jam_keluar'];
    $kode_pegawai = $_POST['kode_pegawai'];

    $get = $db->getAbsenMasuk($tanggal_absen, $kode_pegawai);
    
    if($get != false) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Anda sudah melakukan absen masuk hari ini";
        echo json_encode($response);
    } else {
         // Menyimpan data absen
         $user = $db->simpanAbsen($tanggal_absen, $jam_masuk, $jam_keluar, $kode_pegawai);
         if ($user) {
             // Berhasil menyimpan absen
             $response["error"] = FALSE;
             $response["user"]["kode_absensi"] = $user["kode_absensi"];
             $response["user"]["tanggal_absen"] = $user["tanggal_absen"];
             $response["user"]["tanggal mulai"] = $user["jam_masuk"];
             $response["user"]["tanggal selesai"] = $user["jam_keluar"];
             $response["user"]["kode pegawai"] = $user["kode_pegawai"];
             $response["user"]["nip kasi"] = $user["nip_kasi"];
             echo json_encode($response);
         } else {
             // gagal menyimpan data
             $response["error"] = TRUE;
             $response["error_msg"] = "Terjadi kesalahan saat absen";
             echo json_encode($response);
         }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter masukkan ada yang kurang";
    echo json_encode($response);
}
?>