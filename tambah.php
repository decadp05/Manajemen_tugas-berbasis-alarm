<?php
require_once 'manajemen_tugas.php';

if(isset($_POST['Nama']) && isset($_POST['Tugas']) && isset($_POST['expired_at'])) {
    $db = new Database();
    $tugas = new Tugas($db);
    
    $Nama = $_POST['Nama'];
    $Tugas = $_POST['Tugas'];
    $expired_at = $_POST['expired_at'];

    $tugas->add($Nama, $Tugas, $expired_at);
    header("Location: manajemen_tugas.php");
    exit;
}
?>