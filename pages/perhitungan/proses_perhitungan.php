<?php
// Menampilkan seluruh data
function tampilData($query)
{
    global $koneksi;

    $dataAlter = mysqli_query($koneksi, $query);
    $row = [];
    while ($data = mysqli_fetch_assoc($dataAlter)) {
        $row[] = $data;
    }

    return $row;
}
$alternatif = tampilData("SELECT * FROM data_alternatif");
$kriteria = tampilData("SELECT * FROM data_kriteria");
$penilaian = tampilData("SELECT * FROM data_penilaian");

// Proses perhitungan
if (isset($_POST['hitung'])) {
    $sql1 = "SELECT COUNT(*) FROM data_penilaian";
    $dataPenilai = mysqli_query($koneksi, $sql1);
    $isiPenilai = mysqli_fetch_row($dataPenilai);

    $sql2 = "SELECT COUNT(*) FROM hasil_normalisasi";
    $dataNorm = mysqli_query($koneksi, $sql2);
    $isiNorm = mysqli_fetch_row($dataNorm);

    $sql3 = "SELECT COUNT(*) FROM hasil_preferensi";
    $dataPref = mysqli_query($koneksi, $sql3);
    $isiPref = mysqli_fetch_row($dataPref);

    if ($isiPenilai[0] == 0) {
        echo "<script>
                alert('Mohon Inputkan Data Terlebih Dahulu');
             </script>";
    } else if ($isiNorm[0] > 0 && $isiPref[0] > 0) {
        echo "<script>
                alert('Proses Hitung Telah Dilakukan');
             </script>";
    } else {
        // Fungsi menambil data dari DB    
        function Data($sql)
        {
            global $koneksi;

            $dataNilai = mysqli_query($koneksi, $sql);
            $baris = [];
            while ($hasil = mysqli_fetch_row($dataNilai)) {
                $baris[] = $hasil;
            }

            return $baris;
        }

        // Proses normalisasi
        $ambil = Data("SELECT * FROM data_penilaian");

        function normalisasi($dataPenilaian)
        {
            global $koneksi;
            $jumlahAlternatif = count($dataPenilaian);
            $jumlahKriteria = count($dataPenilaian[0]) - 2;

            $hasil = [];

            for ($i = 0; $i < $jumlahKriteria; $i++) {
                $kolom = array_column($dataPenilaian, $i + 2);
                $nilaiTerbesar = max($kolom);

                for ($j = 0; $j < $jumlahAlternatif; $j++) {
                    $hasil[$j][$i] = number_format($dataPenilaian[$j][$i + 2] / $nilaiTerbesar, 3);
                }
            }

            foreach ($hasil as $index => $row) {
                $query = "INSERT INTO hasil_normalisasi (C1, C2, C3, C4, C5) VALUES ('" . implode("', '", $row) . "')";
                mysqli_query($koneksi, $query);
            }

            return mysqli_affected_rows($koneksi);
        }

        normalisasi($ambil);

        // Proses preferensi
        $bobot = Data("SELECT * FROM data_kriteria");

        // Data / nilai bobot
        $bobotC1 = $bobot[0][1];
        $bobotC2 = $bobot[0][2];
        $bobotC3 = $bobot[0][3];
        $bobotC4 = $bobot[0][4];
        $bobotC5 = $bobot[0][5];

        function preferensi($hasilNorm, $bobotC1, $bobotC2, $bobotC3, $bobotC4, $bobotC5)
        {
            global $koneksi;
            $alternatif = tampilData("SELECT * FROM data_alternatif");
            foreach ($hasilNorm as $index => $row) {
                // Validasi data alternatif
                if (!isset($alternatif[$index])) continue;

                $ID_Alternatif = $alternatif[$index]['ID_Alternatif'];
                $Nama_Mahasiswa = $alternatif[$index]['Nama_Mahasiswa'];

                // Validasi jumlah elemen pada $row
                $pre1 = isset($row[1]) ? number_format($bobotC1 * $row[1], 3) : 0;
                $pre2 = isset($row[2]) ? number_format($bobotC2 * $row[2], 3) : 0;
                $pre3 = isset($row[3]) ? number_format($bobotC3 * $row[3], 3) : 0;
                $pre4 = isset($row[4]) ? number_format($bobotC4 * $row[4], 3) : 0;
                $pre5 = isset($row[5]) ? number_format($bobotC5 * $row[5], 3) : 0;

                $hasilTotal = number_format($pre1 + $pre2 + $pre3 + $pre4 + $pre5, 3);

                // Pastikan ID_Alternatif valid dan ada di tabel data_alternatif
                if ($ID_Alternatif != null && $ID_Alternatif != '') {
                    $query = "INSERT INTO hasil_preferensi (C1, C2, C3, C4, C5, Total, ID_Alternatif) VALUES ('$pre1', '$pre2', '$pre3', '$pre4', '$pre5', '$hasilTotal', '$ID_Alternatif')";
                    mysqli_query($koneksi, $query);
                }
            }
            return mysqli_affected_rows($koneksi);
        }

        $hasilNorm = Data("SELECT * FROM hasil_normalisasi");

        preferensi($hasilNorm, $bobotC1, $bobotC2, $bobotC3, $bobotC4, $bobotC5);

        echo "<script>
                  document.location.href = 'index.php?page=hasil_normalisasi';
             </script>";
    }
}

$hasilNormalisasi = tampilData("SELECT * FROM hasil_normalisasi");

$sqlHasilpref = "SELECT * FROM hasil_preferensi ORDER BY Total DESC";
$hasilPreferensi = tampilData($sqlHasilpref);

if (isset($_POST['reset'])) {
    $sqlNorm = "SELECT COUNT(*) FROM hasil_normalisasi";
    $dataNorm = mysqli_query($koneksi, $sqlNorm);
    $isiNorm = mysqli_fetch_row($dataNorm);

    $sqlPref = "SELECT COUNT(*) FROM hasil_preferensi";
    $dataPref = mysqli_query($koneksi, $sqlPref);
    $isiPref = mysqli_fetch_row($dataPref);

    if ($isiNorm[0] == 0 && $isiPref[0] == 0) {
        echo "<script>
                alert('Tidak Bisa Mereset, Karena Tidak Ada Data Satupun');
             </script>";
    } else {
        $resetNorm = "TRUNCATE TABLE hasil_normalisasi";
        $resetPref = "TRUNCATE TABLE hasil_preferensi";

        mysqli_query($koneksi, $resetNorm);
        mysqli_query($koneksi, $resetPref);

        echo "<script>
                alert('Reset Berhasil');
                document.location.href = 'index.php';
             </script>";
    }
}
