<?php

require('function.php');
debug('<<<お知らせ詳細>>>');
debugLogstart();

if($_GET['i_id']){
    $i_id = $_GET['i_id'];
}else{
    header('Location:infotop.php');
}

$dbFormdata = getInfo($i_id);
debug('body:'.$dbFormdata['body']);

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
        <div class="main">
            <h3>
                <?php echo sanitize($dbFormdata['title']); ?><br>
                <span class="date">更新日：
                    <?php echo date('Y/m/d H:i', strtotime($dbFormdata['update_date'])); ?></span>
            </h3>
            <p>
                <?php echo nl2br(sanitize($dbFormdata['body'])); ?>
            </p>
        </div>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
