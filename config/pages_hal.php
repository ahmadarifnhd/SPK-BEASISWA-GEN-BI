<?php
require 'koneksi_db.php';
error_reporting(error_reporting() & ~E_NOTICE);

// Ambil nilai `page` dengan pengecekan
$page = isset($_GET['page']) ? $_GET['page'] : '';

// halaman alternatif
if ($page == 'data_alternatif') {
	require 'pages/alternatif/alternatif.php';
}
// hapus data alter
else if ($page == 'delete_alter') {
	require 'pages/alternatif/proses_hapus_alter.php';
}
// edit data alter
else if ($page == 'edit_alter') {
	require 'pages/alternatif/proses_update_alter.php';
}
// ====================================================

// halaman kriteria
else if ($page == 'data_kriteria') {
	require 'pages/kriteria/kriteria.php';
}
// hapus data bobot kriteria
else if ($page == 'delete_kriteria') {
	require 'pages/kriteria/proses_hapus_kriteria.php';
}
// ====================================================

// halaman penilaian
else if ($page == 'data_penilaian') {
	require 'pages/penilaian/penilaian.php';
}
// hapus data penilaian
else if ($page == 'delete_penilaian') {
	require 'pages/penilaian/proses_hapus_penilaian.php';
}
// edit data penilaian
else if ($page == 'edit_penilaian') {
	require 'pages/penilaian/proses_update_penilaian.php';
}
// ====================================================

// halaman perhitungan
else if ($page == 'perhitungan') {
	require 'pages/perhitungan/perhitungan.php';
}
// ====================================================

// halaman hasil
else if ($page == 'hasil_normalisasi') {
	require 'pages/hasil/hasil_normalisasi.php';
} else if ($page == 'hasil_preferensi') {
	require 'pages/hasil/hasil_preferensi.php';
}
// ====================================================

// halaman user
else if ($page == 'list_user') {
	require 'pages/user/list_user.php';
} else if ($page == 'add_user') {
	require 'pages/user/add_user.php';
} else if ($page == 'proses_add_user') {
	require 'pages/user/proses_add_user.php';
} else if ($page == 'edit_user') {
	require 'pages/user/edit_user.php';
} else if ($page == 'proses_update_user') {
	require 'pages/user/proses_update_user.php';
} else if ($page == 'delete_user') {
	require 'pages/user/proses_hapus_user.php';
} else if ($page == 'edit_profile') {
	require 'pages/user/edit_profile.php';
}
// ====================================================
// halaman upberkas
else if ($page == 'upload_berkas') {
	require 'pages/upberkas/upload_berkas.php';
} else if ($page == 'list_berkas') {
	require 'pages/upberkas/list_berkas.php';
} else if ($page == 'list_berkas2') {
	require 'pages/upberkas/list_berkas2.php';
} else if ($page == 'edit_berkas') {
	require 'pages/upberkas/edit_berkas.php';
} else if ($page == 'hapus_berkas') {
	require 'pages/upberkas/hapus_berkas.php';
}


// ====================================================
// halaman tentang
else if ($page == 'tentang') {
	require 'tentang.php';
}
// ====================================================

// halaman cara penggunaan
else if ($page == 'petunjuk') {
	require 'cara_penggunaan.php';
}
// ====================================================

// halaman beranda (default)
else {
	require 'beranda.php';
}
