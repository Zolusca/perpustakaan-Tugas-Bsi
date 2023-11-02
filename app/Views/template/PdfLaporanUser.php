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
    <?php if(isset($userData)):
//        var_dump($userData[0][0]->nama);
//    $dataListUser = $userData[0];?>

    <table class="pure-table" style="margin: 20px auto auto;border-collapse: collapse;">
        <thead >
        <tr>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Email</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Nama User</th>
            <th style="text-align: left;background-color: #2aa198;color: white;padding: 8px;border: 1px solid #ddd;">Alamat</th>
        </tr>
        </thead>

        <?php foreach ($userData[0] as $value):?>

            <tbody>
            <tr>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->email?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->nama?></td>
                <td style="color: black;padding: 8px;border: 1px solid #ddd;"><?=$value->alamat?></td>
            </tr>
            </tbody>


        <?php endforeach;?>
        <?php endif;?>
    </table>


</div><!-- Container -->

</body>
</html>

