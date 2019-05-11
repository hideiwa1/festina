<?php

require('function.php');
debug('<<<パスワード変更>>>');
debugLogstart();

require('auth.php');

if(!empty($_POST)){
    $old_pass = $_POST['old-pass'];
    $new_pass = $_POST['new-pass'];
    $pass_re = $_POST['pass-re'];

    validRequire($old_pass, 'old-pass');
    validRequire($new_pass, 'new-pass');

    if(empty($err_msg)){
        validHalf($old_pass, 'old-pass');
        validMinlen($old_pass, 'old-pass');
        validMaxlen($old_pass, 'old-pass', '20');

        validHalf($new_pass, 'new-pass');
        validMinlen($new_pass, 'new-pass');
        validMaxlen($new_pass, 'new-pass', '20');

        validPass($new_pass, $pass_re, 'new-pass');
        if(empty($err_msg)){
            try{
                $dbh = dbconnect();
                $sql = 'SELECT password FROM users WHERE id = :id';
                $data = array(':id' => $_SESSION['user_id']);
                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt -> fetch(PDO::FETCH_ASSOC);
                if(password_verify($old_pass, $result['password'])){
                    $sql = 'UPDATE users SET password = :pass WHERE id = :id';
                    $data = array(':pass' => password_hash($new_pass, PASSWORD_DEFAULT), ':id' => $_SESSION['user_id']);
                    $stmt = queryPost($dbh, $sql, $data);

                    header('Location:mypage.php');
                    exit;
                }else{
                    $err_msg['old-pass'] = MSG13;
                }

            }catch (Exception $e){
                debug('エラー発生:'.$e -> getMessage());
                $err_msg['common'] = MSG07;
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
            <h2>パスワード変更</h2>
            <span class="err-msg">
                <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
            <form method="post">
                <p><span class="item">古いパスワード</span>
                    <?php if(!empty($err_msg['old-pass'])) echo $err_msg['old-pass']; ?><br>
                    <input type="password" name="old-pass">
                </p>
                <p><span class="item">新しいパスワード</span>
                    <?php if(!empty($err_msg['new-pass'])) echo $err_msg['new-pass']; ?><br>
                    <input type="password" name="new-pass">
                </p>
                <p><span class="item">新しいパスワード（再入力）</span><br>
                    <input type="password" name="pass-re">
                </p>
                <input type="submit" value="登録する">
            </form>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
