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
        </ul>
    </nav>
</header>


<div class="container">

    <div class="kiri-empty"></div>
    <div class="kanan-empty"></div>
    <div class="core">

        <div class="data-buku">
            <table>
                <thead>
                <tr>
                    <td>nama</td>
                    <td>alamat</td>
                    <td>email</td>
                </tr>
                </thead>

                <tbody>

                <?php if (isset($dataanggota)) :?>
                    <?php foreach ($dataanggota["user"] as $valueBuku):?>
                        <tr>
                            <td><?= $valueBuku->nama?></td>
                            <td><?= $valueBuku->alamat?></td>
                            <td><?= $valueBuku->email?></td>
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


