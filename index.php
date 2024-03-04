<?php

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Koneksi Gagal: " . $this->conn->connect_error);
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function insertData($nama, $email, $whatsapp, $alamat) {
        $sql = "INSERT INTO datauser (nama, email, whatsapp, alamat) VALUES ('$nama', '$email', '$whatsapp', '$alamat')";
    
        if ($this->conn->query($sql) === TRUE) {
            echo '<script type="text/javascript">alert("Data berhasil disimpan");</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function getData() {
        $sql = "SELECT * FROM datauser";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>WhatsApp</th>
                        <th>Alamat</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nama']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['whatsapp']}</td>
                        <td>{$row['alamat']}</td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "Tidak ada data";
        }
    }
}

$database = new Database("localhost", "root", "admin123", "dbtugas");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $whatsapp = $_POST["whatsapp"];
    $alamat = $_POST["alamat"];

    $database->insertData($nama, $email, $whatsapp, $alamat);
}

?>

<script>
    <?php
    if (isset($errorMessage)) {
        echo "showAlert('$errorMessage');";
    }
    ?>
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
</head>
<body>
    <h2>Form Input Data</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="whatsapp">WhatsApp:</label>
        <input type="text" name="whatsapp" pattern="[0-9]{10,12}" title="Harus berisi 10-12 digit angka" required><br>

        <label for="alamat">Alamat:</label>
        <textarea name="alamat" required></textarea><br>

        <input type="submit" value="Simpan">
    </form>

    <hr>

    <h2>Data User</h2>

    <?php
    $database->getData();
    $database->close();
    ?>

</body>
</html>