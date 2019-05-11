<?php

require('function.php');
debug('<<<日記一覧>>>');
debugLogstart();

if(!empty($_GET['c_id'])){
    $c_id = $_GET['c_id'];
}else{
    header("Location: index.php");
    exit;
}

$userdata = getUser($c_id);


if(!empty($_GET['p'])){
$nowPage = (int)$_GET['p'];
}else{
$nowPage = 1;
}
$span = 20;
$minlist = ($nowPage-1)*$span;
$dbFormdata = getDiary($c_id, $span, $minlist, false);
$link = 'diarylist.php';
$link .= rewriteGet(array('p'));
if(!empty($dbFormdata['total_page'])){
    if($nowPage > $dbFormdata['total_page'] || empty($nowPage)){
    header('Location:'.$link);
    exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja" ?>

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="list.css">
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

        <section class="main">
            <h2><span class="h2">日記一覧</span>
                <div class="number">
                    <span class="total">全
                        <?php echo sanitize($dbFormdata['total']); ?>件</span>
                    <span class="page">
                        <?php echo ($minlist+1); ?> -
                        <?php echo (($minlist + $span) > $dbFormdata['total'])? $dbFormdata['total'] : ($minlist + $span) ; ?> 件 /
                        <?php echo $dbFormdata['total']; ?>件</span>
                </div>
            </h2>
            <div>
                <?php
                foreach($dbFormdata['data'] as $key => $val){
                ?>
                <p class="result">
                    <a href="diary.php<?php echo rewriteGet().'&b_id='.$val['id']; ?>">
                        <span class="title">
                            <?php echo mb_substr(sanitize($val['title']),0,15); ?></span><br>
                        <?php echo mb_substr(sanitize($val['comment']), 0, 30); ?>
                        <span class="user">更新日：
                            <?php echo sanitize(date('Y/m/d H:i', strtotime($val['update_date']))); ?></span>
                    </a>
                </p>
                <?php } ?>
            </div>
            <?php
            pagenation($nowPage, $dbFormdata['total_page'], '5', $link); 
            ?>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
