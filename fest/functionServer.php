<?php
error_reporting(E_ALL);
ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

$debug_flg = true;
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}

//session_save_path('/var/tmp/');
ini_set('session.gc_maxlifetime', 60*60*24*30);
ini_set('session.cookie_lifetime', 60*60*24*30);
session_start();
session_regenerate_id();

function debugLogstart(){
    debug('セッションID:'.session_id());
    debug('$_SESSION:'.print_r($_SESSION, true));
}

$err_msg = array();

define('MSG01', '入力必須項目です');
define('MSG02', 'メールアドレス形式で入力してください');
define('MSG03', '文字以下で入力してください');
define('MSG04', '半角英数字で入力してください');
define('MSG05', '文字以上で入力してください');
define('MSG06', 'パスワード（再入力）と一致しません');
define('MSG07', 'エラーが発生しました、時間を空けてお試しください');
define('MSG08', 'そのメールアドレスはすでに登録されています');
define('MSG09', 'メールアドレス又はパスワードが違います');
define('MSG10', '認証キーが正しくありません');
define('MSG11', '有効期限が切れています');
define('MSG12', '半角数字で入力してください');
define('MSG13', 'パスワードが違います');

function sanitize($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

function errMsg($key){
    global $err_msg;
    if(!empty($err_msg['common'])){
        return sanitize($err_msg['common']);
    }
}

function getFormdata($key, $flg = false){
    global $dbFormdata;
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    if(!empty($_SESSION[$key])){
        return sanitize($_SESSION[$key]);
    }else{
        if(!empty($dbFormdata[$key])){
            if(isset($method[$key])){
                return sanitize($method[$key]);
            }else{
                return sanitize($dbFormdata[$key]);
            }
        }else{
            if(isset($method[$key])){
                return sanitize($method[$key]);
            }
        }
    }
}


function validRequire($str, $key){
    if(empty($str)){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}

function validMaxlen($str, $key, $len = 255){
    if(mb_strlen($str) > ($len + 1)){
        global $err_msg;
        $err_msg[$key] = $len.MSG03;
    }
}

function validHalf($str, $key){
    if(!preg_match("/^[0-9a-zA-Z]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}

function validMinlen($str, $key, $len = 6){
    if(mb_strlen($str) < ($len + 1)){
        global $err_msg;
        $err_msg[$key] = $len.MSG05;
    }
}
function validPass($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}

function validNum($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG12;
    }
}

function validEmailDup($str, $key){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT email FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $str);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if($result){
            global $err_msg;
            $err_msg[$key] = MSG08;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function uploadImg($file, $key){
    try{
        $type = @exif_imagetype($file['tmp_name']);
        if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
            throw new RuntimeException('画像が未対応です。');
        }
        $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
        if(!move_uploaded_file($file['tmp_name'], $path)){
            throw new RuntimeException('ファイル保存時にエラーが発生しました');
        }
        chmod($path, 0644);
        return $path;
    }catch (RunteimeException $e){
        debug('エラー発生:'.$e -> getMessage());
        global $err_msg;
        $err_msg[$key] = MSG07;
    }
}

function dbconnect(){
    $dsn = 'mysql:dbname=festina_fest; host=mysql1.php.xdomain.ne.jp; charset=utf8';
    $user = 'festina_fest';
    $password = 'password';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}

function queryPost($dbh, $sql, $data){
    $stmt = $dbh -> prepare($sql);
    if(!$stmt -> execute($data)){
        global $err_msg;
        $err_msg['common'] = MSG07;
        return 0;
    }else{
        return $stmt;
    }
}

function getContactCategory(){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM contact_category';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        if($result){
            return $result;
        }else{
            return false;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getBlogdata($id, $u_id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM blog WHERE id = :id AND user_id = :u_id AND delete_flg = 0';
        $data = array(':id' => $id, 'u_id' => $u_id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return false;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getCategory(){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM category';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        if($result){
            return $result;
        }else{
            return false;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function randKey($len = 10){
    return bin2hex(random_bytes($len));
}

function sendmail($from, $to, $subject, $comment){
    if(!empty($from) && !empty($to) && !empty($subject) && !empty($comment)){
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        
        $result = mb_send_mail($from, $to, $subject, $commnet);
        if($result){
            debug('メールを送信しました');
        }else{
            debug('メールの送信に失敗しました');
        }
    }
}

function sessionToPost($arr){
    foreach($arr as $key){
        if(!empty($_SESSION[$key])){
            $_POST[$key] = $_SESSION[$key];
        }
        unset($_SESSION[$key]);
    }
}

function unsetSession($arr){
    foreach($arr as $key){
        unset($_SESSION[$key]);
    }
}

function getDiary($id, $span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM blog WHERE user_id = :u_id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);
        
        $sql = 'SELECT * FROM blog WHERE user_id = :u_id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();
        
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getDiaryAll($str, $category, $span, $minlist, $flg = true){
    try{
        $str = '%'.$str.'%';
        $dbh = dbconnect();
        $sql = 'SELECT id FROM blog WHERE title LIKE :key AND delete_flg = 0';
        if(!empty($category)){
            $sql .= ' AND category = :c_id';
            $data = array(':key' => $str, ':c_id' => $category);
        }else{
            $data = array(':key' => $str);
        }
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql ='SELECT b.id, b.title, b.comment, b.user_id, b.update_date, u.name FROM blog AS b LEFT JOIN users AS u ON b.user_id = u.id WHERE b.title LIKE :key AND b.delete_flg = 0';
        if(!empty($category)){
            $sql .= ' AND category = :c_id';
            $data = array(':key' => $str, ':c_id' => $category);
        }else{
            $data = array(':key' => $str);
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEventAll($str, $category, $span, $minlist, $flg = true){
    try{
        $str = '%'.$str.'%';
        $dbh = dbconnect();
        $sql = 'SELECT id FROM event WHERE title LIKE :key AND delete_flg = 0';
        if(!empty($category)){
            $sql .= ' AND category = :c_id';
            $data = array(':key' => $str, ':c_id' => $category);
        }else{
            $data = array(':key' => $str);
        }
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql ='SELECT e.id, e.title, e.body, e.start_date, e.end_time, e.limit_date, e.user_id, e.update_date, u.name FROM event AS e LEFT JOIN users AS u ON e.user_id = u.id WHERE e.title LIKE :key AND e.delete_flg = 0';
        if(!empty($category)){
            $sql .= ' AND category = :c_id';
            $data = array(':key' => $str, ':c_id' => $category);
        }else{
            $data = array(':key' => $str);
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getUserAll($str, $span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM users WHERE name LIKE :key AND delete_flg = 0';
        $str = '%'.$str.'%';
        $data = array(':key' => $str);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql ='SELECT id, name, pic, comment FROM users WHERE name LIKE :key AND delete_flg = 0';
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $str = '%'.$str.'%';
        $data = array(':key' => $str);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function pagenation($nowPage, $totalPage, $pageSpan=5, $link=''){
    if($nowPage === 1 && $totalPage >= $pageSpan){
        $minPage = 1;
        $maxPage = 5;
    }else if($nowPage === 2 && $totalPage >= $pageSpan){
        $minPage = 1;
        $maxPage = 5;
    }else if($nowPage === ($totalPage - 1) && $totalPage > $pageSpan){
        $minPage = $nowPage - 3;
        $maxPage = $totalPage;
    }else if($nowPage === $totalPage && $totalPage > $pageSpan){
        $minPage = $nowPage - 4;
        $maxPage = $totalPage;
    }else if($totalPage < $pageSpan){
        $minPage = 1;
        $maxPage = $totalPage;
    }else{
        $minPage = $nowPage - 2;
        $maxPage = $nowPage + 2;
    }
    
    echo '<div class="pagebox">';
    echo '<ul class="pagelist">';
    for($i = $minPage; $i <= $maxPage; $i++){
        if($i === $nowPage){
            echo '<li class="now">'.$i.'</li>';
        }else{
            echo '<li><a href="'.$link.'?p='.$i.'">'.$i.'</a></li>';
        }
    }
    echo '</ul>';
    echo '</div>';
}

function getUser($id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM users WHERE id = :id AND delete_flg = 0';
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getDiaryOne($id, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM blog WHERE id = :id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getCommentAndUser($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM comment WHERE send_blog = :b_id AND delete_flg = 0';
        $data = array(':b_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);
        
        $sql = 'SELECT c.id, index_num, c.comment, send_blog, reply_num, c.update_date, c.delete_flg,u.id AS user_id, u.name, u.pic FROM comment AS c LEFT JOIN users AS u ON c.send_user = u.id WHERE send_blog = :b_id ORDER BY update_date ASC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':b_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
        return false;
    }
}

function getLang(){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM language';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        if($result){
            return $result;
        }else{
            return false;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getInfoAll($span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM info WHERE delete_flg = 0';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql = 'SELECT * FROM info WHERE delete_flg = 0';
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getInfo($id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM info WHERE id = :id AND delete_flg = 0';
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEventdata($id, $u_id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM event WHERE id = :id AND user_id = :u_id AND delete_flg = 0';
        $data = array(':id' => $id, 'u_id' => $u_id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return false;
        }
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEvent($id, $span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM event WHERE user_id = :u_id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql = 'SELECT * FROM event WHERE user_id = :u_id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getAttender($id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM attender WHERE event_id = :id AND delete_flg = 0';
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> rowCount();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEventOne($id, $flg = true){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM event WHERE id = :id AND delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getAttenderAndUser($id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT a.user_id, a.event_id , u.name, u.pic FROM attender AS a LEFT JOIN users AS u ON a.user_id = u.id WHERE a.event_id = :id AND a.delete_flg = 0';
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getE_CommentAndUser($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM event_comment WHERE send_event = :e_id AND delete_flg = 0';
        $data = array(':e_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql = 'SELECT c.id, index_num, c.comment, send_event, c.update_date, c.delete_flg,u.id AS user_id, u.name, u.pic FROM event_comment AS c LEFT JOIN users AS u ON c.send_user = u.id WHERE send_event = :e_id ORDER BY update_date ASC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':e_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
        return false;
    }
}

function rewriteGet($arr = array(), $flg = true){
    if(!empty($_GET)){
        if($flg){
            $str = '?';
        }else{
            $str = '&';
        }
        foreach($_GET as $key => $val){
            if(!in_array($key, $arr, true)){
                $str .= $key .'='.$val.'&';
            }
        }
        $str = mb_substr($str, 0, -1, "UTF-8");
        return $str;
    }
}

function getInfoTop($span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM info WHERE delete_flg = 0';
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}
function getDiaryTop($span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql ='SELECT b.id, b.title, b.comment, b.user_id, b.update_date, u.name, u.pic FROM blog AS b LEFT JOIN users AS u ON b.user_id = u.id WHERE b.delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEventTop($span, $minlist, $flg = true){
    try{
        $dbh = dbconnect();
        $sql ='SELECT e.id, e.title, e.body, e.start_date, e.end_time, e.limit_date, e.user_id, e.update_date, u.name FROM event AS e LEFT JOIN users AS u ON e.user_id = u.id WHERE e.delete_flg = 0';
        if(!$flg){
            $sql .= ' AND open_flg = 0';
        }
        $sql .= ' ORDER BY update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}
function getCommentAndDiary($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT c.index_num, c.comment, c.update_date, b.id, b.title, b.user_id FROM comment AS c LEFT JOIN blog AS b ON c.send_blog = b.id WHERE b.user_id = :u_id AND c.delete_flg = 0 ORDER BY c.update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getEventAndAttender($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT e.id, e.title, e.start_date, e.user_id, e.update_date FROM event AS e LEFT JOIN attender AS a ON e.id = a.event_id WHERE a.user_id = :u_id AND a.delete_flg = 0 ORDER BY e.update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getTodo($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM todo WHERE user_id = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);

        $sql = 'SELECT * FROM todo WHERE user_id = :u_id AND delete_flg = 0';
        $sql .= ' ORDER BY limit_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();

        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getCommentForDiary($id, $span, $minlist){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT id FROM comment WHERE send_user = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt -> rowCount();
        $result['total_page'] = ceil($result['total']/$span);
        
        $sql = 'SELECT c.index_num, c.comment, c.send_user, c.update_date, b.id, b.title, b.user_id FROM comment AS c LEFT JOIN blog AS b ON c.send_blog = b.id WHERE c.send_user = :u_id AND c.delete_flg = 0 ORDER BY c.update_date DESC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$minlist;
        $data = array(':u_id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getClub(){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM webukatu_club';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getLesson(){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM webukatu_lesson';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}

function getWebComment($id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM webukatu_comment WHERE user_id = :id';
        $data = array(':id' => $id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt -> fetchAll();
        return $result;
    }catch (Exception $e){
        debug('エラー発生:'.$e -> getMessage());
    }
}
