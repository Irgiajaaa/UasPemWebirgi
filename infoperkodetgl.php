<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Data Peserta</title>
    <link rel="stylesheet" type="text/css" href="info.css">
</head>
<body>
    <h2 align="center">Data Peserta Berdasarkan ID Peserta dan Tanggal Daftar</h2>

    <?php
    // Koneksi ke database
    include 'koneksi.php';

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tanggal_daftar = $_POST['tanggal_daftar'];
        $id_peserta = $_POST['id_peserta'];

        // Prepare the SQL query based on provided inputs
        $query = "SELECT peserta.id_peserta, peserta.nama_peserta, peserta.email, peserta.no_telp, peserta.tanggal_lahir, peserta.tanggal_daftar, kursus.nama_kursus
                  FROM peserta
                  INNER JOIN kursus ON peserta.kursus_id = kursus.id_kursus
                  WHERE peserta.tanggal_daftar = ?";

        // Append filter by id_peserta if provided
        if (!empty($id_peserta)) {
            $query .= " AND peserta.id_peserta = ?";
        }

        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Error: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters
        if (!empty($id_peserta)) {
            $stmt->bind_param("ss", $tanggal_daftar, $id_peserta);
        } else {
            $stmt->bind_param("s", $tanggal_daftar);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Informasi Peserta untuk Tanggal Daftar $tanggal_daftar dan ID Peserta $id_peserta</h2>";
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
            echo "<p align='center'>Tidak ada informasi peserta untuk Tanggal Daftar: $tanggal_daftar dan ID Peserta: $id_peserta</p>";
        }

        $stmt->close();
    } else {
        // Form to input tanggal_daftar and id_peserta
        echo "<form method='post' align='center'>
                <label for='tanggal_daftar'>Masukkan Tanggal Daftar:</label>
                <input type='date' id='tanggal_daftar' name='tanggal_daftar' required>
                <br>
                <label for='id_peserta'>Masukkan ID Peserta:</label>
                <input type='text' id='id_peserta' name='id_peserta'>
                <br>
                <input type='submit' value='Tampilkan Informasi'>
            </form>";
    }

    $conn->close();
    ?>
</body>
</html>
