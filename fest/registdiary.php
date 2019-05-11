<?php

require('function.php');
debug('<<<日記作成・編集ページ>>>');
debugLogstart();

require('auth.php');
debug('POST:'.print_r($_POST, true));

if(!empty($_GET['b_id'])){
    $b_id = $_GET['b_id'];
    $dbFormdata = getBlogdata($b_id, $_SESSION['user_id']);
    if(!empty($_GET['b_id']) && empty($dbFormdata)){
        header("Location: mypage.php");
        exit;
    }else{
        $edit_flg = true;
    }
}else{
    $b_id = '';
    $edit_flg = false;
}
$dbCategory = getCategory();

if(!empty($_POST)){
    
    $title = $_POST['title'];
    $category = $_POST['category'];
    $comment = $_POST['comment'];
    if(!empty($_FILES['pic1']['name'])){
        $pic1 = uploadImg($_FILES['pic1'], 'pic1');
    }else if(!empty($dbFormdata['pic1'])){
        $pic1 = $dbFormdata['pic1'];
    }else if(!empty($_POST['pic1'])){
        $pic1 = $_POST['pic1'];
    }else{
        $pic1 = '';
    }
    if(!empty($_FILES['pic2']['name'])){
        $pic2 = uploadImg($_FILES['pic2'], 'pic2');
    }else if(!empty($dbFormdata['pic2'])){
        $pic2 = $dbFormdata['pic2'];
    }else if(!empty($_POST['pic2'])){
        $pic2 = $_POST['pic2'];
    }else{
        $pic2 = '';
    }
    if(!empty($_FILES['pic3']['name'])){
        $pic3 = uploadImg($_FILES['pic3'], 'pic3');
    }else if(!empty($dbFormdata['pic3'])){
        $pic3 = $dbFormdata['pic3'];
    }else if(!empty($_POST['pic3'])){
        $pic3 = $_POST['pic3'];
    }else{
        $pic3 = '';
    }
    
    validRequire($title, 'title');
    validRequire($comment, 'comment');
    validRequire($category, 'category');
    
    if(empty($err_msg)){
        validMaxlen($title, 'title');
        validMaxlen($comment, 'comment', '500');
        
        if(empty($err_msg)){
            $_SESSION['title'] = $title;
            $_SESSION['category'] = $category;
            $_SESSION['comment'] = $comment;
            $_SESSION['pic1'] = $pic1;
            $_SESSION['pic2'] = $pic2;
            $_SESSION['pic3'] = $pic3;
            $_SESSION['edit_flg'] = $edit_flg;
            $_SESSION['b_id'] = $b_id;
            
            header("Location: registdiaryCheck.php");
            exit;
        }
    }
}else{
    $arr = ['title', 'category', 'comment', 'pic1', 'pic2', 'pic3'];
    sessionToPost($arr);
    debug('session:'.print_r($_SESSION, true));
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
            <h2>
                <?php if(!$edit_flg){
                    echo '日記作成';
                }else{
                    echo '日記編集';
                }?>
            </h2>
            <form method="post" enctype="multipart/form-data">
                <span class="err-msg">
                    <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                </span>
                <p><span class="item">タイトル</span><span class="err-msg">
                        <?php if(!empty($err_msg['title'])) echo $err_msg['title']; ?>
                    </span><br>
                    <input type="text" name="title" value="<?php echo getFormdata('title'); ?>">
                </p>
                <p><span class="item">カテゴリー</span><span class="err-msg">
                        <?php if(!empty($err_msg['category'])) echo $err_msg['category']; ?>
                    </span><br>
                    <select name="category">
                        <option value="0" <?php if(empty(getFormdata('category'))) echo 'selected' ; ?> >選択してください</option>
                        <?php
                        foreach($dbCategory as $key => $val){
                        ?>
                        <option value="<?php echo $val['id'];?>" <?php if(getFormdata('category')===$val['id']) echo 'selected' ; ?> >
                            <?php echo $val['name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </p>
                <p><span class="item">内容</span><span class="err-msg">
                        <?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?>
                    </span><br>
                    <textarea name="comment" class="js-text"><?php echo getFormdata('comment'); ?></textarea>
                </p>
                <p class="counter">
                    <span class="js-count"></span>文字 / 500文字
                </p>
                <p><span class="item">画像</span><span class="err-msg">
                        <?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?>
                    </span></p>
                <div class="img">
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic1'); ?>" alt="">
                        <input type="file" name="pic1" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic2'); ?>" alt="">
                        <input type="file" name="pic2" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                    <div class="pic">
                        <img src="<?php echo getFormdata('pic3'); ?>" alt="">
                        <input type="file" name="pic3" class="input">
                        <span class="drop">ドラッグ＆ドロップ</span>
                    </div>
                </div>
                <input type="hidden" name="pic1" value="<?php echo getFormdata('pic1'); ?>">
                <input type="hidden" name="pic2" value="<?php echo getFormdata('pic2'); ?>">
                <input type="hidden" name="pic3" value="<?php echo getFormdata('pic3'); ?>">
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
