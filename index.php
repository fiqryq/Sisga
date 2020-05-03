<?php 
require __DIR__ . '/vendor/autoload.php';
require 'libs/NotORM.php'; 

use \Slim\App;

$app = new App();

$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = '';
$dbname = 'sisga';
$dbmethod = 'mysql:dbname=';

$dsn = $dbmethod.$dbname;
$pdo = new PDO($dsn, $dbuser, $dbpass);
$db  = new NotORM($pdo);

$app-> get('/', function(){
    echo "API Sisga";
});

$app ->get('/semuapegawai', function() use($app, $db){
	$pegawai["error"] = false;
	$pegawai["message"] = "Berhasil mendapatkan data pegawai";
    foreach($db->pegawai_honorer() as $data){
        $pegawai['semuapegawai'][] = array(
            'kode_pegawai' => $data['kode_pegawai'],
            'nama_pegawai' => $data['nama_pegawai'],
            'jabatan_pegawai' => $data['jabatan_pegawai'],
            'email_pegawai' => $data['email_pegawai'],
            'telp_pegawai' => $data['telp_pegawai'],
            'no_rekening_pegawai' => $data['no_rekening_pegawai'],
            'kota_pegawai' => $data['kota_pegawai'],
            'jalan_pegawai' => $data['jalan_pegawai'],
            'rt_rw_pegawai' => $data['rt_rw_pegawai'],
            'no_rumah_pegawai' => $data['no_rumah_pegawai'],
            'kode_pos_pegawai' => $data['kode_pos_pegawai'],
            'username_pegawai' => $data['username_pegawai'],
            'pass_pegawai' => $data['pass_pegawai']
            );
    }
    echo json_encode($pegawai);
});

$app ->get('/pegawai/{nama_pegawai}', function($request, $response, $args) use($app, $db){
    $pegawai = $db->pegawai_honorer()->where('nama_pegawai',$args['nama_pegawai']);
    $detail = $pegawai->fetch();

    if ($pegawai->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Nama pegawai belum tersedia di database";
        $responseJson["nama_pegawai"] = null;
        $responseJson["jabatan_pegawai"] = null;
        $responseJson["email_pegawai"] = null;
        $responseJson["telp_pegawai"] = null;
    } else {
        $responseJson["error"] = false;
        $responseJson["message"] = "Berhasil mengambil data";
        $responseJson["nama_pegawai"] = $detail['nama_pegawai'];
        $responseJson["jabatan_pegawai"] = $detail['jabatan_pegawai'];
        $responseJson["email_pegawai"] = $detail['email_pegawai'];
        $responseJson["telp_pegawai"] = $detail['telp_pegawai'];
    }

    echo json_encode($responseJson); 
});

$app ->get('/absen/{kode_pegawai}', function($request, $response, $args) use($app, $db){
    $absen = $db->absensi_pegawai()->where('kode_pegawai',$args['kode_pegawai']);
    $detail = $absen->fetch();

    if ($absen->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Pegawai dengan kode tersebut belum melakukan absen";
        $responseJson["tanggal_absen"] = null;
        $responseJson["jam_masuk"] = null;
        $responseJson["jam_keluar"] = null;
        $responseJson["kode_pegawai"] = null;
    } else {

        foreach($absen as $data){
            $dataabsen['absen'][] = array(
                'kode_absensi' => $data['kode_absensi'],
                'tanggal_absen' => $data['tanggal_absen'],
                'jam_masuk' => $data['jam_masuk'],
                'jam_keluar' => $data['jam_keluar'],
                'kode_pegawai' => $data['kode_pegawai']
                );
        }
        echo json_encode($dataabsen);
    }
}); 

$app ->get('/pengajuancuti/{kode_pegawai}', function($request, $response, $args) use($app, $db){
    $cuti = $db->pengajuan_cuti()->where('kode_pegawai',$args['kode_pegawai']);
    $detail = $cuti->fetch();

    if ($cuti->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Pegawai dengan kode tersebut belum mengajukan cuti";
        $responseJson["kode_pengajuan_cuti"] = null;
        $responseJson["tgl_pengajuan_cuti"] = null;
        $responseJson["alasan_pengajuan_cuti"] = null;
        $responseJson["tgl_mulai_cuti"] = null;
        $responseJson["tgl_selesai_cuti"] = null;
        $responseJson["status_pengajuan_cuti"] = null;
        $responseJson["ket_pengajuan_cuti"] = null;
        $responseJson["kode_pegawai"] = null;
        $responseJson["nip_kasi"] = null;
    } else {
        foreach($cuti as $data){
            $datacuti['pengajuancuti'][] = array(
                'kode_pengajuan_cuti' => $data['kode_pengajuan_cuti'],
                'tgl_pengajuan_cuti' => $data['tgl_pengajuan_cuti'],
                'alasan_pengajuan_cuti' => $data['alasan_pengajuan_cuti'],
                'tgl_mulai_cuti' => $data['tgl_mulai_cuti'],
                'tgl_selesai_cuti' => $data['tgl_selesai_cuti'],
                'status_pengajuan_cuti' => $data['status_pengajuan_cuti'],
                'ket_pengajuan_cuti' => $data['ket_pengajuan_cuti'],
                'kode_pegawai' => $data['kode_pegawai'],
                'nip_kasi' => $data['nip_kasi']
                );
        }
        echo json_encode($datacuti);
    }
});

$app ->get('/cuti/{kode_pegawai}', function($request, $response, $args) use($app, $db){
    $cuti = $db->cuti()->where('kode_pegawai',$args['kode_pegawai']);
    $detail = $cuti->fetch();

    if ($cuti->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Pegawai dengan kode tersebut tidak sedang cuti";
        $responseJson["kode_cuti"] = null;
        $responseJson["jenis_cuti"] = null;
        $responseJson["pemotongan_honor"] = null;
        $responseJson["tglmulaicuti"] = null;
        $responseJson["tglselesaicuti"] = null;
        $responseJson["statuscuti"] = null;
        $responseJson["kode_pegawai"] = null;
        $responseJson["nip_kasi"] = null;
        $responseJson["kode_pengajuan_cuti"] = null;
    } else {
        foreach($cuti as $data){
            $datacuti['cuti'][] = array(
                'kode_cuti' => $data['kode_cuti'],
                'jenis_cuti' => $data['jenis_cuti'],
                'pemotongan_honor' => $data['pemotongan_honor'],
                'tglmulaicuti' => $data['tglmulaicuti'],
                'tglselesaicuti' => $data['tglselesaicuti'],
                'statuscuti' => $data['statuscuti'],
                'kode_pegawai' => $data['kode_pegawai'],
                'nip_kasi' => $data['nip_kasi'],
                'kode_pengajuan_cuti' => $data['kode_pengajuan_cuti']
                );
        }
        echo json_encode($datacuti);
    }
});

$app ->get('/pengajuansurat/{kode_pegawai}', function($request, $response, $args) use($app, $db){
    $surat = $db->pengajuan_surat_perjalanan_dinas()->where('kode_pegawai',$args['kode_pegawai']);
    $detail = $surat->fetch();

    if ($surat->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Pegawai dengan kode tersebut belum mengajukan surat";
        $responseJson["kode_pengajuan_surat"] = null;
        $responseJson["tgl_pengajuan_surat"] = null;
        $responseJson["tujuan_pengajuan_surat"] = null;
        $responseJson["tgl_mulai_sppd"] = null;
        $responseJson["tgl_selesai_sppd"] = null;
        $responseJson["status_pengajuan_surat"] = null;
        $responseJson["ket_pengajuan_sppd"] = null;
        $responseJson["kode_pegawai"] = null;
        $responseJson["nip_kasi"] = null;
    } else {
        foreach($surat as $data){
            $datasurat['pengajuansurat'][] = array(
                'kode_pengajuan_surat' => $data['kode_pengajuan_surat'],
                'tgl_pengajuan_surat' => $data['tgl_pengajuan_surat'],
                'tujuan_pengajuan_surat' => $data['tujuan_pengajuan_surat'],
                'tgl_mulai_sppd' => $data['tgl_mulai_sppd'],
                'tgl_selesai_sppd' => $data['tgl_selesai_sppd'],
                'status_pengajuan_surat' => $data['status_pengajuan_surat'],
                'ket_pengajuan_sppd' => $data['ket_pengajuan_sppd'],
                'kode_pegawai' => $data['kode_pegawai'],
                'nip_kasi' => $data['nip_kasi']
                );
        }
        echo json_encode($datasurat);
    }
});

$app ->get('/surat/{kode_pegawai}', function($request, $response, $args) use($app, $db){
    $surat = $db->surat_perjalanan_dinas()->where('kode_pegawai',$args['kode_pegawai']);
    $detail = $surat->fetch();

    if ($surat->count() == 0) {
        $responseJson["error"] = true;
        $responseJson["message"] = "Pegawai dengan kode tersebut tidak terdaftar";
        $responseJson["no_surat"] = null;
        $responseJson["jenis_surat"] = null;
        $responseJson["uang_saku"] = null;
        $responseJson["tglmulaisppd"] = null;
        $responseJson["tglselesaisppd"] = null;
        $responseJson["statussurat"] = null;
        $responseJson["kode_pegawai"] = null;
        $responseJson["nip_kasi"] = null;
        $responseJson["kode_pengajuan_surat"] = null;
    } else {
        foreach($surat as $data){
            $datasurat['surat'][] = array(
                'no_surat' => $data['no_surat'],
                'jenis_surat' => $data['jenis_surat'],
                'uang_saku' => $data['uang_saku'],
                'tglmulaisppd' => $data['tglmulaisppd'],
                'tglselesaisppd' => $data['tglselesaisppd'],
                'statussurat' => $data['statussurat'],
                'kode_pegawai' => $data['kode_pegawai'],
                'nip_kasi' => $data['nip_kasi'],
                'kode_pengajuan_surat' => $data['kode_pengajuan_surat']
                );
        }
        echo json_encode($datasurat);
    }
});

//run App
$app->run();