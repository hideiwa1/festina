<?php

require('function.php');
debug('<<<退会ページ>>>');
debugLogstart();

require('auth.php');

if(!empty($_POST['withdrow'])){
    try{
        $dbh = dbconnect();
        $sql = 'UPDATE users SET delete_flg = :n WHERE id = :id';
        $data = array(':n' => 1, ':id' => $_SESSION['user_id']);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            header("Location:index.php");
            exit;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
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
    <link rel="stylesheet" type="text/css" href="withdrow.css">
    <title>Festina Lente</title>
</head>

<body>

    <?php
    require('header.php');
    ?>

    <main class="site-width">
        <section class="form">
            <h2>退会ページ</h2>
            <form method="post">
                <button type="button" class="delete js-modalopen" data-target="js-modal" value="withdrow">退会する</button>
                <div id="js-modal" class="js-modal">
                    <h3>退会する</h3>
                    <p>
                        退会後はデータの復旧ができません。よろしいですか？
                    </p>
                    <form method="post">
                        <div class="button">
                            <button type="submit" name="delete" value="withdrow" class="delete">退会する</button>
                            <button class="back">キャンセル</button>
                        </div>
                    </form>
                </div>
            </form>
        </section>
    </main>

    <?php
    require('footer.php');
    ?>

</body>

</html>
