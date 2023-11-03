<!DOCTYPE html>
<html  lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pdf download</title>
    <style>
        .container{
            font-size: 14px;
            font-family: "monospace";
        }
        .navbar {
            height: 30px;
            background-color: #24b3b3;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding-top: 20px;
        }
        span{
            font-weight: bold;
            margin-top: 10px;
        }
        i {
            display: block;
            text-align: center; /* Mengatur teks di tengah */
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container" ><!-- Container -->
    <div class="navbar">
        <span>DashesLines</span>
    </div>

    <h5>dicetak pada :  <?= date("Y.m.d")?></h5>
    <?php if(isset($bukuData)):
//            var_dump($bukuData['buku']);
    //    $dataListUser = $userData[0];?>

    <table class="pure-table" style="margin: 20px auto auto;border-collapse: collapse;">
        <thead >
        <tr>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Judul Buku</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Pengarang</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Kategori Buku</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Penerbit</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Stok</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Dipinjam</th>
        </tr>
        </thead>

        <?php foreach ($bukuData['buku'] as $index=>$value):?>

            <tbody>
            <tr>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->judul_buku?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->pengarang?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$bukuData['kategori'][$index<count($bukuData['kategori'])]->nama_kategori?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->penerbit?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->stok?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->dipinjam?></td>
            </tr>
            </tbody>


        <?php endforeach;?>
        <?php endif;?>
    </table>


</div><!-- Container -->

</body>
</html>



