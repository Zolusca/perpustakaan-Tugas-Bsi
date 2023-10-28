<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/homeheader.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/bukudetail.css">
    <link rel="stylesheet" href="/css/footerhome.css">
    <title></title>
</head>
<body>

<header>
    <div class="brand"><a href="#">DashesLine</a></div>

    <nav>
        <ul>
            <li><a href="<?= base_url()."user/register"?>">Daftar</a></li>
            <li><a href="<?= base_url()."user/login"?>">Login</a></li>
            <li><a href="<?= base_url()."user/dashboard/buku"?>">Daftar Buku</a></li>
            <li><a href="<?= base_url()."user/dashboard/buku/booking/list"?>">Daftar Booking</a></li>
        </ul>
    </nav>
</header>


<div class="container">

    <div class="kiri-empty"></div>
    <div class="kanan-empty"></div>
    <div class="core">

        <div class="container-detail-buku">

            <?php if(isset($dataBuku)):
            //                var_dump($dataBuku["gambar"]); ?>
            <div class="data-detail-buku">

                <img src="<?php echo base_url()."bukupicture/".$dataBuku["gambar"]?>" alt="buku image">

                <table>
                    <tr>
                        <td>Judul Buku</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["judulBuku"]?></td>
                    </tr>
                    <tr>
                        <td>Kategori Buku</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["kategoriBuku"]?></td>
                    </tr>
                    <tr>
                        <td>Pengarang</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["pengarang"]?></td>
                    </tr>
                    <tr>
                        <td>Penerbit</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["penerbit"]?></td>
                    </tr>
                    <tr>
                        <td>Tahun Terbit</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["tahunTerbit"]?></td>
                    </tr>
                    <tr>
                        <td>ISBN</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["isbn"]?></td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["stok"]?></td>
                    </tr>
                    <tr>
                        <td>Dipinjam</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $dataBuku["dipinjam"]?></td>
                    </tr>
                </table>

            </div><!--data detail buku--->
            <?php endif;?>
        </div><!---detail buku--->

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
            <p>Source Code On My GitHub</p>
        </div>
        <span class="footer-copyright">Made in china</span>
    </footer>
</div>
</body>
</html>

