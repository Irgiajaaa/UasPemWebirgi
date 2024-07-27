<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Data Pertanggal</title>
    <link rel="stylesheet" type="text/css" href="info.css">
</head>
<body>
    <h2 align="center">Data Pertanggal</h2>

    <?php
    // Koneksi ke database
    include 'koneksi.php';

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['tanggal_daftar'])) {
            $tanggal_daftar = $_POST['tanggal_daftar'];

            // Prepare and execute SQL statement
            $stmt = $conn->prepare("SELECT peserta.id_peserta, peserta.nama_peserta, peserta.email, peserta.no_telp, peserta.tanggal_lahir, peserta.tanggal_daftar, kursus.nama_kursus
                                    FROM peserta
                                    INNER JOIN kursus ON peserta.kursus_id = kursus.id_kursus
                                    WHERE peserta.tanggal_daftar = ?");
            
            // Check if statement preparation succeeded
            if ($stmt === false) {
                die('Error: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("s", $tanggal_daftar);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Data Peserta untuk Tanggal Daftar: $tanggal_daftar</h2>";
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
                echo "<div class='back-link'><a href='dashboard.php'>Back</a></div>";
            } else {
                echo "<p align='center'>Tidak ada data kursus untuk Tanggal Daftar: $tanggal_daftar</p>";
            }

            $stmt->close();
        } else {
            echo "<p align='center'>Masukkan Tanggal Daftar terlebih dahulu.</p>";
        }
    } else {
        // Form to input tanggal_daftar
        echo "<form method='post' align='center'>
                <label for='tanggal_daftar'>Masukkan Tanggal Daftar:</label>
                <input type='date' id='tanggal_daftar' name='tanggal_daftar' required>
                <input type='submit' value='Tampilkan Data'>
            </form>";
    }

    $conn->close();
    ?>
</body>
</html>
