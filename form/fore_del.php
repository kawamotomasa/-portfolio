<?php
$file = './data/fore.csv';
// ファイルをオープンして既存のコンテンツを取得します
//$current = file_get_contents($file);
// 新しい人物をファイルに追加します
//$current .= "John Smith\n";

$current="";
// 結果をファイルに書き出します
file_put_contents($file, $current);
?>
全削除しました。
<a href="fore.php" ><button>確認する</button></a>
<!--<input type="button" value="確認する" onclick=" history.back()">-->
