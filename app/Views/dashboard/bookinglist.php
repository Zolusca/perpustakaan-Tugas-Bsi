<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/homeheader.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/bukubooking.css">
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

        <div class="data-booking">
            <span> Maksimum Booking 3 buku </span>

            <?php
            if(isset($dataBooking)):
            //            var_dump($dataBooking[0]);
            $dataList = $dataBooking[0]; ?>

            <table>
                <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Booking</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Batalkan Booking</th>
                </tr>
                </thead>

                <?php foreach ($dataList as $valueData):?>

                <tbody>
                <tr>
                    <td><?= $valueData->gambar?></td>
                    <td><?= $valueData->judul_buku?></td>
                    <td><?= $valueData->tgl_booking?></td>
                    <td><?= $valueData->pengarang?></td>
                    <td><?= $valueData->penerbit?></td>
                    <td>
                        <form method="get" action="<?php echo base_url()."user/dashboard/buku/booking/delete/".$valueData->id_temp?>">
                            <input type="submit" value="Hapus" >
                        </form>
                    </td>
                </tr>
                </tbody>

                <?php endforeach;?>

                <tfoot>
                <tr>
                    <td>
                        <form method="get" action="<?= base_url()."user/dashboard/buku/booking/pdf" ?>">
                            <input type="hidden" name="idTemp" value="<?= $valueData->id_temp?>">
                            <input type="submit" value="Selesaikan Booking" >
                        </form>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
            <?php endif;?>

        </div><!---data booking--->

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


