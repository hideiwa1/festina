<header>
    <div class="site-width">
        <h1><a href="index.php">Festina Lente</a></h1>
        <p class="subtitle">駆け出しエンジニアのためのコミュニティサイト</p>
        <nav class="login-char">
            <div class="login">
                <?php
                if(empty($_SESSION['user_id'])){ ?>
                <a href="login.php">
                    <img src="img/silet.png">
                    <div class="login-msg">
                        <span style="font-size: 18px;">ログイン</span><br>
                        新規登録はこちら
                    </div>
                </a>
                <?php }else{
                    $header = getUser($_SESSION['user_id']);
                    if(!empty($header['pic'])){
                        $pic = $header['pic'];
                    }else{
                        $pic = 'img/silet.png';
                    }?>
                <a href="login.php">
                    <img src="<?php echo sanitize($pic); ?>">
                    <div class="login-msg">
                        ようこそ<br>
                        <?php echo sanitize($header['name']); ?>さん<br>
                        <span class="login-mypage">マイページへ</span><br>
                    </div>
                </a>
            </div>
            <div class="logout">
                <a href="logout.php">
                    ログアウト
                </a>
                <?php } ?>
            </div>
        </nav>
    </div>
    <div id="menu">
        <nav class="menu">
            <ul>
                <li><a href="about.php">about</a></li>
                <li><a href="infotop.php">お知らせ</a></li>
                <li><a href="community.php">コミュニティ</a></li>
                <li><a href="">掲示板</a></li>
                <li><a href="">データベース</a></li>
                <li><a href="contact.php">お問い合わせ</a></li>
            </ul>
        </nav>
    </div>
</header>
