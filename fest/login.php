<?php

require('function.php');

debug('<<<ログインページ>>>');
debugLogstart();

require('auth.php');
$link = rewriteGet();
$link = str_replace('?backurl=', '', $link);
if(empty($link)){
    $link = 'mypage.php';
}


if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    
    validRequire($email, 'email');
    validRequire($pass, 'pass');
    
    if(empty($err_msg)){
        validEmail($email, 'email');
        
        validHalf($pass, 'pass');
        validMaxlen($pass, 'pass', '20');
        validMinlen($pass, 'pass');
        
        if(empty($err_msg)){
            try{
                $dbh = dbconnect();
                $sql = 'SELECT * FROM users WHERE email = :email AND delete_flg = 0';
                $data = array(':email' => $email);
                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt -> fetch(PDO::FETCH_ASSOC);

                if(password_verify($pass, $result['password'])){
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['login_time'] = time();
                    $limit = 60*60*24;
                    if(empty($_POST['auto-login'])){
                        $_SESSION['login_limit'] = $limit;
                    }else{
                        $limit = $limit * 30;
                        $_SESSION['login_limit'] = $limit;
                    }

                    $sql = 'UPDATE users SET login_date = :l_date WHERE email = :email';
                    $data = array(':l_date' => date('Y-m-d H:i:s'), ':email' => $email);
                    $stmt = queryPost($dbh, $sql, $data);
                    header("Location:$link");
                    exit;
                }else{
                    $err_msg['common'] = MSG09;
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
            <h2>ログインページ</h2>
            <span class="err-msg">
                <?php echo errMsg('common'); ?></span>
            <form method="post">
                <p><span class="item">メールアドレス</span> <span class="err-msg">
                        <?php echo errMsg('email'); ?></span><br>
                    <input type="text" name="email" value="<?php echo getFormdata('email'); ?>">
                </p>
                <p><span class="item">パスワード </span><span class="err-msg">
                        <?php echo errMsg('pass'); ?></span><br>
                    <input type="password" name="pass">
                </p>
                <p><input type="checkbox" name="auto-login">次回から自動でログインする</p>
                <input type="submit" value="ログイン">
            </form>
            <p>会員登録がまだの方は <a href="signup.php" class="link">こちら</a> へ
            </p>
            <p>パスワードをお忘れの方は <a href="passRemaind.php" class="link"> こちら</a>へ</p>
            <p>
                ゲストアカウントでのご利用もいただけます。<br>
                email : guest1@guest.com ~ guest10@guest.com<br>
                password : password
            </p>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
