<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION["name"])==false){
    echo <<< message
    <p> ログインされてません!!<br>
    ログインして下さい。</p>
    <p><a href="login.html">ログインページへ</a></p>
    message;
}
else{

$csv = array();
$csv_man = fopen("./data/man.csv","r");
while($line = fgets($csv_man)){
    array_push($csv,$line);
}
fclose($csv_man);
//var_dump($csv);

$list = fopen("./tmpl/listsex.tmpl","r");
$size = filesize("./tmpl/listsex.tmpl");
$data = fread($list,$size);
fclose($list);

//var_dump($data);

$contents = "";
foreach($csv as $value){
    //var_dump($value);
    list($name,$addre1,$comment) = explode(",",$value);

    $comment = str_replace("&lt;br&gt","<br>",$comment);
    $comment = preg_replace('/"/','', $comment);
    $comment = preg_replace('/;/','', $comment);

    //var_dump($value);
    $replace_data = $data;
    $replace_data = str_replace("!name!",$name,$replace_data);
    $replace_data = str_replace("!addre1!",$addre1,$replace_data);
    $replace_data = str_replace("!comment!",$comment,$replace_data);

    $contents .= $replace_data;
}
//var_dump($contents);

$page = fopen("./tmpl/pageman.tmpl","r");
$pagesize = filesize("./tmpl/pageman.tmpl");
$data_page = fread($page,$pagesize);
fclose($page);

$data_page = str_replace("!contents!",$contents,$data_page);
echo $data_page;
exit;
}
