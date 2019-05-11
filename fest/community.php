<?php

require('function.php');
debug('<<<コミュニティ検索ページ>>>');
debugLogstart();

$dbh = dbconnect();
$dbCategory = getCategory();

if(!empty($_GET['keyword'])){
    $keyword = $_GET['keyword'];
}else{
    $keyword = '';
}
if(!empty($_GET['category'])){
    $category = $_GET['category'];
}else{
    $category = '';
}
if(!empty($_GET['tab'])){
    $tab = $_GET['tab'];
}else{
    $tab = array();
}
    
if(!empty($_GET)){
    validRequire($tab, 'tab');
    
    if(empty($err_msg)){
        $span = 10;
        $minlist = 0;
        try{
            if(in_array('1', $tab, true)){
                $dbDiary = getDiaryAll($keyword, $category, $span, $minlist);
                debug('$dbDiary:'.print_r($dbDiary, true));
            }
            if(in_array('2', $tab, true)){
                $dbEvent = getEventAll($keyword, $category, $span, $minlist);
                debug('$dbEvent:'.print_r($dbEvent, true));
            }
            if(in_array('3', $tab, true)){
                $dbUser = getUserAll($keyword, $span, $minlist);
                debug('$dbUser:'.print_r($dbUser, true));
            }
        }catch (Exception $e){
            debug('エラー発生:'.$e -> getMessage());
            $err_msg['common'] = MSG07;
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
    <link rel="stylesheet" type="text/css" href="community.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="serch">
            <form method="get">
                <h2>コミュニティ検索</h2>
                <p>キーワード
                    <input type="text" name="keyword" value="<?php if(!empty($_GET['keyword'])) echo sanitize($_GET['keyword']); ?>">
                </p>
                <p>カテゴリー<span class="err-msg">
                        <?php if(!empty($err_msg['category'])) echo $err_msg['category']; ?>
                    </span>
                    <select name="category">
                        <option value="0" <?php if(empty($category)) echo 'selected' ; ?> >選択してください</option>
                        <?php
                        foreach($dbCategory as $key => $val){
                        ?>
                        <option value="<?php echo $val['id'];?>" <?php if($category===$val['id']) echo 'selected' ; ?> >
                            <?php echo $val['name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </p>
                <p>検索対象
                    <input type="checkbox" name="tab[]" value="1" <?php if(in_array('1', $tab)) echo "checked" ;?> >日記
                    <input type="checkbox" name="tab[]" value="2" <?php if(in_array('2', $tab)) echo "checked" ;?>>イベント
                    <input type="checkbox" name="tab[]" value="3" <?php if(in_array('3', $tab)) echo "checked" ;?>>ユーザー
                </p>
                <button type="submit">検索する</button>
            </form>
        </section>

        <section>
            <h3>検索結果</h3>

            <?php if(!empty($dbDiary)){?>
            <div class="tag"><span class="tag-p">日記一覧</span>
                <div class="number">
                    <span class="total">全
                        <?php echo $dbDiary['total']; ?>件</span>
                    <span class="page">
                        <?php echo ($minlist+1); ?> -
                        <?php echo (($minlist + $span) > $dbDiary['total'])? $dbDiary['total'] : ($minlist + $span) ; ?> 件 /
                        <?php echo $dbDiary['total']; ?>件</span>
                </div>
            </div>

            <?php
                foreach($dbDiary['data'] as $key => $val){
                ?>
            <p class="result diary">
                <a href="diary.php?c_id=<?php echo sanitize($val['user_id']); ?>&b_id=<?php echo sanitize($val['id']); ?>">
                    <img src="<?php if(!empty($val['pic'])){
                    echo sanitize($val['pic']);
                }else{
                    echo 'img/silet.png';}?>">
                    <span class="title">
                        <?php echo sanitize($val['title']); ?></span><br>
                    <?php echo mb_substr(sanitize($val['comment']), 0, 20); ?><br>
                    <span class="user">
                        <?php echo sanitize($val['name']); ?> 更新日：
                        <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                </a>
            </p>
            <?php } ?>
            <div class="clear"></div>
            <p class="more">
                <a href="diaryindex.php<?php echo rewriteGet(array('tab')); ?>">さらに見る＞＞</a>
            </p>


            <?php } ?>

            <?php if(!empty($dbEvent['data'])){?>
            <div class="tag"><span class="tag-p">イベント一覧</span>
                <div class="number">
                    <span class="total">全
                        <?php echo $dbEvent['total']; ?>件</span>
                    <span class="page">
                        <?php echo ($minlist+1); ?> -
                        <?php echo (($minlist + $span) > $dbEvent['total'])? $dbEvent['total'] : ($minlist + $span) ; ?> 件 /
                        <?php echo $dbEvent['total']; ?>件</span>
                </div>
            </div>
            <div>
                <?php
                foreach($dbEvent['data'] as $key => $val){
                ?>
                <p class="result diary">
                    <a href="event.php?c_id=<?php echo sanitize($val['user_id']); ?>&e_id=<?php echo sanitize($val['id']); ?>">
                        <img src="<?php if(!empty($val['pic'])){
                    echo sanitize($val['pic']);
                }else{
                    echo 'img/silet.png';}?>">
                        <span class="title">
                            <?php echo sanitize($val['title']); ?></span><br>
                        開催日時：
                        <?php echo date('Y/m/d H:i', strtotime(sanitize($val['start_date']))); ?> ~
                        <?php echo date('H:i', strtotime(sanitize($val['end_time']))); ?><br>
                        参加期日：
                        <?php echo date('Y/m/d H:i', strtotime(sanitize($val['limit_date']))); ?><br>
                        <span class="user">
                            <?php echo sanitize($val['name']); ?> 更新日：
                            <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                    </a>
                </p>
                <?php } ?>
            </div>
            <div class="clear"></div>
            <p class="more">
                <a href="eventindex.php<?php echo rewriteGet(array('tab')); ?>">さらに見る＞＞</a>
            </p>

            <?php } ?>

            <?php if(!empty($dbUser['data'])){?>
            <div class="tag"><span class="tag-p">ユーザー一覧</span>
                <div class="number">
                    <span class="total">全
                        <?php echo $dbUser['total']; ?>件</span>
                    <span class="page">
                        <?php echo ($minlist+1); ?> -
                        <?php echo (($minlist + $span) > $dbUser['total'])? $dbUser['total'] : ($minlist + $span) ; ?> 件 /
                        <?php echo $dbUser['total']; ?>件</span>
                </div>
            </div>
            <div style="overflow: hidden;">
                <?php foreach($dbUser['data'] as $key => $val){
                ?>
                <p class="result userProfile">
                    <a href="profile.php?c_id=<?php echo sanitize($val['id']); ?>">
                        <img src="<?php if(!empty($val['pic'])){
                        echo sanitize($val['pic']);
                        }else{
                        echo 'img/silet.png';}?>">
                        <span class="profile">
                            <?php echo sanitize($val['name']); ?><br>
                            <?php echo mb_substr(sanitize($val['comment']), 0, 20); ?>
                        </span>
                    </a>
                </p>
                <?php } ?>
            </div>
            <p>
                <a href="userindex.php<?php echo rewriteGet(array('tab', 'category')); ?>">さらに見る＞＞</a>
            </p>

            <?php } ?>

        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
