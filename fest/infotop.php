<?php

require('function.php');
debug('<<<お知らせ一覧>>>');
debugLogstart();

if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}
$span = 20;
$minlist = ($nowPage-1)*$span;
$dbFormdata = getInfoAll($span, $minlist, false);
$link = 'infotop.php';
$link .= rewriteGet(array('p'));
if($nowPage > $dbFormdata['total_page'] || empty($nowPage)){
    header('Location: mydiary.php');
    exit;
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
    <link rel="stylesheet" type="text/css" href="info.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <h2><span class="h2">お知らせ</span>
            <div class="number">
                <span class="total">全
                    <?php echo $dbFormdata['total']; ?>件</span>
                <span class="page">
                    <?php echo ($minlist+1); ?> -
                    <?php echo (($minlist + $span) > $dbFormdata['total'])? $dbFormdata['total'] : ($minlist + $span) ; ?> 件 /
                    <?php echo $dbFormdata['total']; ?>件</span>
            </div>
        </h2>

        <?php
            foreach($dbFormdata['data'] as $key => $val){
            ?>
        <div class="result">
            <a href="info.php?i_id=<?php echo $val['id']; ?>">
                <span class="title">
                    <?php echo $val['title']; ?>
                </span><br>
                <?php echo sanitize(mb_substr($val['body'], 0, 50)); ?><br>
                <span class="user">更新日：
                    <?php echo date('Y/m/d H:i', strtotime($val['update_date'])); ?></span>
            </a>
        </div>
        <?php } ?>

        <?php pagenation($nowPage, $dbFormdata['total_page'], '20', $link); ?>
    </main>

    <?php
    require('footer.php');
    ?>

</body>



</html>
