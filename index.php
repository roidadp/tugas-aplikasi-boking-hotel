<?php
// fungsi bersihin data input
function test_input($data)
{
    $data = htmlspecialchars(stripslashes(trim($data)));
    return $data;
}

// Ambil Data yang ingin diedit
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'edt') {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    require 'pdo/config.php';
    try {
        $sql = 'select * from book_detail where id_bodet = :id;';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $erow = $stmt->fetch();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Hapus data 
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'del') {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    require 'pdo/config.php';
    try {
        $sql = "delete from book_detail where id_bodet = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt->closeCursor();

        $info = 'Data berhasil dihapus';
        header('location: index.php?info=' . $info);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Tambah data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['nama_pelanggan'])) {
        $nampelErr = "Isi nama pelanggannya!";
    } else {
        $nampel = filter_var(test_input($_POST['nama_pelanggan']), FILTER_SANITIZE_STRING);
        if (strlen($nampel) > 30) {
            $nampelErr = "Batas nama adalah 30";
        }
    }
    if (isset($_POST['alamat'])) {
        $alamat = filter_var(test_input($_POST['alamat'], FILTER_SANITIZE_STRING));
    } else {
        $alamat = '';
    }
    if (empty($_POST['jenkam'])) {
        $jenkamErr = 'Pilih jenis kamarnya!';
    } else {
        $jenkam = filter_var(test_input($_POST['jenkam']), FILTER_SANITIZE_NUMBER_INT);
    }
    if (empty($_POST['no_kamar'])) {
        $nokamErr = 'Isi nomor kamarnya!';
    } else {
        $nokam = filter_var(test_input($_POST['no_kamar']), FILTER_SANITIZE_STRING);
    }
    if (empty($_POST['lama_inap'])) {
        $lanapErr = 'Isi lama inapnya!';
    } else {
        $lanap = filter_var(test_input($_POST['lama_inap']), FILTER_SANITIZE_NUMBER_INT);
    }

    if (!empty($nampelErr) || !empty($jenkamErr) || !empty($nokamErr) || !empty($lanapErr)) {
        header('location: index.php?nampelErr=' . $nampelErr . '&jenkamErr=' . $jenkamErr . '&nokamErr=' . $nokamErr . '&lanapErr=' . $lanapErr);
        exit;
    }

    require 'pdo/config.php';
    try {
        $sql = 'insert into book_detail (nama_pelanggan,alamat,id_jenkam,no_kamar,lama_inap) values (:nampel, :alamat, :jenkam, :nokam, :lanap);';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nampel', $nampel);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':jenkam', $jenkam);
        $stmt->bindParam(':nokam', $nokam);
        $stmt->bindParam(':lanap', $lanap);
        $stmt->execute();

        $info = 'Data berhasil ditambahkan';
        header('location: index.php?info=' . $info);
        exit;
    } catch (PDOException $e) {
        echo 'Error saat memasukkan data : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/jquery.dataTables.min.css">

    <script src="assets/jquery.min.js"></script>
    <script src="assets/jquery.dataTables.min.js"></script>
    <script src="assets/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light bg-gradient">
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-primary">
                    <h1 class="display-2 text-primary text-center">Aplikasi Booking Hotel Tugas Roidadp</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="post" action="<?= isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'edt' ? 'pdo/edt.php' : 'index.php'; ?>">
                            <input type="hidden" name="id" value="<?= $id ?? '';?>">
                            <div class="row gx-3">
                                <div class="col-lg-6 mb-3">
                                    <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="<?= $erow['nama_pelanggan'] ?? ''; ?>">
                                    <?= isset($_GET['nampelErr']) ? '<small class="text-danger">' . $_GET['nampelErr'] . '</small>' : ''; ?>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="1" class="form-control"><?= $erow['alamat'] ?? ''; ?></textarea>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-lg-4 mb-3">
                                    <label for="jenkam" class="form-label">Jenis Kamar</label>
                                    <select name="jenkam" id="jenkam" class="form-select">
                                        <option selected disabled>-Pilih-</option>
                                        <?php
                                        require 'pdo/config.php';
                                        try {
                                            $sql = "SELECT * FROM jenis_kamar";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            while ($row = $stmt->fetch()) {
                                                $selected = $erow['id_jenkam'] == $row['id_jenkam'] ? 'selected' : '';
                                        ?>
                                                <option value=<?= '"' . $row['id_jenkam'] . '" ' . $selected; ?>><?= ucfirst($row['jenkam']) . ' - Rp. ' . number_format($row['biaya'], 2) ?></option>
                                        <?php
                                            }
                                            $stmt->closeCursor();
                                        } catch (PDOException $e) {
                                            echo '<option>' . $e->getMessage() . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <?= isset($_GET['jenkamErr']) ? '<small class="text-danger">' . $_GET['jenkamErr'] . '</small>' : ''; ?>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="no_kamar" class="form-label">Nomor Kamar</label>
                                    <input type="number" name="no_kamar" id="no_kamar" class="form-control" max="999" value="<?= $erow['no_kamar'] ?? ''; ?>">
                                    <?= isset($_GET['nokamErr']) ? '<small class="text-danger">' . $_GET['nokamErr'] . '</small>' : ''; ?>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="lama_inap" class="form-label">Lama Inap</label>
                                    <div class="input-group">
                                        <input type="number" name="lama_inap" id="lama_inap" class="form-control" value="<?= $erow['lama_inap'] ?? '' ?>">
                                        <label for="" class="input-group-text"> Hari </label>
                                    </div>
                                    <?= isset($_GET['lanapErr']) ? '<small class="text-danger">' . $_GET['lanapErr'] . '</small>' : ''; ?>
                                </div>
                            </div>
                            <div class="row mt-2 gx-2">
                                <div class="col-lg-1">
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </div>
                                <div class="col-lg-1">
                                    <a href='index.php' class="btn btn-outline-secondary w-100">Cancel</a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <?= isset($_GET['info']) ?
                    '<div class="alert alert-info text-info">' . $_GET['info'] . '</div>' : ''; ?>
            </div>
            <div class="col-12">
                <div class="card py-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-light table-hover table-striped dtabel text-center">
                                <thead>
                                    <tr class="table-primary text-primary">
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Jenis Kamar</th>
                                        <th>Nomor Kamar</th>
                                        <th>Biaya</th>
                                        <th>Lama Inap</th>
                                        <th>Total Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require 'pdo/config.php';
                                    try {
                                        $sql = "select b.id_bodet, b.nama_pelanggan, b.alamat, date(b.tanggal_masuk) tanggal_masuk, j.jenkam, b.no_kamar, j.biaya, b.lama_inap, j.biaya * b.lama_inap total_bayar from book_detail b join jenis_kamar j on b.id_jenkam = j.id_jenkam";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $total = 0;
                                        $no = 1;
                                        while ($row = $stmt->fetch()) {
                                            $total += $row['total_bayar'];
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $row['nama_pelanggan'] ?></td>
                                                <td><?= $row['alamat'] ?></td>
                                                <td><?= $row['tanggal_masuk'] ?></td>
                                                <td><?= ucfirst($row['jenkam']) ?></td>
                                                <td><?= $row['no_kamar'] ?></td>
                                                <td><?= 'Rp. ' . number_format($row['biaya']) ?></td>
                                                <td><?= $row['lama_inap'] ?></td>
                                                <td><?= 'Rp. ' . number_format($row['total_bayar']) ?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="index.php?action=edt&id=<?= $row['id_bodet'] ?>">edt</a>
                                                    <a href="index.php?action=del&id=<?= $row['id_bodet'] ?>" class="btn btn-outline-primary">del</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                        $stmt->closeCursor();
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="8">' . $e->getMessage() . '</td></tr>';
                                    }
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="8" class="text-start">Total Seluruh</th>
                                        <th colspan="2" class="text-start"><?= 'Rp. ' . number_format($total) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.dtabel').DataTable();
        })
    </script>
</body>

</html>