<?php

require('function.php');
debug('<<<コミュニティ検索ページ>>>');
debugLogstart();

if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}
$span = 20;
$minlist = ($nowPage-1)*$span;
$link = 'userindex.php';
$link .= rewriteGet(array('p'));

    if(!empty($_GET['keyword'])){
        $keyword = $_GET['keyword'];
    }else{
        $keyword = '';
    }
    $category = $_GET['category'];

    try{
        $dbUser = getUserAll($keyword, $span, $minlist);
        debug('$dbUser:'.print_r($dbUser, true));
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
        $err_msg['common'] = MSG07;
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
    <link rel="stylesheet" type="text/css" href="community.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
        require('header.php');
        ?>

    <main class="site-width">
        <section class="serch">
            <form method="get">
                <h2>ユーザー検索</h2>
                <p>キーワード
                    <input type="text" name="keyword" value="<?php if(!empty($_GET['keyword'])) echo sanitize($_GET['keyword']); ?>">
                </p>
                <button type="submit">検索する</button>
            </form>
        </section>

        <section>
            <h3>検索結果</h3>

            <p style="overflow: hidden;">
                <span class="total">全
                    <?php echo $dbUser['total']; ?>件</span>
                <span class="page">
                    <?php echo ($minlist+1); ?> -
                    <?php echo (($minlist + $span) > $dbUser['total'])? $dbUser['total'] : ($minlist + $span) ; ?> 件 /
                    <?php echo $dbUser['total']; ?>件</span>
            </p>
            <div style="overflow: hidden;">
                <?php foreach($dbUser['data'] as $key => $val){
                    ?>
                <p class="result userProfile">
                    <a href="profile.php?c_id=<?php echo sanitize($val['user_id']); ?>">
                        <img src="<?php if(!empty($val['pic'])){
                        echo sanitize($val['pic']);
                    }else{
                        echo 'img/silet.png';}?>">
                        <span class="profile">
                            <?php echo sanitize($val['name']); ?><br>
                            <?php echo mb_substr(sanitize($val['comment']), 0, 20); ?>
                        </span>
                    </a>
                </p>
                <?php } ?>
            </div>
            <?php pagenation($nowPage, $dbFormdata['total_page'], '5', $link); ?>

        </section>
    </main>

    <?php
        require('footer.php');
        ?>

</body>

</html>
