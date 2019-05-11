<?php

require('function.php');
debug('<<<イベント確認ページ>>>');
debugLogstart();

require('auth.php');

if(!empty($_SESSION['title']) && !empty($_SESSION['body']) ){
    $title = $_SESSION['title'];
    $s_date = $_SESSION['start_date'];
    $e_time = $_SESSION['end_time'];
    $l_date = $_SESSION['limit_date'];
    $l_num = $_SESSION['limit_num'];
    $body = $_SESSION['body'];
    $pic1 = $_SESSION['pic1'];
    $pic2 = $_SESSION['pic2'];
    $pic3 = $_SESSION['pic3'];
    $edit_flg = $_SESSION['edit_flg'];
    $e_id = $_SESSION['e_id'];

    if(!empty($_POST['back'])){
        unsetSession(array('e_id', 'edit_flg'));
        if($e_id){
            header("Location: registevent.php?e_id=".$e_id);
            exit;
        }else{
            header("Location: registevent.php");
            exit;
        }
    }else if(!empty($_POST)){
        try{
            $dbh = dbconnect();
            if(!empty($_POST['draft'])){
                $o_flg = 1;
            }else{
                $o_flg = 0;
            }
            if($edit_flg){
                $sql = 'UPDATE event SET title = :title, start_date = :s_date, end_time = :e_time, limit_date = :l_date, limit_num = :l_num, body = :body, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3, open_flg = :o_flg WHERE user_id = :u_id AND id = :id';
                $data = array(':title' => $title, ':s_date' => $s_date, ':e_time' => $e_time, ':l_date' => $l_date, ':l_num' => $l_num, ':body' => $body, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':id' => $e_id, ':o_flg' => $o_flg);
            }else{
                $sql = 'INSERT INTO event (title, start_date, end_time, limit_date, limit_num, body, user_id, pic1, pic2, pic3, open_flg, create_date) VALUES (:title, :s_date, :e_time, :l_date, :l_num, :body, :u_id, :pic1, :pic2, :pic3, :o_flg, :date)';
                $data = array(':title' => $title, ':s_date' => $s_date, ':e_time' => $e_time, ':l_date' => $l_date, ':l_num' => $l_num, ':body' => $body, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':o_flg' => $o_flg, ':date' => date('Y-m-d H:i:s'));
            }
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                debug('成功');
                $arr = ['title', 'start_date', 'end_time', 'limit_date','limit_num', 'body','pic1', 'pic2', 'pic3', 'edit_flg', 'b_id'];
                unsetSession($arr);

                header("Location: mypage.php");
                exit;
            }
        }catch (Exception $e){
            debug('エラー発生:'.$e -> getMessage());
        }
    }
}else{
    header("Location: mypage.php");
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
    <link rel="stylesheet" type="text/css" href="check.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="form">
            <h2>イベント登録 確認ページ</h2>
            <form method="post">
                <p><span class="item">タイトル</span><br>
                    <span>
                        <?php echo getFormdata('title'); ?></span>
                </p>
                <p><span class="item">開催日時</span><br>
                    <span>
                        <?php echo date('Y/m/d H:i', strtotime(getFormdata('start_date'))); ?> ~
                        <?php echo date('H:i', strtotime(getFormdata('end_time'))); ?></span>
                </p>
                <p><span class="item">参加期日</span><br>
                    <span>
                        <?php echo date('Y/m/d H:i', strtotime(getFormdata('limit_date'))); ?> まで</span>
                </p>
                <p><span class="item">定員</span><br>
                    <span>
                        <?php echo getFormdata('limit_num'); ?>人</span>
                </p>
                <p><span class="item">内容</span><br>
                    <span>
                        <?php echo getFormdata('body'); ?></span>
                </p>
                <div class="img">
                    <img src="<?php echo getFormdata('pic1'); ?>">
                    <img src="<?php echo getFormdata('pic2'); ?>">
                    <img src="<?php echo getFormdata('pic3'); ?>">
                </div>
                <div class="button">
                    <div>
                        <input type="submit" name="back" value="修正する" class="back">
                        <input type="submit" name="draft" value="下書き" class="back">
                        <input type="submit" name="submit" value="登録する" class="submit">
                    </div>
                </div>
            </form>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
