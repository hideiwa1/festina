<?php

require('function.php');

debug('<<<お問い合わせページ>>>');
debugLogstart();

$dbCategory = getContactCategory();

if(!empty($_POST)){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $comment = $_POST['comment'];
    
    validRequire($email, 'email');
    validRequire($comment, 'comment');
    validRequire($category, 'category');
    
    if(empty($err_msg)){
        validEmail($email, 'email');
        
        validMaxlen($name, 'name', '20');
        
        validMaxlen($comment, 'commnent', '200');
        
        if(empty($err_msg)){
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['category'] = $category;
            $_SESSION['comment'] = $comment;
            
            header("Location: contactCheck.php");
        }
    }
}else{
    if(!empty($_SESSION['email'])){
        $_POST['email'] = $_SESSION['email'];
        unset($_SESSION['email']);
    }
    if(!empty($_SESSION['name'])){
        $_POST['name'] = $_SESSION['name'];
        unset($_SESSION['name']);
    }
    if(!empty($_SESSION['category'])){
        $_POST['category'] = $_SESSION['category'];
        unset($_SESSION['category']);
    }
    if(!empty($_SESSION['comment'])){
        $_POST['comment'] = $_SESSION['comment'];
        unset($_SESSION['comment']);
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
    <link rel="stylesheet" type="text/css" href="contact.css">
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
                <span class="err-msg">
                    <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                </span>
                <p>メールアドレス
                    <span class="err-msg">
                        <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                    </span><br>
                    <input type="text" name="email" value="<?php echo getFormdata('email'); ?>">
                </p>
                <p>名前（ハンドルネーム）<span class="err-msg">
                        <?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?>
                    </span><br>
                    <input type="text" name="name" value="<?php echo getFormdata('name'); ?>">
                </p>
                <p>お問い合わせ内容<span class="err-msg">
                        <?php if(!empty($err_msg['category'])) echo $err_msg['category']; ?>
                    </span><br>
                    <select name="category">
                        <option value="0" <?php if(empty(getFormdata('category'))) echo 'selected' ; ?> >選択してください</option>
                        <?php
                        foreach($dbCategory as $key => $val){
                        ?>
                        <option value="<?php echo $val['id']; ?>" <?php if(getFormdata('category')===$val['id']) echo 'selected' ; ?>>
                            <?php echo $val['name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </p>
                <p><span class="err-msg">
                        <?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?>
                    </span><br>
                    <textarea name="comment" class="js-text"><?php echo getFormdata('comment'); ?></textarea>
                </p>
                <p class="counter">
                    <span class="js-count"></span>文字 / 200文字
                </p>
                <input type="submit" value="確認する">
            </form>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
