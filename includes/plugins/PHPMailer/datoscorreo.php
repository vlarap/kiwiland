<?php
$mail = new PHPMailer();

$mail->setLanguage('es');
$mail->IsSMTP();
$mail->Host			  = HOST_NAME;
$mail->Port     	= HOST_PORT;
$mail->From     	= MAIL_FROM;
$mail->FromName		= MAIL_FROMNAME;
$mail->SMTPAuth		= HOST_SMTPAUTH;
$mail->SMTPSecure	= HOST_SMTPSECURE;
$mail->Username 	= HOST_USER;
$mail->Password 	= HOST_PASS;

$mail->CharSet		= MAIL_UTF8;
$mail->Priority		= MAIL_PRIORI;
$mail->SMTPDebug	= MAIL_DEBUG;
$mail->IsHTML(MAIL_MSJHTML);
?>
