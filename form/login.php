<?php
session_start();
$name = htmlentities($_POST["name"],ENT_QUOTES,"utf-8");
$password = htmlentities($_POST["password"],ENT_QUOTES,"utf-8");
$password = hash("sha256",$password);
if($name == "admin" && $password ==
"c565fe03ca9b6242e01dfddefe9bba3d98b270e19cd02fd85ceaf75e2b25bf12"){
    $_SESSION["name"] = $name;
    $_SESSION["password"] = $password;

    echo <<< _form1_
    <!DOCTYPE html>
    <html>
    <head>
    <title>ログインメニュー</title>
    <meta charset="utf-8">
    <style>
    h1{
        text-align: center;
        margin:100px;
    }
    #login{
        text-align: center;
        margin:100px;
    }
    button{
        cursor: pointer;
        background-color: blue;
        width:200px;
        height:80px;
    }
    </style>
    </head>
    <body>
    <h1>ログインメニュー</h1>
    <div id=login>
    <p><a href="main.php"><button><font color=white>主催者管理メニュー</font></button></a>
    <p><a href="logout.php"><button><font color=white>ログアウト</font></button></a></p>
    </div>

    <script src="jquery-3.5.1.min.js"></script>
    <script>

     $(function() {
      $("button").hover(function(){
       $(this).css('background','deeppink');
        }, function() {
         $(this).css('background','');
      });
     });

    </script>

    </body>
    </html>

    _form1_;

}else{
    echo <<<_form2_
    <p>ログイン失敗</p>
    <p><a href="login.html">ログインページへ</a></p>
    _form2_;
}

