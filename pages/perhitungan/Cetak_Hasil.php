<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require '../../config/koneksi_db.php';

function printData($query)
{
	global $koneksi;
	$dataAlter = mysqli_query($koneksi, $query);
	if (!$dataAlter) {
		die("Query error: " . mysqli_error($koneksi));
	}
	$row = [];
	while ($data = mysqli_fetch_assoc($dataAlter)) {
		$row[] = $data;
	}
	return $row;
}

// Ambil data alternatif lengkap
$alternatif = printData("SELECT ID_Alternatif, Nama_Mahasiswa FROM data_alternatif");

// Ambil hasil normalisasi
$printNorm = printData("SELECT * FROM hasil_normalisasi");

// Sinkronisasi Nama Mahasiswa pada hasil normalisasi
foreach ($printNorm as $index => $data) {
	// Jika ID_Alternatif NULL, isi Nama Mahasiswa berdasarkan urutan
	if (empty($data['ID_Alternatif']) || $data['ID_Alternatif'] === null) {
		$printNorm[$index]['Nama_Mahasiswa'] = isset($alternatif[$index]['Nama_Mahasiswa']) ? $alternatif[$index]['Nama_Mahasiswa'] : '-';
	} else {
		$idAlt = $data['ID_Alternatif'];
		$namaMahasiswa = '-';
		foreach ($alternatif as $alt) {
			if ($alt['ID_Alternatif'] == $idAlt) {
				$namaMahasiswa = $alt['Nama_Mahasiswa'];
				break;
			}
		}
		$printNorm[$index]['Nama_Mahasiswa'] = $namaMahasiswa;
	}
}

// Ambil hasil preferensi
$printPrefQuery = "SELECT * FROM hasil_preferensi ORDER BY Total DESC";
$hasilPref = printData($printPrefQuery);

// Sinkronisasi Nama Mahasiswa pada hasil preferensi
foreach ($hasilPref as $index => $data) {
	$idAlt = $data['ID_Alternatif'] ?? null;
	$namaMahasiswa = '-';
	foreach ($alternatif as $alt) {
		if ($alt['ID_Alternatif'] == $idAlt) {
			$namaMahasiswa = $alt['Nama_Mahasiswa'];
			break;
		}
	}
	$hasilPref[$index]['Nama_Mahasiswa'] = $namaMahasiswa;
}

$mpdf = new \Mpdf\Mpdf();
$headerTitle = '<div style="text-align: center;">
	<img src="../../img/genbi.png" style="height:120px; margin-bottom:10px;" />
	<h1 style="font-size: 22px; text-align: center;">
	<i>Sistem Pendukung Keputusan Beasiswa GenBI <br> (SAW Method)</i></h1>
	<hr style="color: black; border: none; margin-top: -6px;">
</div>';

$waktu = '<p style="margin-top: -3px;"><i>' . date("D, j F Y") . '</i></p>';

$header1 = '<div>
    		      <h2 style="font-size: 16px;">Hasil Normalisasi</h2>
    		   </div>';

$tabel1 = '<table border="1" cellspacing="0" cellpadding="5" style="width: 100%; text-align: center; font-size: 13px; 
   	margin-top: -5px;">
              <thead>
                <tr class="text-center">
                  <th>Nama Mahasiswa</th>
                  <th>C1</th>
                  <th>C2</th>
                  <th>C3</th>
                  <th>C4</th>
				  <th>C5</th>
                </tr>
              </thead>
              <tbody>';
$no1 = 1;
foreach ($printNorm as $data1) {
	$tabel1 .= '<tr>
			                    <td>' . $data1['Nama_Mahasiswa'] . '</td>
			                    <td>' . $data1['C1'] . '</td>
			                    <td>' . $data1['C2'] . '</td>
			                    <td>' . $data1['C3'] . '</td>
			                    <td>' . $data1['C4'] . '</td>
								 <td>' . $data1['C5'] . '</td>
			                 </tr>';
	$no1++;
}
$tabel1 .= '</tbody>
			            	</table>';

$header2 = '<div>
	              <h2 style="font-size: 16px; margin-top: 26px;">Hasil Preferensi</h2>
	           </div>';

$tabel2 = '<table border="1" cellspacing="0" cellpadding="5" style="width: 100%; text-align: center; font-size: 13px;
	margin-top: -5px;">
              <thead>
                <tr class="text-center">
                  <th>Urutan</th>
                  <th>Nama Mahasiswa</th>
                  <th>C1</th>
                  <th>C2</th>
                  <th>C3</th>
                  <th>C4</th>
				  <th>C5</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody class="text-center"> ';
$no2 = 1;
foreach ($hasilPref as $data2) {
	$tabel2 .= '<tr>
			                    <td>' . $no2 . '</td>
			                    <td>' . $data2['Nama_Mahasiswa'] . '</td>
			                    <td>' . $data2['C1'] . '</td>
			                    <td>' . $data2['C2'] . '</td>
			                    <td>' . $data2['C3'] . '</td>
			                    <td>' . $data2['C4'] . '</td>
								<td>' . $data2['C5'] . '</td>
			                    <td>' . $data2['Total'] . '</td>
			                  </tr>';
	$no2++;
}
$tabel2 .= '</tbody>
            			 	</table>';


function hasilTertinggi($query)
{
	global $koneksi;
	$dataAlter = mysqli_query($koneksi, $query);
	if (!$dataAlter) {
		die("Query error: " . mysqli_error($koneksi));
	}
	$row = [];
	while ($data = mysqli_fetch_row($dataAlter)) {
		$row[] = $data;
	}
	return $row;
}

$nilai = hasilTertinggi("SELECT MAX(Total) AS Total FROM hasil_preferensi");
$hasil = $nilai[0][0];

// Nama Mahasiswa dengan nilai tertinggi (peringkat 1)
$namaTertinggi = $hasilPref[0]['Nama_Mahasiswa'] ?? '-';

$kesimpulan = "<p style=\"margin-top: 23px; line-height: 22px; text-align: justify;\"><i>Berdasarkan jumlah alternatif, nilai bobot 
	masing-masing kriteria serta penilaian yang ada, hasil dari pemilihan Mahasiswa yang layak mendapatkan Beasiswa GENBI dengan metode SAW ini untuk nilai tertinggi pada 
	hasil preferensi adalah <b>{$hasil}</b>. Jadi kesimpulannya  Mahasiswa yang layak mendapatkan Beasiswa GENBI ialah <b>{$namaTertinggi}</b>.</i></p>";

$mpdf->WriteHTML($headerTitle);
$mpdf->WriteHTML($waktu);
$mpdf->WriteHTML($header1);
$mpdf->WriteHTML($tabel1);
$mpdf->WriteHTML($header2);
$mpdf->WriteHTML($tabel2);
$mpdf->WriteHTML($kesimpulan);

$mpdf->Output('SPK_Hasil_Keputusan.pdf', \Mpdf\Output\Destination::INLINE);
