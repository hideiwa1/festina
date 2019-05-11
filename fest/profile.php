<?php

require('function.php');
debug('<<<プロフィール>>>');
debugLogstart();

if(!empty($_GET['c_id'])){
    $c_id = $_GET['c_id'];
}else{
    header("Location: index.php");
    exit;
}

$userdata = getUser($c_id);
$arr['lang'] = explode(',', $userdata['lang']);
$dblang = getLang();

?>
<!DOCTYPE html>
<html lang="ja" ?>

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="diary.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <?php
        require('userProfile.php');
        ?>

        <div class="main">
            <section>
                <h2>プロフィール<br>
                </h2>
                <div class="left">
                    <img src="<?php echo sanitize($userdata['pic']); ?>">
                </div>
                <div class="right">
                    <p class="title">
                        <?php echo sanitize($userdata['name']); ?>
                    </p>
                    <p class="title">
                        <?php echo sanitize($userdata['age']); ?>歳
                    </p>
                    <p class="title">
                        使用言語
                    </p>
                    <ul class="lang">
                        <?php
                        foreach($dblang as $key => $val){
                        ?>
                        <li <?php if(in_array($val['id'], $arr['lang'], true)) echo 'class="use"' ; ?>>
                            <?php echo sanitize($val['name']); ?>
                        </li>

                        <?php } ?>
                    </ul>
                    <p class="title">
                        <?php echo sanitize($userdata['comment']); ?>
                    </p>
                </div>
            </section>
            <div class="clear"></div>
        </div>
    </main>

    <?php
    require('footer.php');
    ?>

</body>



</html>
