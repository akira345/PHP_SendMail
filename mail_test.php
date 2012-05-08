<?php
require_once("./sendmail.php");
//使い方のサンプル
$mail = new sendmail("./test.tmpl");	//テンプレートファイルを指定
$mail->set_to("send_to@exsample.com");	//To
$mail->set_from("send_from@exsample.com");	//From
$mail->set_title("Mail_Title");	//タイトル
//テンプレートで置換する文字列を指定します。
$tmp = array(
		"aaa"=>"置換さてた文字"
		);
$mail->send($tmp);	//メール送信！
?>
