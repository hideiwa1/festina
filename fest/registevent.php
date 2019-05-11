<?php

require('function.php');
debug('<<<イベント作成・編集ページ>>>');
debugLogstart();

require('auth.php');
debug('POST:'.print_r($_POST, true));

if(!empty($_GET['e_id'])){
    $e_id = $_GET['e_id'];
    $dbFormdata = getEventdata($e_id, $_SESSION['user_id']);
    if(!empty($_GET['e_id']) && empty($dbFormdata)){
        header("Location: mypage.php");
        exit;
    }else{
        $edit_flg = true;
    }
}else{
    $e_id = '';
    $edit_flg = false;
}
$dbCategory = getCategory();

if(!empty($_POST)){

    $title = $_POST['title'];
    $s_date = $_POST['start_date'];
    $e_time = $_POST['end_time'];
    $l_date = $_POST['limit_date'];
    if($_POST['limit'] === '1'){
        debug('1');
        $l_num = $_POST['limit_num'];
    }else{
        $l_num = 0;
    }
    $body = $_POST['body'];
    if(!empty($_FILES['pic1']['name'])){
        $pic1 = uploadImg($_FILES['pic1'], 'pic1');
    }else if(!empty($dbFormdata['pic1'])){
        $pic1 = $dbFormdata['pic1'];
    }else if(!empty($_POST['pic1'])){
        $pic1 = $_POST['pic1'];
    }else{
        $pic1 = '';
    }
    if(!empty($_FILES['pic2']['name'])){
        $pic2 = uploadImg($_FILES['pic2'], 'pic2');
    }else if(!empty($dbFormdata['pic2'])){
        $pic2 = $dbFormdata['pic2'];
    }else if(!empty($_POST['pic2'])){
        $pic2 = $_POST['pic2'];
    }else{
        $pic2 = '';
    }
    if(!empty($_FILES['pic3']['name'])){
        $pic3 = uploadImg($_FILES['pic3'], 'pic3');
    }else if(!empty($dbFormdata['pic3'])){
        $pic3 = $dbFormdata['pic3'];
    }else if(!empty($_POST['pic3'])){
        $pic3 = $_POST['pic3'];
    }else{
        $pic3 = '';
    }

    validRequire($title, 'title');
    validRequire($body, 'body');

    if(empty($err_msg)){
        validMaxlen($title, 'title');
        validMaxlen($body, 'body', '500');
        validNum($l_num, 'l_num');

        if(empty($err_msg)){
            $_SESSION['title'] = $title;
            $_SESSION['start_date'] = $s_date;
            $_SESSION['end_time'] = $e_time;
            $_SESSION['limit_date'] = $l_date;
            $_SESSION['limit_num'] = $l_num;
            $_SESSION['body'] = $body;
            $_SESSION['pic1'] = $pic1;
            $_SESSION['pic2'] = $pic2;
            $_SESSION['pic3'] = $pic3;
            $_SESSION['edit_flg'] = $edit_flg;
            $_SESSION['e_id'] = $e_id;

            header("Location: registeventCheck.php");
            exit;
        }
    }
}else{
    $arr = ['title', 'start_date', 'end_time', 'limit_date','limit_num', 'body','pic1', 'pic2', 'pic3'];
    sessionToPost($arr);
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
    <link rel="stylesheet" type="text/css" href="regist.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="main">
            <h2>イベント作成</h2>
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            <form method="post" enctype="multipart/form-data">
                <p><span class="item">タイトル</span>
                    <?php if(!empty($err_msg['title'])) echo $err_msg['title']; ?><br>
                    <input type="text" name="title" value="<?php echo getFormdata('title'); ?>">
                </p>
                <p><span class="item">開催日時</span>
                    <?php if(!empty($err_msg['start_date'])) echo $err_msg['start_date']; ?>
                    <?php if(!empty($err_msg['end_date'])) echo $err_msg['end_date']; ?><br>
                    <input type="datetime-local" step="3600" name="start_date" value="<?php echo date('Y-m-d\TH:i:s', strtotime(getFormdata('start_date'))); ?>"> 〜 <input type="time" step="3600" name="end_time" value="<?php echo getFormdata('end_time'); ?>">
                </p>
                <p><span class="item">参加期日</span>
                    <?php if(!empty($err_msg['limit_date'])) echo $err_msg['limit_date']; ?><br>
                    <input type="datetime-local" step="3600" name="limit_date" value="<?php echo date('Y-m-d\TH:i:s', strtotime(getFormdata('limit_date'))); ?>">まで
                </p>
                <p><span class="item">定員</span>
                    <?php if(!empty($err_msg['limit_num'])) echo $err_msg['limit_num']; ?><br>
                    <input type="radio" name="limit" value="1" <?php if(!empty(getFormdata('limit_num'))) echo 'checked' ; ?> ><input type="number" min="1" name="limit_num" value="<?php echo getFormdata('limit_num'); ?>">人まで
                    <input type="radio" name="limit" value="0" <?php if(empty(getFormdata('limit_num'))) echo 'checked' ; ?>>定員無し
                </p>
                <p><span class="item">内容</span>
                    <?php if(!empty($err_msg['body'])) echo $err_msg['body']; ?><br>
                    <textarea name="body" class="js-text"><?php echo getFormdata('body'); ?></textarea>
                </p>
                <p class="counter">
                    <span class="js-count"></span>文字 / 500文字
                </p>
                <p><span class="item">画像</span><span class="err-msg">
                        <?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?>
                    </span></p>
                <div class="img">
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic1'); ?>" alt="">
                        <input type="file" name="pic1" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic2'); ?>" alt="">
                        <input type="file" name="pic2" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic3'); ?>" alt="">
                        <input type="file" name="pic3" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                </div>

                <input type="hidden" name="pic1" value="<?php echo getFormdata('pic1'); ?>">
                <input type="hidden" name="pic2" value="<?php echo getFormdata('pic2'); ?>">
                <input type="hidden" name="pic3" value="<?php echo getFormdata('pic3'); ?>">
                <input type="submit" value="確認する">
            </form>
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
