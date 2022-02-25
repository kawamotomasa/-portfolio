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
$csv_fore = fopen("./data/fore.csv","r");
while($line = fgets($csv_fore)){
    array_push($csv,$line);
}
fclose($csv_fore);
//var_dump($csv);

$list = fopen("./tmpl/listfore.tmpl","r");
$size = filesize("./tmpl/listfore.tmpl");
$data = fread($list,$size);
fclose($list);

//var_dump($data);

$contents = "";
foreach($csv as $value){
    //var_dump($value);
    list($name,$sex,$age,$year,$month,$day) = explode(",",$value);
    //var_dump($value);
    $replace_data = $data;
    $replace_data = str_replace("!name!",$name,$replace_data);
    $replace_data = str_replace("!sex!",$sex,$replace_data);
    $replace_data = str_replace("!age!",$age,$replace_data);
    $replace_data = str_replace("!year!",$year,$replace_data);
    $replace_data = str_replace("!month!",$month,$replace_data);
    $replace_data = str_replace("!day!",$day,$replace_data);
    $contents .= $replace_data;
}
//var_dump($contents);

$page = fopen("./tmpl/pagefore.tmpl","r");
$pagesize = filesize("./tmpl/pagefore.tmpl");
$data_page = fread($page,$pagesize);
fclose($page);

$data_page = str_replace("!contents!",$contents,$data_page);
echo $data_page;
exit;
}
