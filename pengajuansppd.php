<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['tgl_pengajuan_surat']) && isset($_POST['tujuan_pengajuan_surat']) && isset($_POST['tgl_mulai_sppd']) && isset($_POST['tgl_selesai_sppd']) && isset($_POST['kode_pegawai'])) {
 
    // menerima parameter POST ( tanggal pengajuan, tujuan, tanggal mulai, tanggal selesai, dan kode pegawai )
    $tgl_pengajuan_surat = $_POST['tgl_pengajuan_surat'];
    $tujuan_pengajuan_surat = $_POST['tujuan_pengajuan_surat'];
    $tgl_mulai_sppd = $_POST['tgl_mulai_sppd'];
    $tgl_selesai_sppd = $_POST['tgl_selesai_sppd'];
    $kode_pegawai = $_POST['kode_pegawai'];

    if (strtotime($tgl_selesai_sppd) < strtotime($tgl_mulai_sppd)) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Tanggal selesai tidak boleh lebih kecil";
        echo json_encode($response);
    } else {
        // Menyimpan data pengajuan surat
        $user = $db->simpanPengajuansurat($tgl_pengajuan_surat, $tujuan_pengajuan_surat, $tgl_mulai_sppd, $tgl_selesai_sppd, $kode_pegawai);
        if ($user) {
            // Berhasil menyimpan pengajuan surat
            $response["error"] = FALSE;
            $response["user"]["kode_pengajuan_surat"] = $user["kode_pengajuan_surat"];
            $response["user"]["tanggal pengajuan"] = $user["tgl_pengajuan_surat"];
            $response["user"]["tujuan"] = $user["tujuan_pengajuan_surat"];
            $response["user"]["tanggal mulai"] = $user["tgl_mulai_sppd"];
            $response["user"]["tanggal selesai"] = $user["tgl_selesai_sppd"];
            $response["user"]["status"] = $user["status_pengajuan_surat"];
            $response["user"]["kode pegawai"] = $user["kode_pegawai"];
            $response["user"]["nip kasi"] = $user["nip_kasi"];
            echo json_encode($response);
        } else {
            // gagal menyimpan data
            $response["error"] = TRUE;
            $response["error_msg"] = "Terjadi kesalahan saat mengajukan surat";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter masukkan ada yang kurang";
    echo json_encode($response);
}
?>