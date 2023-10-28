<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/homeheader.css">
    <link rel="stylesheet" href="/css/formlogin.css">
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
                <li><a href="#">Daftar Booking</a></li>
            </ul>
        </nav>
    </header>


    <?php if (isset($data)): ?>
        <?php
        // get message from array
        $getErrorMessage = $data["dataError"];

        echo "<script>alert('{$getErrorMessage}');</script>";

        ?>
    <?php endif; ?>

    <div class="container">
        <div class="kiri-empty"></div>
        <div class="kanan-empty"></div>
        <div class="core">
            <div class="form">
                <span class="form-span">Login</span>
                <!--            FORM                       -->
                <form action="/user/login" method="post" class="core-konten-form"><!-- form -->

                    <table class="core-konten-table">

                        <!--            INPUT                       -->

                        <tr>
                            <td><label for="email">Email</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="email" id="email" name="email" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="password">Kata Sandi</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="password" id="password" name="password" minlength="6" maxlength="50" required>
                            </td>
                        </tr>


                        <!--     SUBMIT INPUT                     -->
                        <tr>
                            <td class="core-konten-table-td-submit">
                                <input type="submit" value="Masuk">
                            </td>
                            <td>

                            </td>
                        </tr>

                        <!--            INPUT  End                     -->

                    </table>

                </form><!-- form -->

            </div>

            <div class="image">
                <img class="image" src="/resourcegambar/illustrasimembaca.png" alt="image">
            </div>

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
