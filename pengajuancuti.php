<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['tgl_pengajuan_cuti']) && isset($_POST['alasan_pengajuan_cuti']) && isset($_POST['tgl_mulai_cuti']) && isset($_POST['tgl_selesai_cuti']) && isset($_POST['kode_pegawai'])) {
 
    // menerima parameter POST ( tanggal pengajuan, alasan, tanggal mulai, tanggal selesai, dan kode pegawai )
    $tgl_pengajuan_cuti = $_POST['tgl_pengajuan_cuti'];
    $alasan_pengajuan_cuti = $_POST['alasan_pengajuan_cuti'];
    $tgl_mulai_cuti = $_POST['tgl_mulai_cuti'];
    $tgl_selesai_cuti = $_POST['tgl_selesai_cuti'];
    $kode_pegawai = $_POST['kode_pegawai'];

    if (strtotime($tgl_selesai_cuti) < strtotime($tgl_mulai_cuti)) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Tanggal selesai tidak boleh lebih kecil";
        echo json_encode($response);
    } else {
        // Menyimpan data pengajuan cuti
        $user = $db->simpanPengajuanCuti($tgl_pengajuan_cuti, $alasan_pengajuan_cuti, $tgl_mulai_cuti, $tgl_selesai_cuti, $kode_pegawai);
        if ($user) {
            // Berhasil menyimpan pengajuan cuti
            $response["error"] = FALSE;
            $response["user"]["kode_pengajuan_cuti"] = $user["kode_pengajuan_cuti"];
            $response["user"]["tanggal pengajuan"] = $user["tgl_pengajuan_cuti"];
            $response["user"]["alasan"] = $user["alasan_pengajuan_cuti"];
            $response["user"]["tanggal mulai"] = $user["tgl_mulai_cuti"];
            $response["user"]["tanggal selesai"] = $user["tgl_selesai_cuti"];
            $response["user"]["status"] = $user["status_pengajuan_cuti"];
            $response["user"]["kode pegawai"] = $user["kode_pegawai"];
            $response["user"]["nip kasi"] = $user["nip_kasi"];
            echo json_encode($response);
        } else {
            // gagal menyimpan data
            $response["error"] = TRUE;
            $response["error_msg"] = "Terjadi kesalahan saat mengajukan cuti";
            echo json_encode($response);
        }   
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter masukkan ada yang kurang";
    echo json_encode($response);
}
?>