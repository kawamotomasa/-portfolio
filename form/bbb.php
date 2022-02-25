<?php

// 現在日付
$now = date('Ymd');

// 誕生日
$birthday = "1976/07/01";
$birthday = str_replace("/", "", $birthday);

// 年齢
$age = floor(($now - $birthday) / 10000);
echo $age . '歳';