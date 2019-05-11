<section class="profile">
    <img src="<?php if(!empty($userdata['pic'])){
    echo sanitize($userdata['pic']);
}else{
    echo 'img/silet.png';}?>">
    <p>
        <?php echo sanitize($userdata['name']); ?> さんのページ
    </p>
    <ul>
        <li><a href="profile.php?c_id=<?php echo $c_id; ?>" <?php if(basename($_SERVER['PHP_SELF'])==='profile.php' ) echo 'class="select"' ; ?>>プロフィール</a></li>
        <li><a href="diarylist.php?c_id=<?php echo $c_id; ?>" <?php if(basename($_SERVER['PHP_SELF'])==='diary.php' || basename($_SERVER['PHP_SELF'])==='diarylist.php' ) echo 'class="select"' ; ?>>日記</a></li>
        <li><a href="eventlist.php?c_id=<?php echo $c_id; ?>" <?php if(basename($_SERVER['PHP_SELF'])==='event.php' || basename($_SERVER['PHP_SELF'])==='eventlist.php' ) echo 'class="select"' ; ?>>イベント</a></li>
    </ul>
</section>
