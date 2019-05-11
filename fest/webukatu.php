<?php

require('function.php');
debug('<<<ウェブカツ進捗管理>>>');
debugLogstart();

require('auth.php');

$user = $_SESSION['user_id'];
$club = getClub();
$lesson = getLesson();
$comment = getWebComment($user);

?>

<!DOCTYPE html>
<html lang="ja" ?>

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="webukatu.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="main">
            <h2>ウェブカツ！！進捗管理</h2>
            <?php foreach($club as $c_key => $c_val){ ?>
            <div class="club">
                <?php echo $c_val['name']; ?>
                <p class="open">開く</p>
                <p class="close" style="display: none;">閉じる</p>
                <ul class="lesson" style="display: none;">
                    <?php foreach($lesson as $l_key => $l_val){ ?>
                    <li class="comp_title">
                        <?php echo $l_val['name']; ?><br>
                        <div class="comp" data-compclub="<?php echo $c_val['id'];?>" data-complesson="<?php echo $l_val['id'];?>">
                            <img src="img/sumi.png" class="<?php foreach($comment as $key=> $val){
                            if($val['club_id'] === $c_val['id'] && $val['lesson_id'] === $l_val['id'] && $val['comp_flg'] === '1'){
                                echo 'stamp'; } } ?>">
                        </div>
                        <textarea name="lesson"><?php foreach($comment as $key=> $val){
    if($val['club_id'] === $c_val['id'] && $val['lesson_id'] === $l_val['id']){
        echo sanitize($val['comment']); } } ?></textarea>
                        <button class="memo" data-textclub="<?php echo $c_val['id'];?>" data-textlesson="<?php echo $l_val['id'];?>">保存</button>
                    </li>
                    <?php if($l_val['id'] === $c_val['lesson_num']){ break;}} ?>
                </ul>
            </div>
            <?php } ?>

            <div class="club">
                ネットワーク部
                <p class="open">開く</p>
                <p class="close" style="display: none;">閉じる</p>
                <ul class="lesson" style="display: none;">
                    <li>Lesson1<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson2<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson3<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson4<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson5<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson6<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson7<br>
                        <textarea name="lesson"></textarea></li>
                    <li>Lesson8<br>
                        <textarea name="lesson"></textarea></li>
                </ul>
            </div>
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
