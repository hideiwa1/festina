<?php

require('function.php');
debug('<<<ユーザー登録確認画面>>>');
debugLogstart();

if(!empty($_SESSION['name']) && !empty($_SESSION['email']) && !empty($_SESSION['pass'])){
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $pass = $_SESSION['pass'];
    
    if($_POST['back']){
        header("Location:signup.php");
        exit;
    }else if(!empty($_POST['submit'])){
        try{
            $dbh = dbconnect();
            $sql = 'INSERT INTO users (name, email, password, create_date) VALUES (:name, :email, :pass, :c_date)';
            $data = array(':name' => $name, ':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':c_date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                $_SESSION['user_id'] = $dbh -> lastInsertId();
                $_SESSION['login_time'] = time();
                $_SESSION['login_limit'] =
                    60*60*24;
                $arr = array('name', 'email', 'pass');
                unsetSession($arr);
                header("Location:mypage.php");
                exit;
            }
        }catch (exception $e){
            debug('エラー発生:'.$e -> getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}else{
    header("Location:signup.php");
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
    <link rel="stylesheet" type="text/css" href="checkSmall.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="form">
            <h2>ユーザー登録 確認ページ</h2>
            <form method="post">
                <p><span class="item">メールアドレス</span><br>
                    <span>
                        <?php echo sanitize($email); ?></span>
                </p>
                <p><span class="item">名前（ハンドルネーム）</span><br>
                    <span>
                        <?php echo sanitize($name); ?></span>
                </p>
                <p><span class="item">パスワード</span><br>
                    <span>＊＊＊＊＊＊</span>
                </p>
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
