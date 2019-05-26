<?php

try{
if(!empty($_POST){
    $name = $_POST('name');
    $mail = $_POST('email');
    $text = $_POST('comment');
    
    $from = 'raika.kudo1@gmail.com';
    $to = $mail;
    $subject = 'お問合わせ内容を受け付けました';
    $comment = <<<EOT
{$name}様
お問い合わせいただきありがとうございます。

下記内容にて受付致しました。
お問い合わせ内容：
{$text}

EOT;
    $comment2 = <<<EOT
{$name}様よりお問合わせです。
Email: {$mail}

お問い合わせ内容：
{$text}

EOT;
    
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    
    mb_send_mail($to, $subject, $comment, "FROM:".$from);
    
    mb_send_mail($from, $subject, $comment2, "FROM:".$from);
    
    return true;
});
}catch Exception($e){
    return false;
}

?>
