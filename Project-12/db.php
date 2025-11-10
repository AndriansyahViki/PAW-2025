<?php
$hostname ='localhost';
$user='root';
$pass='';
$db='karyawan';

$conn = mysqli_connect($hostname,$user,$pass,$db) ;

if (!$conn) {
    die('koneksi gagal '. mysqli_connect_error());
}


?>