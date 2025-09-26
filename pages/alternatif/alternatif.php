<?php 
  // menampilkan seluruh data 
  function tampilData($query) {
    global $koneksi;

    $dataAlter = mysqli_query($koneksi, $query);
    $row = [];
    while ($data = mysqli_fetch_assoc($dataAlter)) {
      $row [] = $data;
    }

    return $row;
  } 
  $hasil = tampilData("SELECT * FROM data_alternatif");

  // menyimpan data yang diinput
  if (isset($_POST['simpan'])) {
    global $koneksi;

    $sql = "SELECT COUNT(*) FROM data_alternatif";
    $data = mysqli_query($koneksi, $sql);
    $isi = mysqli_fetch_row($data);

    if ($isi[0] >= 10) {
      echo "<script>
              alert('Data Alternatif Hanya Bisa Input Maksimal 10 Data');
           </script>";
    } else {
      $namaAlternatif = htmlspecialchars($_POST['alternatif']);
      $JenisBeasiswa = htmlspecialchars($_POST['jenis']);

      $cekData = mysqli_query($koneksi, "SELECT * FROM data_alternatif WHERE Nama_Mahasiswa = '$namaAlternatif'");
      $result = mysqli_fetch_assoc($cekData);

      switch ($namaAlternatif) {
        case $result['Nama_Mahasiswa']:
          echo "<script>
                alert('Data Alternatif Sudah Ada');
                document.location.href = 'index.php?page=data_alternatif';
             </script>";
          break;
        default:
          // Custom ID generation
          $maxIdResult = mysqli_query($koneksi, "SELECT MAX(ID_Alternatif) as max_id FROM data_alternatif");
          $maxIdRow = mysqli_fetch_assoc($maxIdResult);
          $newId = ($maxIdRow['max_id'] !== null) ? $maxIdRow['max_id'] + 1 : 1;

          $stmt = mysqli_prepare($koneksi, "INSERT INTO data_alternatif (ID_Alternatif, Nama_Mahasiswa, Jenis_Beasiswa) VALUES (?, ?, ?)");
          mysqli_stmt_bind_param($stmt, "iss", $newId, $namaAlternatif, $JenisBeasiswa);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          echo "<script>
                  alert('Data Alternatif Berhasil Disimpan');
                  document.location.href = 'index.php?page=data_alternatif';
               </script>";
          break;
      }
    }  
  }
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Alternatif</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="index.php?beranda.php">Beranda</a></li>
          <li class="breadcrumb-item active">Alternatif</li>
        </ol>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Silahkan Masukan Data Alternatif</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form role="form" method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Alternatif</label>
                <input class="form-control select2" style="width: 100%;" placeholder="Alternatif" name="alternatif" required></input>
              </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis Beasiswa</label>
                <select class="form-control select2" style="width: 100%;" name="jenis" required>
                  <option value="" >-- Pilih Jenis Beasiswa --</option>
                  <option value="Beasiswa Genbi">Beasiswa GENBI</option>
                  <option value="Beasiswa Merdeka">Beasiswa Merdeka</option>
                </select>
              </div>
              <!-- /.form-group -->
            </div>
          </div>
          <div class="simpan d-flex justify-content-end ">
            <button type="submit" class="btn btn-primary" name="simpan"><i class="far fa-save"></i> Simpan</button>
          </div>
        </form>
        <!-- /.row -->
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <!-- /.container-fluid -->
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="text-center">
                    <th class="text-nowrap">Kode Alternatif</th>
                    <th class="text-nowrap">Nama Alternatif</th>
                    <th class="text-nowrap">Jenis Beasiswa</th>
                    <th class="text-nowrap">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    foreach ($hasil as $items) { 
                  ?>
                    <tr class="text-center">
                      <td class="text-nowrap"><?= 'A'. $no++; ?></td>
                      <td class="text-nowrap"><?= $items['Nama_Mahasiswa'];?></td>
                      <td class="text-nowrap"><?= $items['Jenis_Beasiswa'];?></td>
                      <td class="text-nowrap">
                        <center>
                          <a href="index.php?page=edit_alter&id=<?= $items['ID_Alternatif']; ?>">
                            <button type="button" class="btn btn-primary"><i class="far fa-edit"></i> Edit</button>
                          </a>
                          <a href="index.php?page=delete_alter&id=<?= $items['ID_Alternatif']; ?>">
                            <button type="button" class="btn btn-primary" onclick="return confirm('Hapus alternatif?');"><i class="far fa-trash-alt"></i> Delete</button>
                          </a>
                        </center>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
