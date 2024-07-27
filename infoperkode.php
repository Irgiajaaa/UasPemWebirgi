<?php
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Peserta</title>
    <link rel="stylesheet" type="text/css" href="info.css">
</head>
<body>
    <div class="container">
        <h2>Informasi Per Kode</h2>
        <?php
        // Handling form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST['id_peserta'])) {
                $id_peserta = $_POST['id_peserta'];

                // Prepare and execute SQL statement
                $stmt = $conn->prepare("SELECT peserta.id_peserta, peserta.nama_peserta, peserta.email, peserta.no_telp, peserta.tanggal_lahir, peserta.tanggal_daftar, kursus.nama_kursus
                                        FROM peserta
                                        INNER JOIN kursus ON peserta.kursus_id = kursus.id_kursus
                                        WHERE peserta.id_peserta = ?");
                
                // Check if statement preparation succeeded
                if ($stmt === false) {
                    die('Error: ' . htmlspecialchars($conn->error));
                }

                $stmt->bind_param("i", $id_peserta);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<h2>Informasi Peserta untuk ID Peserta: $id_peserta</h3>";
                    echo "<table>
                            <tr>
                                <th>ID Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Tanggal Lahir</th>
                                <th>Tanggal Daftar</th>
                                <th>Nama Kursus</th>
                            </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row['id_peserta']."</td>
                                <td>".$row['nama_peserta']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['no_telp']."</td>
                                <td>".$row['tanggal_lahir']."</td>
                                <td>".$row['tanggal_daftar']."</td>
                                <td>".$row['nama_kursus']."</td>
                            </tr>";
                    }

                    echo "</table>";
                    echo "<div class='back-link'><a href='dashboard.php'>Kembali</a></div>";
                } else {
                    echo "<p align='center'>Tidak ada informasi peserta untuk ID Peserta: $id_peserta</p>";
                }

                $stmt->close();
            } else {
                echo "<p align='center'>Masukkan ID Peserta terlebih dahulu.</p>";
            }
        } else {
            // Form to input id_peserta
            echo "<form method='post' align='center'>
                    <label for='id_peserta'>Masukkan ID Peserta:</label>
                    <input type='text' id='id_peserta' name='id_peserta' required>
                    <input type='submit' value='Tampilkan Informasi'>
                </form>";
        }
        ?>
    </div>
</body>
</html>
