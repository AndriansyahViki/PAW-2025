<?php
require 'db.php';

$pesan = '';
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $email = trim($_POST['email']);
    $gaji = isset($_POST['gaji']) ? (float)$_POST['gaji'] : 0;

    if ($nama=='' || $jabatan=='' || $email=='') {
        $pesan = 'Mohon isi semua field yang wajib.';
    } else {
        $fotoNama = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = $_FILES['foto'];
            if ($up['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($up['name'], PATHINFO_EXTENSION);
                $izin = ['jpg','jpeg','png','gif'];
                if (!in_array(strtolower($ext), $izin)) {
                    $pesan = 'Hanya boleh mengunggah file gambar (jpg, jpeg, png, gif).';
                } else {
                    $fotoNama = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

                    // Pastikan folder uploads ada
                    $folderUpload = __DIR__ . '/uploads';
                    if (!is_dir($folderUpload)) {
                        mkdir($folderUpload, 0777, true);
                    }

                    $tujuan = $folderUpload . '/' . $fotoNama;
                    if (!move_uploaded_file($up['tmp_name'], $tujuan)) {
                        $pesan = 'Gagal menyimpan file yang diunggah.';
                    }
                }
            } else {
                $pesan = 'Terjadi kesalahan saat upload.';
            }
        }

        if ($pesan == '') {
            $sql = "INSERT INTO karyawan (nama, jabatan, email, gaji, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sssds', $nama, $jabatan, $email, $gaji, $fotoNama);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php');
                exit;
            } else {
                $pesan = 'Kesalahan Database: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Karyawan</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="styleadd.css">
</head>
<body>
  <div class="container">
    <h2>Tambah Karyawan</h2>

    <?php if ($pesan): ?>
      <div class="alert"><?php echo htmlspecialchars($pesan); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-card">
      <label>Nama*:<br><input type="text" name="nama" required></label><br>
      <label>Jabatan*:<br><input type="text" name="jabatan" required></label><br>
      <label>Email*:<br><input type="email" name="email" required></label><br>
      <label>Gaji:<br><input type="number" step="0.01" name="gaji"></label><br>
      <label>Foto:<br><input type="file" name="foto" accept="image/*"></label><br>

      <button type="submit" name="submit" class="btn-add">💾 Simpan</button>
    </form>

    <p><a href="index.php" class="back-link">⬅ Kembali ke daftar</a></p>
  </div>
</body>
</html>
