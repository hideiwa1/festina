<?php

require('function.php');
debug('<<<日記詳細>>>');
debugLogstart();


if(!empty($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}
if(!empty($_GET['c_id'])){
    $c_id = $_GET['c_id'];
}else{
    header("Location: index.php");
    exit;
}
if(!empty($_GET['b_id'])){
    $b_id = $_GET['b_id'];
}else{
    header("Location: index.php");
    exit;
}

$userdata = getUser($c_id);

if($userdata['id'] === $user_id){
    $dbFormdata = getDiaryOne($b_id);
}else{
    $dbFormdata = getDiaryOne($b_id, 'false');
}


if(!empty($_GET['p'])){
    $nowPage = (int)$_GET['p'];
}else{
    $nowPage = 1;
}
$span = 20;
$minlist = ($nowPage-1)*$span;

$dbcomment = getCommentAndUser($b_id, $span, $minlist);

$link = $_SERVER['PHP_SELF'];
$p_link = $link.rewriteGet(array('p'));

//debug('result:'.print_r($dbFormdata, true));


try{
    $dbh = dbconnect();
    if(!empty($dbFormdata['category'])){
        $sql = 'SELECT name FROM category WHERE id = :id';
        $data = array(':id' => $dbFormdata['category']);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        debug('result:'.print_r($result,true));
        $c_name = array_shift($result);
    } else{
        $c_name = '';
    }


    if(!empty($_POST['back'])){
        header("Location:registdiary.php?b_id=".$b_id);
        exit;
    }

    if(!empty($_POST['delete'])){
        $delete = $_POST['delete'];
        $sql = 'UPDATE comment set delete_flg = 1 WHERE id = :id';
        $data = array(':id' => $delete);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            header("Location: #".$delete);
            exit;
        }else{
            $err_msg['common'] = MSG07;
        }
    }
    
    if(!empty($_POST['reply'])){
        $comment = $_POST['comment'];
        $reply = $_POST['reply'];
        $user = $user_id;
        
        validRequire($comment, 'comment');
        validMaxlen($comment, 'comment', '250');
        
        if(empty($err_msg)){
            $sql = 'INSERT INTO comment (index_num, comment, send_user, send_blog, reply_num, create_date) VALUES (:index_num, :comment, :s_user, :s_blog, :reply, :c_date)';
            $data = array(':index_num' => $dbcomment['total']+2, ':comment' => $comment, ':s_user' => $user, ':s_blog' => $b_id, ':reply' => $reply, ':c_date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                header("Location: #".$dbcomment['total']);
                exit;
            }else{
                $err_msg['common'] = MSG07;
            }
        }
    }
    if(!empty($_POST['submit'])){
        $comment = $_POST['comment'];
        $user = $user_id;

        validRequire($comment, 'comment');
        validMaxlen($comment, 'comment', '250');

        if(empty($err_msg)){
            $sql = 'INSERT INTO comment (index_num, comment, send_user, send_blog, create_date) VALUES (:index_num, :comment, :s_user, :s_blog, :c_date)';
            $data = array(':index_num' => $dbcomment['total']+2, ':comment' => $comment, ':s_user' => $user, ':s_blog' => $b_id, ':c_date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                header("Location: #".$dbcomment['total']);
                exit;
            }else{
                $err_msg['common'] = MSG07;
            }
        }
    }
}catch (exception $e){
    debug('エラー発生:'.$e -> getMessage());
    $err_msg['common'] = MSG07;
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
    <link rel="stylesheet" type="text/css" href="diary.css">
    <title>Festina Lente</title>
</head>

<body>
    <?php
    require('header.php');
    ?>



    <main class="site-width">
        <?php
        require('userProfile.php');
        ?>

        <div class="main">
            <section>
                <h2>
                    <?php echo sanitize($dbFormdata['title']); ?>
                    <?php if($c_id === $user_id){
                    if(empty($dbFormdata['open_flg'])){
                    ?>
                    <span class="open">公開</span>
                    <?php }else{ ?>
                    <span class="draft">下書き</span>
                    <?php }
                    } ?><br>
                    <p>
                        <span class="category">
                            <?php echo sanitize($c_name); ?></span>
                        <span class="date">最終更新日：
                            <?php echo date('y/m/d H:i', strtotime($dbFormdata['update_date'])); ?></span>
                    </p>
                    <i class="fas fa-heart"></i>
                </h2>
                <?php if(!empty($dbFormdata['pic1']) || !empty($dbFormdata['pic2']) || !empty($dbFormdata['pic3'])){?>
                <ul class="index-img">
                    <li><img src="<?php if(!empty($dbFormdata['pic1'])) echo sanitize($dbFormdata['pic1']); ?>" alt="" <?php if(empty($dbFormdata['pic1'])) echo 'style="display:none"' ; ?>></li>
                    <li><img src="<?php if(!empty($dbFormdata['pic2'])) echo sanitize($dbFormdata['pic2']); ?>" alt="" <?php if(empty($dbFormdata['pic2'])) echo 'style="display:none"' ; ?>></li>
                    <li><img src="<?php if(!empty($dbFormdata['pic3'])) echo sanitize($dbFormdata['pic3']); ?>" alt="" <?php if(empty($dbFormdata['pic3'])) echo 'style="display:none"' ; ?>></li>
                </ul>
                <div class="bar">
                    <ul class="img-bar">
                        <li <?php if(empty($dbFormdata['pic1'])) echo 'style="display:none"' ; ?>><i class="fas fa-circle"></i></li>
                        <li <?php if(empty($dbFormdata['pic2'])) echo 'style="display:none"' ; ?>><i class="fas fa-circle"></i></li>
                        <li <?php if(empty($dbFormdata['pic3'])) echo 'style="display:none"' ; ?>><i class="fas fa-circle"></i></li>
                    </ul>
                </div>
                <?php } ?>
                <p>
                    <?php echo sanitize($dbFormdata['comment']); ?>
                </p>
                <?php if($c_id === $user_id){
                ?>
                <div class="edit">
                    <form method="post" class="block">
                        <button type="submit" name="back" value="back" class="back">修正する</button>
                    </form>
                    <button name="erase" value="erase" class="erase js-modalopen" data-target="js-modal-e">削除する</button>
                    <div id="js-modal-e" class="js-modal">
                        <h2>このイベントを削除します</h2>
                        <form method="post">
                            <div class="button">
                                <button type="submit" name="erase" value="erase">削除する</button>
                                <button>キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php } ?>
            </section>

            <section>
                <h3><span class="h3">コメント</span></h3>
                <?php 
                if(!empty($dbcomment['total'])){
                    foreach($dbcomment['data'] as $key => $val){
                        if(!empty($val['pic'])){
                            $pic = $val['pic'];
                        }else{
                            $pic = 'img/silet.png';
                        }
                        ?>
                <div class="comment" id="id-<?php echo $val['index_num']; ?>">

                    <?php
                        if(!empty($val['delete_flg'])){
                            ?>
                    <p class="msg">
                        <?php echo 'コメントは削除されました'; ?>
                    </p>
                    <?php }else{ ?>
                    <div class="user">
                        <p>
                            <?php echo $val['index_num']; ?>
                        </p>
                        <img src="<?php echo $pic; ?>">
                        <p>
                            <?php echo sanitize($val['name']); ?><br>
                            投稿日：
                            <?php echo sanitize(date('Y/m/d H:i', strtotime($val['update_date']))); ?>
                        </p>
                    </div>
                    <p class="msg">
                        <?php
                            if(!empty($val['reply_num'])){
                                echo '<a href="#id-'.sanitize($val['reply_num']).'">&gt;&gt;'.$val['reply_num'].'</a>';
                            }?>
                        <?php echo sanitize($val['comment']); ?>
                    </p>
                    <div class="button">
                        <?php if(!empty($user_id)){ ?>
                        <?php
                            if($val['user_id'] === $user_id){
                        ?>
                        <button type="button" class="delete js-modalopen" data-target="js-modal-d-<?php echo $val['index_num']; ?>">削除する</button>
                        <div id="js-modal-d-<?php echo $val['index_num'];?>" class="js-modal">
                            <h2>以下のコメントを削除します</h2>
                            <p>
                                <?php echo sanitize($val['comment']); ?>
                            </p>
                            <form method="post">
                                <div class="button">
                                    <button type="submit" name="delete" value="<?php echo $val['id']; ?>" class="delete">削除する</button>
                                    <button class="back">キャンセル</button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>

                        <button type="button" class="reply js-modalopen" data-target="js-modal-r-<?php echo $val['id'];?>">返信する</button>
                        <div id="js-modal-r-<?php echo $val['index_num'];?>" class="js-modal">
                            <h2>返信する</h2>
                            <p class="modal-comment">＜返信元＞<br>
                                <?php echo sanitize($val['comment']); ?>
                            </p>
                            <form method="post">
                                <textarea name="comment" class="js-text"></textarea>
                                <p class="counter">
                                    <span class="js-count"></span>文字 / 250文字
                                </p>
                                <div class="button">
                                    <button type="submit" name="reply" value="<?php echo $val['id']; ?>" class="reply">返信する</button>
                                    <button class="back">キャンセル</button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <?php
                    }
                }
                ?>
                <?php
                pagenation($nowPage, $dbcomment['total_page'], '20', $p_link); 
                ?>
            </section>

            <?php
            if(!empty($user_id)){
            ?>
            <section class="form">
                <form method="post">
                    <p id="comment">
                        <textarea name="comment" class="js-text"></textarea>
                    </p>
                    <p class="counter">
                        <span class="js-count"></span>文字 / 250文字
                    </p>
                    <input type="submit" name="submit" value="発言する" class="submit">
                </form>
            </section>
            <?php }else{ ?>
            <section>
                <p class="not-login">
                    コメントにはログインが必要です。<br>
                    <button><a href="login.php?backurl=<?php echo sanitize($link.rewriteGet()); ?>">ログイン</a></button>
                </p>
            </section>
            <?php } ?>
        </div>

    </main>

    <?php
    require('footer.php');
    ?>

</body>



</html>
