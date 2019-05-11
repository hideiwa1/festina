<?php

require('function.php');
debug('<<<マイページ>>>');
debugLogstart();

require('auth.php');

$span = 5;
$minlist = 0;
$dbDiary = getDiary($_SESSION['user_id'], $span, $minlist);
$dbComment = getCommentForDiary($_SESSION['user_id'], $span, $minlist);
$dbEvent = getEvent($_SESSION['user_id'], $span, $minlist);
$dbAttend = getEventAndAttender($_SESSION['user_id'], $span, $minlist);
$dbTodo = getTodo($_SESSION['user_id'], $span, $minlist);

debug('todo:'.print_r($dbTodo, true));

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
            <div class="list">
                <h2><span class="h2">ToDoリスト</span></h2>
                <ul class="todo">
                    <?php if(empty($dbTodo['data'])){
                        echo '<li>登録されているToDoリストはありません</li>';
                    }else{
                        foreach($dbTodo['data'] as $key => $val){ ?>
                    <li><a href="todo.php?t_id=<?php echo $val['id']; ?>">
                            <span class="title">
                                <?php echo sanitize($val['text']); ?></span><br>
                            <span class="user">期日：
                                <?php echo date('Y/m/d H:i', strtotime(sanitize($val['limit_date']))); ?></span></a></li>
                    <?php }
                    } ?>
                </ul>
                <p class="end"><a href="todo.php">ToDoリスト一覧へ>></a></p>
            </div>

            <div class="list">
                <h2><span class="h2">最近のコメント</span></h2>
                <ul class="comment">
                    <?php if(empty($dbComment['data'])){
                        echo '<li>コメントした日記はありません</li>';
                    }else{
                    foreach($dbComment['data'] as $key => $val){ ?>
                    <li><a href="diary.php?c_id=<?php echo $val['user_id']; ?>&b_id=<?php echo $val['id']; ?>#id-<?php echo $val['index_num']; ?>">
                            <span class="title">
                                <?php echo sanitize($val['title']); ?></span><br>
                            <?php echo sanitize($val['comment']); ?>
                            <span class="user">更新日：
                                <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span></a></li>
                    <?php }
                     } ?>
                </ul>
            </div>

            <div class="list">
                <h2><span class="h2">最近の日記</span></h2>
                <ul class="diary">
                    <?php if(empty($dbDiary['data'])){
                        echo '<li>作成した日記はありません</li>';
                    }else{
                    foreach($dbDiary['data'] as $key => $val){ ?>
                    <li><a href="diary.php?c_id=<?php echo $val['user_id']; ?>&b_id=<?php echo $val['id']; ?>">
                            <span class="title">
                                <?php echo sanitize($val['title']); ?></span><br>
                            <?php echo sanitize($val['comment']); ?>
                            <span class="user">更新日：
                                <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span></a></li>
                    <?php }
                    } ?>
                </ul>
                <p class="end"><a href="mydiary.php">日記一覧へ>></a></p>
            </div>

            <div class="list">
                <h2><span class="h2">最近参加したイベント</span></h2>
                <ul class="event">
                    <?php if(empty($dbAttend)){
                        echo '<li>参加したイベントはありません</li>';
                    }else{
                     foreach ($dbAttend as $key => $val){ ?>
                    <li>
                        <a href="event.php?c_id=<?php echo $val['user_id']; ?>&e_id=<?php echo $val['id']; ?>">
                            <span class="title">
                                <?php echo sanitize($val['title']); ?></span><br>
                            開催日：
                            <?php echo date('Y/m/d H:i', strtotime(sanitize($val['start_date']))); ?> ~
                            <span class="user">更新日：
                                <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                        </a>
                    </li>
                    <?php }
                    } ?>
                </ul>
            </div>

            <div class="list">
                <h2><span class="h2">最近開催したイベント</span></h2>
                <ul class="event">
                    <?php if(empty($dbEvent['data'])){
                        echo '<li>参加したイベントはありません</li>';
                    }else{
                     foreach ($dbEvent['data'] as $key => $val){ ?>
                    <li>
                        <a href="event.php?c_id=<?php echo $val['user_id']; ?>&e_id=<?php echo $val['id']; ?>">
                            <span class="title">
                                <?php echo sanitize($val['title']); ?></span><br>
                            開催日：
                            <?php echo date('Y/m/d H:i', strtotime(sanitize($val['start_date']))); ?> ~
                            <span class="user">更新日：
                                <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                        </a>
                    </li>
                    <?php }
                    } ?>
                </ul>
                <p class="end"><a href="event.php">イベント一覧へ>></a></p>
            </div>
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
