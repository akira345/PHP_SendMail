<?php
require_once("./sendmail.php");
//�Ȥ����Υ���ץ�
$mail = new sendmail("./test.tmpl");	//�ƥ�ץ졼�ȥե���������
$mail->set_to("send_to@exsample.com");	//To
$mail->set_from("send_from@exsample.com");	//From
$mail->set_title("Mail_Title");	//�����ȥ�
//�ƥ�ץ졼�Ȥ��ִ�����ʸ�������ꤷ�ޤ���
$tmp = array(
		"aaa"=>"�ִ����Ƥ�ʸ��"
		);
$mail->send($tmp);	//�᡼��������
?>
