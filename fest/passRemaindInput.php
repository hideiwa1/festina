<?php

require('function.php');

debug('<<<パスワードリマインダー入力画面>>>');

if(!empty($_POST)){
    $auth_key = $_POST['auth-key'];

    validRequire($auth_key, 'auth-key');
    validHalf($auth_key, 'auth-key');
    if(empty($err_msg)){
        if($_SESSION['auth_key'] !== $auth_key){
            $err_msg['common'] = MSG10;
        }
        if($_SESSION['auth_limit'] < time()){
            $err_msg['common'] = MSG11;
        }
        if(empty($err_msg)){
            $pass = randKey();
            
            try{
                $dbh = dbconnect();
                $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
                $data = array(':pass' => password_hash($pass, PASSEORD_DEFAULT), ':email' => $_SESSION['auth_email']);
                $stmt = qyeryPost($dbh, $sql, $data);
                if($stmt){
                    $from = 'info@info.com';
                    $to = $_SESSION['auth_email'];
                    $subject = 'パスワード再設定のお知らせ';
                    $comment = <<<EOT
パスワードの再設定が完了いたしました。
新しいパスワードは{$pass}です
ログイン後にパスワードの再設定をお願いします。
http://localhost:8888/fest/login.php
EOT;
                    sendmail($from, $to, $subject, $comment);
                    session_unset();
                    header('Location: login.php');
                    exit;
                }
            }catch (Exception $e){
                debug('エラー発生:'.$e -> getMessage());
            }
        }
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
    <link rel="stylesheet" type="text/css" href="login.css">
    <title>Festina Lente</title>
</head>

<body>
    <?php
        require('header.php');
        ?>

    <main class="site-width">
        <section class="form">
            <h2>パスワード再設定</h2>
            <span class="err-msg">
                <?php echo errMsg('common'); ?></span>
            <form method="post">
                <p><span class="item">認証キー</span><br>
                    <input type="text" name="auth-key">
                </p>
                <input type="submit" value="送信する">
            </form>
        </section>
    </main>

    <?php
        require('footer.php');
        ?>

</body>

</html>
