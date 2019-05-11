<?php

require('function.php');

debug('<<<ユーザー登録画面>>>');

if(!empty($_POST)){
    debug('post:'.print_r($_POST, true));
    $email = $_POST['email'];
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass-re'];

    validRequire($email, 'email');
    validRequire($name, 'name');
    validRequire($pass, 'pass');

    if(empty($err_msg)){
        validEmail($email, 'email');
        validEmailDup($email, 'email');

        validMaxlen($name, 'name', '20');

        validHalf($pass, 'pass');
        validMinlen($pass, 'pass');
        validMaxlen($pass, 'pass', '20');

        if(empty($err_msg)){
            validPass($pass, $pass_re, 'pass');
            
            if(empty($err_msg)){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['pass'] = $pass;
                
                header("Location:signupCheck.php");
                exit;
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
            <h2>ユーザー登録</h2>
            <form method="post">
                <span class="err-msg">
                    <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                </span>
                <p>メールアドレス<span class="err-msg">
                        <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                    </span><br>
                    <input type="text" name="email" value="<?php echo getFormdata('email'); ?>">
                </p>
                <p>名前（ハンドルネーム）<span class="err-msg">
                        <?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?>
                    </span><br>
                    <input type="text" name="name" value="<?php echo getFormdata('name'); ?>">
                </p>
                <p>パスワード<span class="err-msg">
                        <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                    </span><br>
                    <input type="password" name="pass">
                </p>
                <p>パスワード（再入力）<br>
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
