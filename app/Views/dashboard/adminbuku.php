<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/homeheader.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/footerhome.css">
    <link rel="stylesheet" href="/css/adminbuku.css">
    <title></title>
</head>
<body>

<header>
    <div class="brand"><a href="#">DashesLine</a></div>

    <nav>
        <ul>
            <li><a href="<?= base_url()."admin/dashboard/main"?>">Data Buku</a></li>
            <li><a href="<?= base_url()."admin/dashboard/userbooking"?>">Data Booking</a></li>
            <li><a href="<?= base_url()."admin/dashboard/userpeminjam"?>">Data Peminjam</a></li>
            <li><a href="<?= base_url()."admin/dashboard/userlist"?>">Daftar Anggota</a></li>
            <li><a href="<?= base_url()."admin/dashboard/admincetaklaporan"?>">Cetak Laporan </a></li>
        </ul>
    </nav>
</header>


<div class="container">

    <?php if (isset($databuku)): ?>
        <?php

    // mengeceek apakah ada key "dataError"
        if(array_key_exists("dataError",$databuku)){

            // menggabungkan semua array menjadi string
            $jsAlertMessage = implode("\\n", $databuku["dataError"]);
            echo "<script>alert('{$jsAlertMessage}');</script>";

        }
        ?>
    <?php endif; ?>

    <div class="kiri-empty"></div>
    <div class="kanan-empty"></div>
    <div class="core">

            <div class="form-tambah-buku">
                <span>Form Tambah Buku</span>

                <form action="<?= base_url()."admin/dashboard/tambahbuku"?>" method="post" enctype="multipart/form-data"><!-- form -->
                    <table>
                        <tr>
                            <td>Judul Buku:</td>
                            <td><input type="text" name="judulbuku" minlength="5" maxlength="90"  required></td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>
                                <select name="kategori" required>

                                    <?php if (isset($databuku)) :?>
                                    <?php foreach ($databuku["kategori"] as $dataKategori):?>

                                    <option value="<?= $dataKategori->id_kategori?>"><?= $dataKategori->nama_kategori?></option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Pengarang</td>
                            <td><input type="text" name="pengarang" minlength="5" maxlength="90" required></td>
                        </tr>
                        <tr>
                            <td>Penerbit</td>
                            <td><input type="text" name="penerbit" minlength="5" maxlength="90" required></td>
                        </tr>
                        <tr>
                            <td>Tahun Terbit</td>
                            <td><input type="number" name="tahunterbit" min="1800" max="2023" required></td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td><input type="number" name="stok" min="0" required></td>
                        </tr>
                        <tr>
                            <td>Gambar</td>
                            <td><input type="file" accept="image/*" name="gambar" required></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="kirim"  required></td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div><!----tambah buku---->

        <div class="data-buku">
            <table>
                <thead>
                    <tr>
                        <td>judul buku</td>
                        <td>pengarang</td>
                        <td>penerbit</td>
                        <td>stok</td>
                        <td>isbn</td>
                        <td>dipinjam</td>
                    </tr>
                </thead>

                <tbody>

                <?php if (isset($databuku)) :?>
                <?php foreach ($databuku["buku"] as $valueBuku):?>
                    <tr>
                        <td><?= $valueBuku->judul_buku?></td>
                        <td><?= $valueBuku->pengarang?></td>
                        <td><?= $valueBuku->penerbit?></td>
                        <td><?= $valueBuku->stok?></td>
                        <td><?= $valueBuku->isbn?></td>
                        <td><?= $valueBuku->dipinjam?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>

            </table>
        </div><!---data buku-->

    </div><!---core-->

</div> <!--container--->

<div class="footer-container">
    <footer>
        <div class="image-social-media">
            <a href=""><img src="/resourcegambar/youtube.png" alt="image" style="width: 30px;height: 30px"></a>
            <a href=""><img src="/resourcegambar/github.png" alt="image" style="width: 30px;height: 30px"></a>
            <a href=""><img src="/resourcegambar/facebook.png" alt="image" style="width: 30px;height: 30px"></a>
        </div>
        <div class="source">
            <p>Source Code On My Github</p>
        </div>
        <span class="footer-copyright">Made in china</span>
    </footer>
</div>
</body>
</html>

