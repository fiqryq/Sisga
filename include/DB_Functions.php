<?php
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // koneksi ke database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }

    /**
     * Get user berdasarkan username dan password
     */
    public function getUserByusernameAndPassword($username_pegawai, $pass_pegawai) {
 
        // Perintah untuk mendapatkan data sesuai dengan username dan password pegawai
        $stmt = $this->conn->prepare("SELECT * FROM pegawai_honorer WHERE username_pegawai = ? AND pass_pegawai = ?");
        $stmt->bind_param("ss", $username_pegawai, $pass_pegawai);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    public function getAbsenMasuk($tanggal_absen, $kode_pegawai) {
        $stmt = $this->conn->prepare("SELECT * FROM absensi_pegawai WHERE tanggal_absen = ? AND kode_pegawai = ?");
        $stmt->bind_param("ss", $tanggal_absen, $kode_pegawai);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    public function getAbsenKeluar($tanggal_absen, $kode_pegawai) {
        $jam_keluar = "00:00:00";
        $stmt = $this->conn->prepare("SELECT * FROM absensi_pegawai WHERE tanggal_absen = ? AND kode_pegawai = ? AND jam_keluar != ?");
        $stmt->bind_param("sss", $tanggal_absen, $kode_pegawai, $jam_keluar);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    // public function getCuti($kode_pegawai) {
    //     $smt = $this->conn->prepare("SELECT cuti.kode_cuti, kode_pengajuan_cuti, GROUP_CONCAT(pengajuan_cuti.kode_pegawai) AS bilderCSV FROM cuti INNER JOIN pengajuan_cuti ON cuti.kode_pengajuan_cuti = pengajuan_cuti.kode_pengajuan_cuti GROUP BY cuti.kode_cuti WHERE kode_pegawai = ?");
    //     $smt->bind_param("s", $kode_pegawai);
    //     if($smt->execute()){
    //         $user = $stmt->get_result()->fetch_assoc();
    //         $stmt->close();
    //         return $user;
    //     } else {
    //         return NULL;
    //     }
    // }

    public function simpanAbsen($tanggal_absen, $jam_masuk, $jam_keluar, $kode_pegawai) {
       
        // Perintah untuk mengambil kode absensi
        $stmt = $this->conn->prepare("SELECT kode_absensi FROM absensi_pegawai ORDER BY kode_absensi DESC");
		$stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        // Perintah untuk melanjutkan kode absensi sesuai angka yang sudah ada
		$split = explode('-', $user['kode_absensi']);
		$number = str_pad($split[1]+1,3,0, STR_PAD_LEFT);
        $kode_absensi = "SN-".$number;

        // Perintah untuk mengambil nip_kasi sesuai dengan kode_pegawai
        // $stmt2 = $this->conn->prepare("SELECT nip_kasi FROM pegawai_honorer WHERE kode_pegawai = ?");
        // $stmt2->bind_param("s", $kode_pegawai);
        // $stmt2->execute();
        // $nip_kasi = $stmt2->get_result()->fetch_assoc();
        // $stmt2->close();

        $nip_kasi = "19820614 200901 1 005";
                
        // Perintah untuk menyimpan data pengajuan cuti
        $stmt3 = $this->conn->prepare("INSERT INTO absensi_pegawai(kode_absensi, tanggal_absen, jam_masuk, jam_keluar, kode_pegawai, nip_kasi) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("ssssss", $kode_absensi, $tanggal_absen, $jam_masuk, $jam_keluar, $kode_pegawai, $nip_kasi);
        $result = $stmt3->execute();
        $stmt3->close();
 
        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM absensi_pegawai WHERE kode_pegawai = ?");
            $stmt->bind_param("s", $kode_pegawai);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }

    public function updateAbsen($tanggal_absen, $kode_pegawai, $jam_keluar) {
        $stmt = $this->conn->prepare("UPDATE absensi_pegawai SET jam_keluar = ? WHERE kode_pegawai = ? AND tanggal_absen = ?");
        $stmt->bind_param("sss", $jam_keluar, $kode_pegawai, $tanggal_absen);
        
        $result = $stmt->execute();
        $stmt->close();
 
        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM absensi_pegawai WHERE kode_pegawai = ? AND tanggal_absen = ?");
            $stmt->bind_param("ss", $kode_pegawai, $tanggal_absen);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }

    public function simpanPengajuansurat($tgl_pengajuan_surat, $tujuan_pengajuan_surat, $tgl_mulai_sppd, $tgl_selesai_sppd, $kode_pegawai) {
       
        // Perintah untuk mengambil kode pengajuan surat
        $stmt = $this->conn->prepare("SELECT kode_pengajuan_surat FROM pengajuan_surat_perjalanan_dinas ORDER BY kode_pengajuan_surat DESC");
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        // Perintah untuk melanjutkan kode pengajuan surat sesuai angka yang sudah ada
        $split = explode('-', $user['kode_pengajuan_surat']);
        $number = str_pad($split[1]+1,3,0, STR_PAD_LEFT);
        $kode_pengajuan_surat = "PD-".$number;
 
        // Perintah untuk mengambil nip_kasi sesuai dengan kode_pegawai
        // $stmt2 = $this->conn->prepare("SELECT nip_kasi FROM pegawai_honorer WHERE kode_pegawai = ?");
        // $stmt2->bind_param("s", $kode_pegawai);
        // $stmt2->execute();
        // $nip_kasi = $stmt2->get_result()->fetch_assoc();
        // $stmt2->close();
 
        $nip_kasi = "19820614 200901 1 005";
        $status_pengajuan_surat = "Menunggu";
        $ket_pengajuan_sppd = "Menunggu persetujuan dari kepala seksi";
                
        // Perintah untuk menyimpan data pengajuan surat
        $stmt3 = $this->conn->prepare("INSERT INTO pengajuan_surat_perjalanan_dinas(kode_pengajuan_surat, tgl_pengajuan_surat, tujuan_pengajuan_surat, tgl_mulai_sppd, tgl_selesai_sppd, status_pengajuan_surat, ket_pengajuan_sppd, kode_pegawai, nip_kasi) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("sssssssss", $kode_pengajuan_surat, $tgl_pengajuan_surat, $tujuan_pengajuan_surat, $tgl_mulai_sppd, $tgl_selesai_sppd, $status_pengajuan_surat, $ket_pengajuan_sppd, $kode_pegawai, $nip_kasi);
        $result = $stmt3->execute();
        $stmt3->close();
 
        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM pengajuan_surat_perjalanan_dinas WHERE kode_pegawai = ?");
            $stmt->bind_param("s", $kode_pegawai);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }

    public function simpanPengajuanCuti($tgl_pengajuan_cuti, $alasan_pengajuan_cuti, $tgl_mulai_cuti, $tgl_selesai_cuti, $kode_pegawai) {
       
        // Perintah untuk mengambil kode pengajuan surat
        $stmt = $this->conn->prepare("SELECT kode_pengajuan_cuti FROM pengajuan_cuti ORDER BY kode_pengajuan_cuti DESC");
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        // Perintah untuk melanjutkan kode pengajuan surat sesuai angka yang sudah ada
        $split = explode('-', $user['kode_pengajuan_cuti']);
        $number = str_pad($split[1]+1,3,0, STR_PAD_LEFT);
        $kode_pengajuan_cuti = "PC-".$number;
 
        // Perintah untuk mengambil nip_kasi sesuai dengan kode_pegawai
        // $stmt2 = $this->conn->prepare("SELECT nip_kasi FROM pegawai_honorer WHERE kode_pegawai = ?");
        // $stmt2->bind_param("s", $kode_pegawai);
        // $stmt2->execute();
        // $nip_kasi = $stmt2->get_result()->fetch_assoc();
        // $stmt2->close();
 
        $nip_kasi = "19820614 200901 1 005";
        $status_pengajuan_cuti = "Menunggu";
        $ket_pengajuan_cuti = "Menunggu persetujuan dari kepala seksi";
                
        // Perintah untuk menyimpan data pengajuan surat
        $stmt3 = $this->conn->prepare("INSERT INTO pengajuan_cuti(kode_pengajuan_cuti, tgl_pengajuan_cuti, alasan_pengajuan_cuti, tgl_mulai_cuti, tgl_selesai_cuti, status_pengajuan_cuti, ket_pengajuan_cuti, kode_pegawai, nip_kasi) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("sssssssss", $kode_pengajuan_cuti, $tgl_pengajuan_cuti, $alasan_pengajuan_cuti, $tgl_mulai_cuti, $tgl_selesai_cuti, $status_pengajuan_cuti, $ket_pengajuan_cuti, $kode_pegawai, $nip_kasi);
        $result = $stmt3->execute();
        $stmt3->close();
 
        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM pengajuan_cuti WHERE kode_pegawai = ?");
            $stmt->bind_param("s", $kode_pegawai);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }
}
 
?>