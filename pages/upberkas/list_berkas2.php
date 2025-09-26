<?php
require 'config/koneksi_db.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['masuk'])) {
    header("Location: login.php");
    exit;
}

// Ambil NPM mahasiswa dari session
$npm_login = trim($_SESSION['npm']); 

// Cek apakah mahasiswa sudah pernah upload
$cek = mysqli_query($koneksi, "SELECT * FROM tbl_berkas WHERE TRIM(npm)='$npm_login'");
$jumlah_berkas = mysqli_num_rows($cek);

// Ambil data berkas mahasiswa login
$result = mysqli_query($koneksi, "SELECT * FROM tbl_berkas WHERE TRIM(npm)='$npm_login' ORDER BY id DESC");
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">List Berkas Saya</h3>
        <?php if($jumlah_berkas == 0): ?>
        <a href="index.php?page=upload_berkas" class="btn btn-primary btn-sm float-right">+ Upload Baru</a>
        <?php else: ?>
        <button class="btn btn-secondary btn-sm float-right"
            onclick="alert('Anda sudah pernah meng-upload berkas, tidak bisa tambah lagi!')">+ Upload Baru</button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NPM</th>
                    <th>Program Studi</th>
                    <th>Jumlah SKS</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if($jumlah_berkas > 0): $no=1; ?>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_mahasiswa']); ?></td>
                    <td><?= htmlspecialchars($row['npm']); ?></td>
                    <td><?= htmlspecialchars($row['program_studi']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_sks']); ?></td>
                    <td>
                        <a href="<?= $row['file_aktif_organisasi']; ?>" target="_blank">Aktif Organisasi</a><br>
                        <a href="<?= $row['file_tidak_beasiswa']; ?>" target="_blank">Tidak Beasiswa</a><br>
                        <a href="<?= $row['file_keluarga_tidak_mampu']; ?>" target="_blank">Keluarga Tidak Mampu</a><br>
                        <a href="<?= $row['file_ipk']; ?>" target="_blank">IPK</a>
                    </td>
                    <td>
                        <span
                            class="badge 
                                <?= $row['status']=='pending'?'bg-warning':($row['status']=='diterima'?'bg-success':'bg-danger'); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=edit_berkas&id=<?= $row['id']; ?>"
                            class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                <?php } ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada berkas yang di-upload.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>