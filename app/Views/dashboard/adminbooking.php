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
    <link rel="stylesheet" href="/css/adminbooking.css">
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
            <li><a href="<?= base_url()."user/dashboard/buku/booking/list"?>">Daftar Anggota</a></li>
        </ul>
    </nav>
</header>


<div class="container">

    <?php if (isset($dataResponse)): ?>
        <?php

            echo "<script>alert('{$dataResponse}');</script>";


        ?>
    <?php endif; ?>

    <div class="kiri-empty"></div>
    <div class="kanan-empty"></div>
    <div class="core">

        <div class="konten">
            <div class="pencarianuser">
                <form action="<?= base_url().'admin/dashboard/caribooking'?>" class="form-email" method="post">
                    <span>Pencarian User Booking</span>
                    <table>
                        <tbody>
                        <tr>
                            <td>Email User </td>
                            <td><input type="email" name="email" required> </td>
                            <td><input type="submit" value="cari"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div><!---pencarian-->

            <div class="datauserbooking">

                <form action="<?= base_url()."admin/dashboard/userambilbuku"?>" method="post">
                    <table>
                        <thead>
                        <tr>
                            <td>Nama</td>
                            <td>Email</td>
                            <td>Judul Buku</td>
                            <td>Pengarang</td>
                            <td>Denda</td>
                            <td>Lama pinjam</td>
                            <td>Aksi</td>
                        </tr>
                        </thead>

                        <?php if (isset($databooking)) :?>
                        <!--     index disni adalah nilai index dari array dari 0 sampai habis       -->
                            <?php foreach ($databooking as $index=>$value):?>

                                <tbody>
                                <tr>
                                    <td><?= $value->nama?></td>
                                    <td><?= $value->email?></td>
                                    <td><?= $value->judul_buku?></td>
                                    <td><?= $value->pengarang?></td>
                                    <td><input type="number" name="denda[<?= $index ?>]" ></td>
                                    <td><input type="date"  name="tanggal[<?= $index ?>]" min="2023" ></td>
                                    <td>
                                        <input type="hidden" name="iduser" value="<?= $value->id_user?>">
                                        <input type="hidden" name="idbooking[<?= $index ?>]" value="<?= $value->id_booking ?>" >
                                        <input type="hidden" name="idbuku[<?= $index ?>]" value="<?= $value->id_buku ?>" >
                                        <input type="submit" value="Kirim" >
                                    </td>
                                </tr>
                                </tbody>
                            <?php endforeach;?>
                        <?php endif;?>
                    </table>
                </form>

            </div><!---data user booking-->
        </div><!---konten-->


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


