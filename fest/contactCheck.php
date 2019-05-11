<?php

require('function.php');

debug('<<<お問い合わせ確認ページ>>>');
debugLogstart();

if(!empty($_SESSION['email']) && !empty($_SESSION['comment']) && !empty($_SESSION['category'])){
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $category = $_SESSION['category'];
    $comment = $_SESSION['comment'];
    
    $dbh = dbconnect();
    $sql = 'SELECT name FROM contact_category WHERE id = :id';
    $data = array(':id' => $category);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    $category = array_shift($result);
    
    if($_POST['back']){
        header("Location: contact.php");
    }else if($_POST['submit']){
        if(empty($name)){
            $name = $email;
        }
        try{
            $from = 'info@info.com';
            $to = $email;
            $subject = 'お問い合わせ内容';
            $comment = <<<EOT
{$name}様
お問い合わせいただきありがとうございます。

下記内容にて受付致しました。
お問い合わせ内容：{$category}
{$comment}

EOT;
            sendmail($from, $to, $subject, $comment);
            
            unset($_SESSION['email']);
            unset($_SESSION['name']);
            unset($_SESSION['category']);
            unset($_SESSION['comment']);
            
            header("Location: index.php");
        }catch (Exception $e){
            debut('エラー発生:'.$e -> getMessage());
        }
    }
}else{
    header("Location: index.php");
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
            <h2>お問い合わせ</h2>
            <form method="post">
                <p><span class="item">メールアドレス</span><br>
                    <span>
                        <?php echo getFormdata('email'); ?></span>
                </p>
                <p><span class="item">名前（ハンドルネーム）</span><br>
                    <span>
                        <?php echo getFormdata('name'); ?></span>
                </p>
                <p><span class="item">お問い合わせ内容</span><br>
                    <span>
                        <?php echo $category; ?></span>
                </p>
                <p>
                    <span>
                        <?php echo getFormdata('comment'); ?></span>
                </p>
                <div class="button">
                    <div>
                        <input type="submit" name="back" value="修正する" class="back">
                        <input type="submit" name="submit" value="確認する" class="submit">
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
