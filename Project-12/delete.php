<?php
require 'db.php';

### mengambil ID dari parameter URL ###
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Jika ID tidak valid, kembali ke index
if ($id <= 0) { 
    header('Location: index.php'); 
    exit; 
}

### Ambil nama file foto sebelum data dihapus ##3
$sql = "SELECT foto FROM karyawan WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $foto);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

### Hapus
$sqlHapus = "DELETE FROM karyawan WHERE id = ?";
$stmt = mysqli_prepare($conn, $sqlHapus);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    ### Jika ada file foto, hapus juga dari folder uploads ###
    if (!empty($foto)) {
        $path = __DIR__ . '/uploads/' . $foto;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}

mysqli_stmt_close($stmt);

### back
header('Location: index.php');
exit;
