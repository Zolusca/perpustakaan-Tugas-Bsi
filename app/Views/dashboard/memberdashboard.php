<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/homeheader.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/bukulist.css">
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

        <div class="card-buku">

            <?php if(isset($dataListBuku)) :
                $listBuku = $dataListBuku[0];
                ?>
                <?php foreach($listBuku as $data) : ?>

            <div class="data-buku"> <!----      DATA BUKU       ----->
                <img src="<?php echo base_url()."bukupicture/".$data->gambar?>" alt="buku">
                <h3><?= $data->judulBuku?></h3>
                <table>
                    <tr>
                        <td>Pengarang</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $data->pengarang?></td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td><?= $data->stok?></td>
                    </tr>
                    <tr>
                        <td><a href="<?php echo base_url()."user/dashboard/buku/detail/".$data->idBuku?>">Detail</a></td>
                        <td>&emsp;</td>
                        <td>&emsp;</td>
                        <td>
                            <form method="post" action="buku/booking">
                                <input type="hidden" name="idBuku" value="<?= $data->idBuku?>">
                                <input type="submit" value="Booking" >
                            </form>
                        </td>
                    </tr>
                </table>

            </div><!----      DATA BUKU       ----->
            <!---   END LOOP    -->
            <?php endforeach?>
            <?php endif?>
        </div><!-----card buku--->

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
