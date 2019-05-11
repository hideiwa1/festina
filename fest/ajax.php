<?php

require('function.php');
debug('<<<Ajax>>>');
debugLogstart();
debug('POST:'.print_r($_POST, true));

if(!empty($_POST['c_club']) && !empty($_POST['c_lesson'])){
    $c_club = $_POST['c_club'];
    $c_lesson = $_POST['c_lesson'];
    if(!empty($_POST['comment'])){
        $comment = $_POST['comment'];
        
        validRequire($comment, 'comment');
        validMaxlen($comment, 'comment');
    }

    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM webukatu_comment WHERE lesson_id = :c_lesson AND club_id = :c_club AND user_id = :u_id';
        $data = array(':c_lesson' => $c_lesson, ':c_club' => $c_club, 'u_id' => $_SESSION['user_id']);
        $stmt = queryPost($dbh, $sql, $data);
        $resultRow = $stmt -> rowCount();

        if(empty($resultRow)){
            if(!isset($_POST['comment'])){
                $sql = 'INSERT INTO webukatu_comment (user_id, lesson_id, club_id, comp_flg, create_date) VALUE (:u_id, :c_lesson, :c_club, :c_flg, :date)';
                $data = array('u_id' => $_SESSION['user_id'], ':c_lesson' => $c_lesson, ':c_club' => $c_club, 'c_flg' => 1, ':date' => date('Y-m-d H:i:s'));
                $stmt = queryPost($dbh, $sql, $data);
            }else{
                $sql = 'INSERT INTO webukatu_comment (user_id, lesson_id, club_id, comment, create_date) VALUE (:u_id, :c_lesson, :c_club, :comment, :date)';
                $data = array('u_id' => $_SESSION['user_id'], ':c_lesson' => $c_lesson, ':c_club' => $c_club, 'comment' => $comment, ':date' => date('Y-m-d H:i:s'));
                $stmt = queryPost($dbh, $sql, $data);
            }
        }else{
            if(!isset($_POST['comment'])){
                $result = $stmt -> fetch(PDO::FETCH_ASSOC);
                if($result['comp_flg'] === '0'){
                    $data = array('u_id' => $_SESSION['user_id'], ':c_lesson' => $c_lesson, ':c_club' => $c_club, ':c_flg' => 1);
                }else{
                    $data = array('u_id' => $_SESSION['user_id'], ':c_lesson' => $c_lesson, ':c_club' => $c_club, ':c_flg' => 0);
                }
                $sql = 'UPDATE webukatu_comment SET comp_flg = :c_flg WHERE lesson_id = :c_lesson AND club_id = :c_club AND user_id = :u_id';
                $stmt = queryPost($dbh, $sql, $data);
            }else{
                $sql = 'UPDATE webukatu_comment SET comment = :comment WHERE lesson_id = :c_lesson AND club_id = :c_club AND user_id = :u_id';
                $data = array('u_id' => $_SESSION['user_id'], ':c_lesson' => $c_lesson, ':c_club' => $c_club, ':comment' => $comment);
                $stmt = queryPost($dbh, $sql, $data);
            }
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

?>
