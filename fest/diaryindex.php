<?php

require('function.php');
debug('<<<日記一覧ページ>>>');
debugLogstart();

if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}
$span = 20;
$minlist = ($nowPage-1)*$span;
$link = 'diaryindex.php';
$link .= rewriteGet(array('p'));
$dbCategory = getCategory();

if(!empty($_GET['keyword'])){
$keyword = $_GET['keyword'];
}else{
$keyword = '';
}
if(!empty($_GET['category'])){
    $category = $_GET['category'];
}else{
    $category = '';
}

try{
    $dbDiary = getDiaryAll($keyword, $category, $span, $minlist);
    debug('$dbDiary:'.print_r($dbDiary, true));
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
                <h2>日記検索</h2>
                <p>キーワード
                    <input type="text" name="keyword" value="<?php if(!empty($_GET['keyword'])) echo sanitize($_GET['keyword']); ?>">
                </p>
                <p>カテゴリー<span class="err-msg">
                        <?php if(!empty($err_msg['category'])) echo $err_msg['category']; ?>
                    </span>
                    <select name="category">
                        <option value="0" <?php if(empty($_GET['category'])) echo 'selected' ; ?> >選択してください</option>
                        <?php
                        foreach($dbCategory as $key => $val){
                        ?>
                        <option value="<?php echo $val['id'];?>" <?php if($_GET['category']===$val['id']) echo 'selected' ; ?> >
                            <?php echo $val['name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </p>
                <button type="submit">検索する</button>
            </form>
        </section>

        <section>
            <h3>検索結果</h3>

            <p style="overflow: hidden;">
                <span class="total">全
                    <?php echo $dbDiary['total']; ?>件</span>
                <span class="page">
                    <?php echo ($minlist+1); ?> -
                    <?php echo (($minlist + $span) > $dbDiary['total'])? $dbDiary['total'] : ($minlist + $span) ; ?> 件 /
                    <?php echo $dbDiary['total']; ?>件</span>
            </p>
            <div>
                <?php foreach($dbDiary['data'] as $key => $val){
                ?>
                <p class="result diary">
                    <a href="diary.php?c_id=<?php echo sanitize($val['user_id']); ?>&b_id=<?php echo sanitize($val['id']); ?>">
                        <img src="<?php if(!empty($val['pic'])){
                    echo sanitize($val['pic']);
                }else{
                    echo 'img/silet.png';}?>">
                        <span class="title">
                            <?php echo sanitize($val['title']); ?></span><br>
                        <?php echo mb_substr(sanitize($val['comment']), 0, 20); ?><br>
                        <span class="user">
                            <?php echo sanitize($val['name']); ?> 更新日：
                            <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                    </a>
                </p>
                <?php } ?>
            </div>
            <div class="clear"></div>
            <?php echo pagenation($nowPage, $dbDiary['total_page'], '5', $link); ?>

        </section>
    </main>

    <?php
require('footer.php');
?>

</body>

</html>
