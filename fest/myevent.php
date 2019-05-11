<?php

require('function.php');
debug('<<<マイイベント一覧>>>');
debugLogstart();

require('auth.php');

if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}

$span = 20;
$minlist = ($nowPage-1)*$span;
$dbFormdata = getEvent($_SESSION['user_id'], $span, $minlist);
$link = 'myevent.php';
$link .= rewriteGet(array('p'));
if(!empty($dbFormdata['data'])){
    if($nowPage > $dbFormdata['total_page'] || empty($nowPage)){
        header('Location: myevent.php');
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
    <link rel="stylesheet" type="text/css" href="mypage.css">
    <link rel="stylesheet" type="text/css" href="mydiary.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="main">
            <h2><span class="h2">イベント一覧</span>
                <?php if(empty($dbFormdata['data'])){
                echo '作成したイベントはありません';
            }else{ ?>
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
            <p class="result">
                <a href="event.php?c_id=<?php echo $_SESSION['user_id']; ?>&b_id=<?php echo $val['id']; ?>">
                    <span class="title">
                        <?php echo mb_substr(sanitize($val['title']),0,15); ?></span>
                    <?php if(empty($val['open_flg'])){
                        ?>
                    <span class="open">公開</span>
                    <?php }else{ ?>
                    <span class="draft">下書き</span>
                    <?php } ?><br>
                    開催日：
                    <?php echo date('Y/m/d H:i', strtotime($val['start_date'])); ?> ~
                    <?php echo date('H:i', strtotime($val['end_time'])); ?>
                    <br>
                    参加期限：
                    <?php echo date('Y/m/d H:i', strtotime($val['limit_date'])); ?>
                    <br>
                    参加人数：
                    <?php echo getAttender($val['id']); ?> /
                    <?php echo $val['limit_num']; ?> 人
                    <span class="user">更新日：
                        <?php echo date('Y/m/d', strtotime($val['update_date'])); ?></span>
                </a>
            </p>
            <?php } ?>
            <?php pagenation($nowPage, $dbFormdata['total_page'], '5', $link); ?>
            <?php } ?>
        </section>

        <?php
        require('side.php');
        ?>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
