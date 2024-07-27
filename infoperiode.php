<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Periode</title>
    <link rel="stylesheet" type="text/css" href="info.css">
</head>
<body>
    <h2 align="center">Data Periode</h2>

    <?php
    // Koneksi ke database
    include 'koneksi.php';

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['tgl_awal']) && !empty($_POST['tgl_akhir'])) {
            $tgl_awal = $_POST['tgl_awal'];
            $tgl_akhir = $_POST['tgl_akhir'];

            // Prepare and execute SQL statement
            $stmt = $conn->prepare("SELECT peserta.id_peserta, peserta.nama_peserta, peserta.email, peserta.no_telp, peserta.tanggal_lahir, peserta.tanggal_daftar, kursus.nama_kursus
                                    FROM peserta
                                    INNER JOIN kursus ON peserta.kursus_id = kursus.id_kursus 
                                    WHERE peserta.tanggal_daftar BETWEEN ? AND ?");
            
            // Check if statement preparation succeeded
            if ($stmt === false) {
                die('Error: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Data Kursus Periode: $tgl_awal sampai $tgl_akhir</h2>";
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
                echo "<p align='center'>Tidak ada data kursus untuk periode: $tgl_awal sampai $tgl_akhir</p>";
            }

            $stmt->close();
        } else {
            echo "<p align='center'>Masukkan Tanggal Awal dan Tanggal Akhir terlebih dahulu.</p>";
        }
    } else {
        // Form to input tanggal awal dan akhir
        echo "<form method='post' align='center'>
                <label for='tgl_awal'>Masukkan Tanggal Awal:</label>
                <input type='date' id='tgl_awal' name='tgl_awal' required>
                <br>
                <label for='tgl_akhir'>Masukkan Tanggal Akhir:</label>
                <input type='date' id='tgl_akhir' name='tgl_akhir' required>
                <br>
                <input type='submit' value='Tampilkan Data'>
            </form>";
    }

    $conn->close();
    ?>
</body>
</html>
