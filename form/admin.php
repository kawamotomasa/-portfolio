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
$csv_data = fopen("./data/data.csv","r");
while($line = fgets($csv_data)){
    array_push($csv,$line);
}
fclose($csv_data);
//var_dump($csv);

$list = fopen("./tmpl/list.tmpl","r");
$size = filesize("./tmpl/list.tmpl");
$data = fread($list,$size);
fclose($list);

//var_dump($data);

$contents = "";
foreach($csv as $value){
    //var_dump($value);
    list($name,$sex,$addre1,$addre2,$tel,$mail,$work) = explode(",",$value);
    //var_dump($value);

    $addre2 = preg_replace('/"/','', $addre2);

    $replace_data = $data;
    $replace_data = str_replace("!name!",$name,$replace_data);
    $replace_data = str_replace("!sex!",$sex,$replace_data);
    $replace_data = str_replace("!addre1!",$addre1,$replace_data);
    $replace_data = str_replace("!addre2!",$addre2,$replace_data);
    $replace_data = str_replace("!tel!",$tel,$replace_data);
    $replace_data = str_replace("!mail!",$mail,$replace_data);
    $replace_data = str_replace("!work!",$work,$replace_data);
    $contents .= $replace_data;
}
//var_dump($contents);

$page = fopen("./tmpl/page.tmpl","r");
$pagesize = filesize("./tmpl/page.tmpl");
$data_page = fread($page,$pagesize);
fclose($page);

$data_page = str_replace("!contents!",$contents,$data_page);
echo $data_page;
exit;
}
