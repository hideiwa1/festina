<?php

require('function.php');

debug('<<<パスワードリマインダー>>>');

if(!empty($_POST)){
    $email = $_POST['email'];
    
    validRequire($email, 'email');
    validEmail($email, 'email');
    if(empty($err_msg)){
        try{
            $dbh = dbconnect();
            $sql = 'SELECT email FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);
            if(!empty($result)){
                $auth_key = randKey();
            
                $from = 'info@info.com';
                $to = $email;
                $subject = 'パスワード再設定のお知らせ';
                $comment = <<<EOT
パスワードを再設定します。
認証キー：{$auth_key}
有効期限は３０分です
設定用ページ：http://localhost:8888/fest/passRemaindInput.php
EOT;
                sendmail($from, $to, $subject, $comment);
                $_SESSION['auth_key'] = $auth_key;
                $_SESSION['auth_limit'] = time() + (60*30);
                $_SESSION['auth_email'] = $email;
                header('Location: http://localhost:8888/fest/passRemaindInput.php');
                exit;
            }else{
                $err_msg['common'] = MSG07;
            }
        }catch (Exception $e){
            debug('エラー発生:'.$e -> getMessage());
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
            <form method="post">
                <p><span class="item">メールアドレス</span><br>
                    <input type="text" name="email">
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
