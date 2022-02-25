<?php

$toppage = "./mk.html";



#入力情報受け取り
$requirements=$_POST["requirements"];
$name=$_POST["name"];
$mail=$_POST["mail"];
$comment=$_POST["comment"];

#無効化
$requirements=htmlentities($requirements,ENT_QUOTES,"UTF-8");
$name=htmlentities($name,ENT_QUOTES,"UTF-8");
$mail=htmlentities($mail,ENT_QUOTES,"UTF-8");
$comment=htmlentities($comment,ENT_QUOTES,"UTF-8");

#改行処理
$requirements=str_replace("\r\n","","$requirements");
$name=str_replace("\r\n","","$name");
$mail=str_replace("\r\n","","$mail");
$comment=str_replace("\r\n","<br>","$comment");
$comment=str_replace("\r","<br>","$comment");
$comment=str_replace("\n","<br>","$comment");

$mailto = "kawa-masa1974@outlook.jp";


#入力チェック
if($requirements==""){
error("要件を選択して下さい。");
}

if($name==""){
error("名前を入力して下さい。");
}

if($mail==""){
error("メールアドレスを入力して下さい。");
}

if($comment==""){
error("コメントを入力して下さい。");
}

#分岐チェック
if($_POST["mode"]=="post"){conf_form();}
else if($_POST["mode"]=="send"){send_form();}


#確認画面
function conf_form(){

global $requirements;
global $name;
global $mail;
global $comment;

#テンプレートの読み込み。
$conf=fopen("tmp/conf.tmpl","r");
$size=filesize("tmp/conf.tmpl");
$data=fread($conf,$size);
fclose($conf);

#文字の置き換え
$data=str_replace("!requirements!",$requirements,$data);
$data=str_replace("!name!",$name,$data);
$data=str_replace("!mail!",$mail,$data);
$data=str_replace("!comment!",$comment,$data);

#表示
echo $data;
exit;

}

#エラーチェック
function error($msg){
$error=fopen("tmp/error.tmpl","r");
$size=filesize("tmp/error.tmpl");
$data=fread($error,$size);
fclose($error);

#文字置き換え
$data=str_replace("!message!",$msg,$data);

echo $data;
exit;

}

#CSV書き込み
function send_form(){

global $requirements;
global $name;
global $mail;
global $comment;

$user_input=array($requirements,$name,$mail,$comment);

mb_convert_variables("SJIS","UTF-8",$user_input);
$fh=fopen("user.csv","a");
flock($fh,LOCK_EX);
fputcsv($fh,$user_input);
flock($fh,LOCK_UN);
fclose($fh);

send_mail();

# テンプレート読み込み
$conf = fopen("tmp/send.tmpl","r") or die;
$size = filesize("tmp/send.tmpl");
$data = fread($conf , $size);
fclose($conf);

# 文字置き換え
global $toppage;
$data = str_replace("!top!", $toppage, $data);
# 表示
echo $data;
exit;

}

#メール送信
function send_mail(){

  global $requirements;
  global $name;
  global $mail;
  global $comment;

  $comment=str_replace("&lt;br&gt;","\r\n","$comment");

	# 本文
	$body = <<< _FORM_
フォームメールより、次のとおり連絡がありました。
 
■要件 :
$requirements
■名前 ： 
{$name} 様
■メールアドレス ： 
$mail
■コメント ： 
$comment
_FORM_;

	# 送信
	global $mailto;
	mb_language("japanese");
	mb_internal_encoding("UTF-8");
	$name_sendonly = "送信専用アドレス";
	$name_sendonly = mb_encode_mimeheader($name_sendonly);
	$mail_sendonly = "kawa-masa1974@outlook.jp";
	$mailfrom = "From:".$name_sendonly."<".$mail_sendonly.">";
	$subject = "フォームから連絡がありました";
	mb_send_mail($mailto,$subject,$body,$mailfrom);
}



?>
