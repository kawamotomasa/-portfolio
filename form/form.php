<?php

#トップページ
$toppage = "./form.html";

#csvファイル
$bbs_data = "./data/data.csv";

#受け取り加工処理
//空配列
$param = array();

//post送信で送られたので、$paramに$_POSTの配列を代入
if(isset($_POST) && is_array($_POST)){
    $param +=$_POST;
    //var_dump($_POST["year"],$_POST["month"],$_POST["day"]);
    $birthday= $_POST["year"].$_POST["month"].$_POST["day"];
    //echo $birthday;
}
foreach($param as $key=> $val){
    //$keyには、key値として(name、age、sex、mode etc)がある。$valには、フォームで入力した生データがある。
    $enc = mb_detect_encoding($val);
    $val = mb_convert_encoding($val,"utf-8",$enc);

    $val = htmlentities($val,ENT_QUOTES,"UTF-8");

    $val = str_replace("\r\n","<br>",$val);
    $val = str_replace("\r","<br>",$val);
    $val = str_replace("\n","<br>",$val);

    $in[$key] = $val;
    //それぞれのkey値(name、age、sex、mode etc)に値(生データ)を代入
}

//var_dump($in);

$mailto = $in["mail"];

//フォーム書き込みチェック

$error_note = "";

if($in["name"] === ""){
    $error_note .= "名前が入力されていません。<br>";
}

if($in["sex"] === ""){
    $error_note .= "性別が選択されてません。<br>";
}

if($in["year"] === ""){
    $error_note .= "西暦が選択されてません。<br>";
}

if($in["month"] === ""){
    $error_note .= "月が選択されてません。<br>";
}

if($in["day"] === ""){
    $error_note .= "日付が選択されてません。<br>";
}

if($in["addre1"] === ""){
    $error_note .= "住所が入力されてません。<br>";
}

if($in["addre2"] === ""){
    $error_note .= "番地が入力されてません。<br>";
}

if($in["tel"] === ""){
    $error_note .= "電話番号が入力されてません。<br>";
}

else if(!preg_match("/^0[0-9]{9,10}\z/",$in["tel"])){
    $error_note.="電話番号が間違っています。<br>";
}

if($in["mail"] === ""){
    $error_note.="メールアドレスが未入力です。<br>";
  }
  else if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $in["mail"])){
    $error_note.="メールアドレスが間違っています。<br>";
  }

  if($in["work"] === ""){
    $error_note .= "職業が入力されてません。<br>";
}


if($error_note !== ""){
    error($error_note);
}

print_r($error_note);


#分岐処理
//確認画面(フォームに書き込んで送信した時の処理)
if($in["mode"] == "post"){
    conf_form();
}
//CSV書き込み&送信処理
else if($in["mode"] == "send"){
    send_form();
}

#確認画面処理
function conf_form(){
 
    global $in;
    global $birthday;

    $age = floor((date('Ymd') - $birthday) / 10000);

    //echo $in["comment"]."です。";

    //$in["comment"] = str_replace(" ","<br>","{$in["comment"]}");

    $contmpl = fopen("./tmpl/conf.tmpl","r");
    $size = filesize("./tmpl/conf.tmpl");
    $data = fread($contmpl,$size);
    fclose($contmpl);

    $data_replace = $data;
    $data_replace = str_replace("!name!",$in["name"],$data_replace);
    $data_replace = str_replace("!sex!",$in["sex"],$data_replace);
    $data_replace = str_replace("!year!",$in["year"],$data_replace);
    $data_replace = str_replace("!month!",$in["month"],$data_replace);
    $data_replace = str_replace("!day!",$in["day"],$data_replace);
    $data_replace = str_replace("!age!",$age,$data_replace);
    $data_replace = str_replace("!addre1!",$in["addre1"],$data_replace);
    $data_replace = str_replace("!addre2!",$in["addre2"],$data_replace);
    $data_replace = str_replace("!tel!",$in["tel"],$data_replace);
    $data_replace = str_replace("!mail!",$in["mail"],$data_replace);
    $data_replace = str_replace("!work!",$in["work"],$data_replace);
    $data_replace = str_replace("!comment!",$in["comment"],$data_replace);

    echo $data_replace;
    exit;
    
}

#CSV書き込み&送信処理
 function send_form(){
     global $in;
     global $bbs_data;
     global $toppage;
    
    //スタッフ用
    $admin_input  = array($in["name"],$in["sex"],$in["addre1"],$in["addre2"],$in["tel"],$in["mail"],$in["work"]);
    $fh=fopen("$bbs_data","a");
    flock($fh,LOCK_EX);
    fputcsv($fh,$admin_input);
    flock($fh,LOCK_UN);
    fclose($fh);

    //占い師用
    $fore_input  = array($in["name"],$in["sex"],$in["age"],$in["year"],$in["month"],$in["day"]);
    $fh_fore=fopen("./data/fore.csv","a");
    flock($fh_fore,LOCK_EX);
    fputcsv($fh_fore,$fore_input);
    flock($fh_fore,LOCK_UN);
    fclose($fh_fore);

    //女性参加用
    if($in["sex"] ==="男"){
        $man_input  = array($in["name"],$in["addre1"],$in["comment"]);
        $fh_man = fopen("./data/man.csv","a");
        flock($fh_man,LOCK_EX);
        fputcsv($fh_man,$man_input);
        flock($fh_man,LOCK_UN);  
        fclose($fh_man);     
    }
    //男性参加用
    else if($in["sex"] ==="女"){
        $woman_input  = array($in["name"],$in["addre1"],$in["comment"]);   
        $fh_woman = fopen("./data/woman.csv","a");
        flock($fh_woman,LOCK_EX);
        fputcsv($fh_woman,$woman_input);
        flock($fh_woman,LOCK_UN);  
        fclose($fh_woman);       
    }

    #メール送信
     send_mail();

     $send = fopen("./tmpl/send.tmpl","r");
     $size = filesize("./tmpl/send.tmpl");
     $read = fread($send,$size);
     fclose($send);

     $str_read = $read;
     $str_read = str_replace("!top!",$toppage,$str_read);

     echo $str_read;
     exit;
 }

#メール送信

function send_mail(){

    $date = date("Y/m/d H:i:s");
    $ip = getenv("REMOTE_ADDR");
    global $in;
    
    $in["comment"]=str_replace("&lt;br&gt;","\r\n","{$in["comment"]}");
    
    #本文
    $body = <<< _FORM_
    
    お世話になっております。
    〇〇です。
    
    お申込みありがとうございます。
    下記、内容にて受け付けました。
    
    ■名前：
    {$in["name"]}
    ■メールアドレス：
    {$in["mail"]}
    ■コメント:
    {$in["comment"]}
    
    当日は、〇〇駅改札前に〇時〇分に集合します。
    時間に遅れそうな場合は、連絡するようお願いいたします。
    
    
    _FORM_;
    
    #送信
    
        global $mailto;
        mb_language("japanese");
        mb_internal_encoding("UTF-8");
        $name_sendonly = "婚活イベント「畑婚」";
        $name_sendonly = mb_encode_mimeheader($name_sendonly);
        $mail_sendonly = "kawa-masa1974@outlook.jp";
        $mailfrom = "From:".$name_sendonly."<".$mail_sendonly.">";
        $subject = "ご応募ありがとうございます。";
        mb_send_mail($mailto,$subject,$body,$mailfrom);
    
    }



 #エラー処理
 function error($msg){
    $error = fopen("./tmpl/error.tmpl","r");
    $size = filesize("./tmpl/error.tmpl");
    $read = fread($error,$size);
    fclose($error);

    $read_replace = $read;
    $read_replace = str_replace("!message!",$msg,$read_replace);

    echo $read_replace;
    exit;

 }