<?php
namespace App\Views;
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/judul.css">
    <link rel="stylesheet" href="/css/kontenLogin.css">
    <link rel="stylesheet" href="/css/footer.css">
</head>
<body>

            <?php if (isset($data)): ?>
                <?php
                $getValidationErrorMessage = $data[0]["dataError"];
                ?>
            <script>window.alert(<?php var_dump($getValidationErrorMessage);?>)</script>
            <?php endif; ?>

    <div class="container">

    <?= $this->include('App\Views\template\CoreTemplate') ?>

        <div class="konten">
            <div class="sidekiri-empty-konten"></div>
            <div class="sidekanan-empty-konten"></div>
            <div class="core-konten">

                <form action="/user/register" method="post" class="core-konten-form" enctype="multipart/form-data"><!-- form -->

                    <table class="core-konten-table">

                                    <!--            INPUT                       -->

                        <tr>
                            <td><label for="nama">Nama:</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="text" id="nama" name="nama" minlength="5" maxlength="100" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="email">Email:</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="email" id="email" name="email" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="alamat">Alamat:</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="text" id="alamat" name="alamat" minlength="10" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="password">Kata Sandi:</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="password" id="password" name="password" minlength="6" maxlength="50" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="gambar">Profile Picture:</label></td>
                            <td class="core-konten-table-td-input">
                                <input type="file" accept="image/*" id="gambar" name="gambar" required>
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

                        <!--            INPUT                       -->

                    </table>


                </form><!-- form -->

            </div>
        </div>

    </div><!-- container-->
    <script src="js/button_core.js"></script>

</body>
</html>