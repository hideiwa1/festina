<?php

require('function.php');
debug('<<<このサイトについて>>>');
debugLogstart();

?>
<!DOCTYPE html>
<html lang="ja" ?>

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="info.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
        require('header.php');
        ?>

    <main class="site-width">
        <h2>
            このサイトについて
        </h2>
        <p>
            このサイトは、私、<a href="https://twitter.com/hideiwa1">ライカ</a>が勉強用に作成したWebサービス用サイトです。<br>
            現在<a href="https://webukatu.com/?afid=gkhi8dmoz06yv4n35cqefwbur1pxj9s7">ウェブカツ!!</a>にてプログラミングの勉強中です。<br>
            まだまだ至らない点がございますので、お気付きの点は<a href="https://twitter.com/hideiwa1">ツイッター</a>までお知らせいただけると幸いです。

        </p>
    </main>

    <?php
        require('footer.php');
        ?>

</body>

</html>
