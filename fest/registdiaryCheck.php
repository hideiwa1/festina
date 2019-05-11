<?php

require('function.php');

debug('<<<日記投稿確認ページ>>>');
debugLogstart();
debug('POST:'.print_r($_POST, true));

require('auth.php');

if(!empty($_SESSION['title']) && !empty($_SESSION['comment'])){
    $title = $_SESSION['title'];
    $category = $_SESSION['category'];
    $comment = $_SESSION['comment'];
    $pic1 = $_SESSION['pic1'];
    $pic2 = $_SESSION['pic2'];
    $pic3 = $_SESSION['pic3'];
    $edit_flg = $_SESSION['edit_flg'];
    $b_id = $_SESSION['b_id'];
    
    $dbh = dbconnect();
    $sql = 'SELECT name FROM category WHERE id = :id';
    $data = array(':id' => $category);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    $c_name = array_shift($result);
    
    if(!empty($_POST['back'])){
        unsetSession(array('b_id', 'edit_flg'));
        if($b_id){
            header("Location: registdiary.php?b_id=".$b_id);
            exit;
        }else{
            header("Location: registdiary.php");
            exit;
        }
    }else if(!empty($_POST)){
        try{
            if(!empty($_POST['draft'])){
                $o_flg = 1;
            }else{
                $o_flg = 0;
            }
            if($edit_flg){
                $sql = 'UPDATE blog SET title = :title, category = :category, comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3, open_flg = :o_flg WHERE user_id = :u_id AND id = :id';
                $data = array(':title' => $title, ':category' => $category, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':id' => $b_id, ':o_flg' => $o_flg);
            }else{
                $sql = 'INSERT INTO blog (title, category, comment, user_id, pic1, pic2, pic3, open_flg, create_date) VALUES (:title, :category, :comment, :u_id, :pic1, :pic2, :pic3, :o_flg, :date)';
                $data = array(':title' => $title, ':category' => $category, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'), ':o_flg' => $o_flg);
            }
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                debug('成功');
                $arr = ['title', 'category', 'comment', 'pic1', 'pic2', 'pic3', 'edit_flg', 'b_id'];
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
<html lang="ja">

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
            <h2>日記投稿 確認ページ</h2>
            <form method="post">
                <p><span class="item">タイトル</span><br>
                    <span>
                        <?php echo getFormdata('title'); ?> </span>
                </p>
                <p><span class="item">カテゴリー</span><br>
                    <span>
                        <?php echo $c_name; ?> </span>
                </p>
                <p><span class="item">内容</span><br>
                    <span>
                        <?php echo getFormdata('comment'); ?> </span>
                </p>
                <div class="img">
                    <img src="<?php echo getFormdata('pic1'); ?> ">
                    <img src="<?php echo getFormdata('pic2'); ?> ">
                    <img src="<?php echo getFormdata('pic3'); ?> ">
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
