<?php

require('function.php');
debug('<<<プロフィール編集>>>');
debugLogstart();

require('auth.php');

$dbFormdata = getUser($_SESSION['user_id']);

if(!empty($_SESSION['lang'])){
    $arr['lang'] = $_SESSION['lang'];
}else{
    if(!empty($dbFormdata['lang'])){
        if(!empty($_POST['lang'])){
            $arr['lang'] = $_POST['lang'];
        }else{
            $arr['lang'] = explode(',', $dbFormdata['lang']);
        }
    }else{
        if(!empty($_POST['lang'])){
            $arr['lang'] = $_POST['lang'];
        }else{
            $arr['lang'] = array();
        }
    }
}

$dblang = getLang();

debug('POST:'.print_r($_POST, true));

if(!empty($_POST)){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $lang = $arr['lang'];
    $comment = $_POST['comment'];
    if(!empty($_FILES['pic']['name'])){
        $pic = uploadImg($_FILES['pic'], 'pic');
    }else if(!empty($dbFormdata['pic'])){
        $pic = $dbFormdata['pic'];
    }else if(!empty($_POST['pic'])){
        $pic = $_POST['pic'];
    }else{
        $pic = '';
    }
    
    validRequire($name, 'name');
    validMaxlen($name, 'name', '20');
    
    validEmail($email, 'email');
    validRequire($email, 'email');
    
    validNum($age, 'age');
    validMaxlen($age, 'age', '3');
    
    validMaxlen($comment, 'comment', '250');
    
    if(empty($err_msg)){
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['age'] = $age;
        $_SESSION['lang'] = $lang;
        $_SESSION['comment'] = $comment;
        $_SESSION['pic'] = $pic;

        header("Location: profileCheck.php");
        exit;
    }
    
}else{
    $array = ['name', 'email', 'age', 'lang', 'comment', 'pic'];
    sessionToPost($array);
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
    <link rel="stylesheet" type="text/css" href="regist.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="main">
            <h2>プロフィール編集</h2>
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            <form method="post" enctype="multipart/form-data">
                <p><span class="item">名前</span>
                    <?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?><br>
                    <input type="text" name="name" value="<?php echo sanitize(getFormdata('name')); ?>">
                </p>
                <p><span class="item">メールアドレス</span>
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?><br>
                    <input type="text" name="email" value="<?php echo sanitize(getFormdata('email')); ?>">
                </p>
                <p><span class="item">年齢</span><br><input type="number" name="age" class="age" value="<?php echo sanitize(getFormdata('age')); ?>">歳
                    <?php if(!empty($err_msg['age'])) echo $err_msg['age']; ?>
                </p>
                <p><span class="item">使用言語</span><br>
                    <?php
                    foreach($dblang as $key => $val){
                    ?>
                    <input type="checkbox" name="lang[]" value="<?php echo $val['id']; ?>" <?php if(in_array($val['id'],$arr['lang'],true)) echo 'checked' ; ?> >
                    <?php echo $val['name']; ?>
                    <?php } ?>
                </p>
                <p><span class="item">自己紹介</span>
                    <?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?><br>
                    <textarea name="comment" class="js-text"><?php echo sanitize(getFormdata('comment')); ?> </textarea>
                </p>
                <p class="counter">
                    <span class="js-count"></span>文字 / 250文字
                </p>
                <p><span class="item">プロフィール画像</span>
                    <?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?>
                </p>
                <div class="img">
                    <div class="pic">
                        <img src="<?php echo sanitize(getFormdata('pic')); ?>">
                        <input type="file" name="pic" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                </div>
                <input type="hidden" name="pic" value="<?php echo getFormdata('pic'); ?>">
                <input type="submit" value="確認する">
            </form>
        </section>

        <?php
        require('side.php');
        ?>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
