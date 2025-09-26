<?php
require 'config/koneksi_db.php';
session_start();

// Pastikan sudah login
if (!isset($_SESSION['masuk'])) {
    header('Location: beasiswa.php');
    exit;
}

// Ambil npm dari session
$npm_session = $_SESSION['npm'];
$role_session = $_SESSION['role'];

// Cek apakah user sudah upload berkas
$cek = mysqli_query($koneksi, "SELECT * FROM tbl_berkas WHERE npm='$npm_session'");
if ($role_session == 'user' && mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Anda sudah melakukan upload berkas, tidak bisa menambah lagi!'); 
          window.location.href='index.php?page=list_berkas';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $npm = $_POST['npm'];
    $program_studi = $_POST['program_studi'];
    $jumlah_sks = $_POST['jumlah_sks'];
    $status = "Pending";

    // Folder tujuan upload
    $uploadDir = "uploads/berkas/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Fungsi upload file
    function uploadFile($fieldName, $uploadDir) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] == 0) {
            $fileName = time() . "_" . basename($_FILES[$fieldName]['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetFile)) {
                return $targetFile;
            }
        }
        return null;
    }

    // Upload semua file
    $file_organisasi = uploadFile('file_aktif_organisasi', $uploadDir);
    $file_beasiswa   = uploadFile('file_tidak_beasiswa', $uploadDir);
    $file_keluarga   = uploadFile('file_keluarga_tidak_mampu', $uploadDir);
    $file_ipk        = uploadFile('file_ipk', $uploadDir);

    // Simpan ke database
    $query = "INSERT INTO tbl_berkas 
        (nama_mahasiswa, npm, program_studi, jumlah_sks, 
        file_aktif_organisasi, file_tidak_beasiswa, file_keluarga_tidak_mampu, file_ipk, status)
        VALUES 
        ('$nama_mahasiswa', '$npm', '$program_studi', '$jumlah_sks', 
        '$file_organisasi', '$file_beasiswa', '$file_keluarga', '$file_ipk', '$status')";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect sesuai role
        if ($role_session == 'user') {
            echo "<script>alert('Berkas berhasil diupload!'); window.location.href='index.php?page=list_berkas2';</script>";
        } else {
            echo "<script>alert('Berkas berhasil diupload!'); window.location.href='index.php?page=list_berkas';</script>";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Upload Berkas</h3>
    </div>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group">
                <label>Nama Mahasiswa</label>
                <input type="text" name="nama_mahasiswa" class="form-control" required>
            </div>
            <div class="form-group">
                <label>NPM</label>
                <input type="text" name="npm" value="<?= $npm_session; ?>" class="form-control" readonly required>
            </div>
            <div class="form-group">
                <label>Program Studi</label>
                <input type="text" name="program_studi" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah SKS (Maks 40)</label>
                <input type="number" name="jumlah_sks" max="40" class="form-control" required>
            </div>
            <div class="form-group">
                <label>File Aktif Organisasi</label>
                <input type="file" name="file_aktif_organisasi" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="form-group">
                <label>File Tidak Menerima Beasiswa</label>
                <input type="file" name="file_tidak_beasiswa" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="form-group">
                <label>File Keluarga Tidak Mampu</label>
                <input type="file" name="file_keluarga_tidak_mampu" class="form-control" accept=".pdf,.doc,.docx"
                    required>
            </div>
            <div class="form-group">
                <label>File IPK</label>
                <input type="file" name="file_ipk" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="index.php?page=list_berkas" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>