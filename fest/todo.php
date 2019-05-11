<?php

require('function.php');
debug('<<<ToDoページ>>>');
debugLogstart();

require('auth.php');

if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}

$span = 20;
$minlist = ($nowPage-1)*$span;
$dbFormdata = getTodo($_SESSION['user_id'], $span, $minlist);
$link = 'todo.php';
$link .= rewriteGet(array('p'));
if(!empty($dbFormdata['data'])){
    if($nowPage > $dbFormdata['total_page'] || empty($nowPage)){
        header('Location: myevent.php');
        exit;
    }
}

if(!empty($_POST)){
    $text = $_POST['text'];
    $limit = $_POST['limit_date'];
    
    validRequire($text, 'text');
    validMaxlen($text, 'text');
    
    if(empty($err_msg)){
        try{$dbh = dbconnect();
            $sql = 'INSERT INTO todo (text, limit_date, user_id, create_date) VALUES (:text, :limit, :id, :date)';
            $data = array(':text' => $text, ':limit' => $limit, ':id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                header("Location:todo.php");
                exit;
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
    <link rel="stylesheet" type="text/css" href="mypage.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="main">
            <h2>
                <span class="h2">ToDoリスト</span>
                <div class="number">
                    <?php foreach($dbFormdata['data'] as $key => $val){ ?>
                    <span class="total">全
                        <?php echo sanitize($dbFormdata['total']); ?>件</span>
                    <span class="page">
                        <?php echo ($minlist+1); ?> -
                        <?php echo (($minlist + $span) > $dbFormdata['total'])? $dbFormdata['total'] : ($minlist + $span) ; ?> 件 /
                        <?php echo $dbFormdata['total']; ?>件</span>
                </div>
            </h2>
            <form method="post">
                <p>内容
                    <input type="text" name="text">
                </p>
                <p>期日
                    <input type="datetime-local" step="3600" name="limit_date">まで
                </p>
                <input type="submit" value="登録する">
            </form>
            <p class="result">
                <span class="title">
                    <?php echo sanitize($val['text']); ?></span><br>
                <span class="limit">期日：
                    <?php echo date('Y/m/d H:i', strtotime(sanitize($val['limit_date']))); ?></span>
                <button class="button">削除する</button>
            </p>
            <?php } ?>

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
