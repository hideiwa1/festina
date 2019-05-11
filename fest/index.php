<?php

require('function.php');
debug('<<<トップページ>>>');
debugLogstart();

$span = 5;
$minlist = 0;
$dbInfo = getInfoTop($span, $minlist);
$dbDiary = getDiaryTop($span, $minlist, false);
$dbEvent = getEventTop($span, $minlist, false);

?>

<!DOCTYPE html>
<html lang="ja" ?>

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Kosugi+Maru" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <ul class="slide">
            <li class="s-img">
                <img src="img/writing-828911_1280.jpg" alt="">
                <p>勉強の進捗管理！<br>
                    <span>勉強中に思ったこと、学んだことを記録しよう。<br>
                        ToDoリストでスケジュール管理をしよう。</span>
                </p>
            </li>
            <li class="s-img">
                <img src="img/business-3152586_1280.jpg" alt="">
                <p>仲間と繋がろう！<br>
                    <span>他のエンジニア仲間と交流しよう。<br>
                        自分だけでは得られない気づきや刺激を得られます。</span>
                </p>
            </li>
            <li class="s-img">
                <img src="img/coffee-2242213_1280.jpg" alt="">
                <p>知識の共有を！<br>
                    <span>勉強中に詰まったこと、気付いたことを共有しよう。<br>
                        他のエンジニアの知識を吸収しよう。</span>
                </p>
            </li>
        </ul>
        <ul class="slide-bar">
            <li class="s-button"><i class="fas fa-circle"></i></li>
            <li class="s-button"><i class="fas fa-circle"></i></li>
            <li class="s-button"><i class="fas fa-circle"></i></li>
        </ul>

        <section class="info">
            <h2 class="infotitle"><span class="h2">お知らせ</span></h2>
            <table>
                <?php foreach($dbInfo as $key => $val){ ?>
                <tr>
                    <th><a href="info.php?i_id=<?php echo $val['id']; ?>" class="title">
                            <?php echo sanitize($val['title']); ?></a></th>
                    <td>
                        <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <a href="infotop.php" class="more">さらに見る＞＞</a>
        </section>

        <section class="new">
            <h2><span class="h2">新着情報</span></h2>
            <div class="panel">
                <div class="diary">
                    <h3>日記</h3>
                    <?php foreach($dbDiary as $key => $val){
                    if(!empty($val['pic'])){
                        $pic = $val['pic'];
                    }else{
                        $pic = 'img/silet.png';
                    }?>
                    <div class="block">
                        <a href="diary.php?c_id=<?php echo $val['user_id']; ?>&b_id=<?php echo $val['id']; ?>">
                            <img src="<?php echo sanitize($pic); ?>">
                            <p>
                                <span class="title">
                                    <?php echo sanitize($val['title']); ?></span><br>
                                <span class="name">
                                    <?php echo sanitize($val['name']); ?></span>
                                <span class="date">
                                    <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>


                            </p>
                        </a>
                    </div>
                    <?php } ?>
                    <a href="diaryindex.php" class="more">さらに見る＞＞</a>
                </div>

                <div class="event">
                    <h3>イベント</h3>
                    <?php foreach($dbEvent as $key => $val){
                    if(!empty($val['pic'])){
                        $pic = $val['pic'];
                    }else{
                        $pic = 'img/silet.png';
                    }?>
                    <div class="block">
                        <a href="event.php?c_id=<?php echo $val['user_id']; ?>&e_id=<?php echo $val['id']; ?>">
                            <img src="<?php echo sanitize($pic); ?>">
                            <p><span class="title">
                                    <?php echo sanitize($val['title']); ?></span><br>
                                <span class="name">
                                    <?php echo sanitize($val['name']); ?></span>
                                <span class="date">
                                    <?php echo date('Y/m/d H:i', strtotime(sanitize($val['update_date']))); ?></span>
                            </p>
                        </a>
                    </div>
                    <?php } ?>
                    <a href="eventindex.php" class="more">さらに見る＞＞</a>
                </div>

                <div class="msg">
                    <h3>掲示板</h3>
                    <div class="block">
                        <a href="">
                            <img src="img/silet.png">
                            <p><span class="title">スレッドタイトル</span><br>
                                <span class="name">名前</span>　<span class="date">2019/1/1 23:00</span>
                            </p>
                        </a>
                    </div>
                    <div class="block">
                        <a href="">
                            <img src="img/silet.png">
                            <p><span class="title">スレッドタイトル</span><br>
                                <span class="name">名前</span>　<span class="date">2019/1/1 23:00</span>
                            </p>
                        </a>
                    </div>
                    <div class="block">
                        <a href="">
                            <img src="img/silet.png">
                            <p><span class="title">スレッドタイトル</span><br>
                                <span class="name">名前</span>　<span class="date">2019/1/1 23:00</span>
                            </p>
                        </a>
                    </div>
                    <a href="" class="more">さらに見る＞＞</a>
                </div>

            </div>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
