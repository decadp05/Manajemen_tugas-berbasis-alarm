<?php
// Koneksi ke database (MySQL)
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
class Database {
    private $host = "localhost";
    private $db = "uas_kbp";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}

// Model Tugas
class Tugas {
    private $db;
    private $data = [];

    public function __construct($db) {
        $this->db = $db->conn;
    }
    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    public function get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }   


    public function add($Nama, $Tugas, $expired_at) {
        $stmt = $this->db->prepare("INSERT INTO tugas (Nama, Tugas, expired_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $Nama, $Tugas, $expired_at);
        return $stmt->execute();
    }
    public function getAll() {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("SELECT * FROM tugas WHERE expired_at > ? ORDER BY id DESC");
        $stmt->bind_param("s", $now);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tugas WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

// Inisialisasi
$db = new Database();
$tugas = new Tugas($db);


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $tugas->delete($id);
    header("Location: manajemen_tugas.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Tugas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Manajemen Tugas</h2>
    <a href="logout.php" class="logout-link">Logout</a>
    <form method="post" action="tambah.php">
        <input type="text" name="Nama" placeholder="Nama" required>
        <input type="text" name="Tugas" placeholder="Tugas" required>
        <input type="datetime-local" name="expired_at" placeholder="Tanggal &Jam Expired" required>
        <button type="submit" name="tambah">Tambah</button>
    </form>
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tugas</th>
            <th>Kadaluwarsa</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $data = $tugas->getAll();
        if ($data && $data->num_rows > 0) {
            while ($row = $data->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['Nama']}</td>
                        <td>{$row['Tugas']}</td>
                        <td>{$row['expired_at']}</td>
                        <td><a href='?delete={$row['id']}'>Hapus</a></td>
                        <td><a href='edit.php?id={$row['id']}'>Edit</a></td>
                      </tr>";
                      $allTugas[] = $row; // Simpan data tugas untuk alarm
                $no++;
            }
        } else {
            echo "<tr><td colspan='6'>Belum ada tugas.</td></tr>";
        }
        ?>
        <script src="pengingat.js"></script>
    <script>
    const tugasData = <?php
        echo json_encode($allTugas);
    ?>;
    cekAlarmTugas(tugasData);
    </script>
    </table>
</body>
</html>