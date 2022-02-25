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
echo <<< message
<!doctype html>
<html>
    <head>
        <title>メイン</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=css/style.css>
    </head>
    <body>
        <h1>主催者管理メニュー</h1>
        <ul id="menu">
            <a class="menu-link" href="admin.php"><li class="menu"><font color=white>男女参加者リスト</font></li></a>
            <a class="menu-link" href="man.php"><li class="menu"><font color=white>男性参加者リスト</font></li></a>
            <a class="menu-link" href="woman.php"><li class="menu"><font color=white>女性参加者リスト</font></li></a>
            <a class="menu-link" href="fore.php"><li class="menu"><font color=white>占い師用</font></li></a>
            <a class="menu-link" href="logout.php"><li class="menu"><font color=white>ログアウト</font></li></a>
        </ul>

        <script src="jquery-3.5.1.min.js"></script>
        <script>
    
         $(function() {
          $(".menu").hover(function(){
           $(this).css('background','deeppink');
            }, function() {
             $(this).css('background','');
          });
         });

         $(function() {
            $("button").hover(function(){
             $(this).css('color','#02029b');
              }, function() {
               $(this).css('color','');
            });
           });
    
        </script>

    </body>
</html>
message;  
 }

?>