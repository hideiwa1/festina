<?php

require('function.php');
debug('<<<プロフィール編集確認ページ>>>');
debugLogstart();

require('auth.php');

if(!empty($_SESSION['name']) && !empty($_SESSION['email'])){
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $age = $_SESSION['age'];
    $lang = $_SESSION['lang'];
    $comment = $_SESSION['comment'];
    $pic = $_SESSION['pic'];
    
    $dblang = getLang();
    $language = implode(',', $lang);
    
    if($_POST['back']){
        header("Location: profileEdit.php");
        exit;
    }else if(!empty($_POST)){
        try{
            $dbh = dbconnect();
            $sql = 'UPDATE users SET name = :name, email = :email, age = :age, lang = :lang, comment = :comment, pic = :pic WHERE id = :u_id';
            $data = array(':name' => $name, ':email' => $email, ':age' => $age, ':lang' => $language, ':comment' => $comment, ':pic' => $pic, ':u_id' => $_SESSION['user_id']);
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                debug('成功');
                $arr = ['name', 'email', 'age', 'lang', 'comment', 'pic'];
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
            <h2>プロフィール編集 確認ページ</h2>
            <form method="post">
                <p><span class="item">名前</span><br>
                    <span>
                        <?php echo getFormdata('name'); ?></span>
                </p>
                <p><span class="item">メールアドレス</span><br>
                    <span>
                        <?php echo getFormdata('email'); ?></span>
                </p>
                <p><span class="item">年齢</span><br>
                    <span>
                        <?php echo getFormdata('age'); ?>歳</span>
                </p>
                <p><span class="item">使用言語</span><br>
                    <span>
                        <?php
                    foreach($dblang as $key => $val){
                        ?>
                        <?php if(in_array($val['id'], $lang, true)) echo $val['name']; ?>
                        <?php } ?>
                    </span>

                </p>
                <p><span class="item">自己紹介</span><br>
                    <span>
                        <?php echo getFormdata('comment'); ?></span>
                </p>
                <p style="margin-bottom: 0;"><span class="item">プロフィール画像</span></p>
                <div class="img">
                    <img src="<?php echo getFormdata('pic'); ?>">
                </div>
                <div class="button">
                    <div>
                        <input type="submit" name="back" value="修正する" class="back">
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
