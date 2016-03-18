<?php

error_reporting('E_NONE'); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_client_config_path = $_SERVER['DOCUMENT_ROOT'] . '/client/config/database.php';
$db_admin_config_path = $_SERVER['DOCUMENT_ROOT'] . '/admin/config/database.php';
$mail_client_config_path = $_SERVER['DOCUMENT_ROOT'] . '/client/config/mail.php';
$mail_admin_config_path = $_SERVER['DOCUMENT_ROOT'] . '/admin/config/mail.php';

if ( file_exists($db_config_path) && file_exists($mail_config_path) ) {
	header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/index.php');
	exit();
}

if ( file_exists($db_admin_config_path) && file_exists($mail_admin_config_path) ) {
	header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/admin/index.php');
	exit();
}

// Check POST content
$redirect_page = false;
$data = array();

if (empty($_POST['MailHost'])){
	$redirect_page = true;
} else {
	$data['MailHost'] = addslashes($_POST['MailHost']);
}
if (empty($_POST['MailPort'])){
	$data['MailPort'] = 25;
} else {
	$data['MailPort'] = (int) $_POST['MailPort'];
} 
// is checkbox answer set?
if (! isset($_POST['MailSMTPAuth'])){
	$data['MailSMTPAuth'] = 'false';
} else {
	$data['MailSMTPAuth'] = $_POST['MailSMTPAuth'];
}
if (empty($_POST['MailSMTPSec'])){
	$data['MailSMTPSec'] = '';
} else {
	$data['MailSMTPSec'] = $_POST['MailSMTPSec'];
}
if (empty($_POST['MailSMTPuser'])){
	$data['MailSMTPuser'] = '';
} else {
	$data['MailSMTPuser'] = addslashes($_POST['MailSMTPuser']);
}
if (empty($_POST['MailSMTPpasswd'])){
	$data['MailSMTPpasswd'] = '';
} else {
	$data['MailSMTPpasswd'] = addslashes($_POST['MailSMTPpasswd']);
}
if (empty($_POST['MailFrom'])){
	$redirect_page = true;
} else {
	$data['MailFrom'] = addslashes($_POST['MailFrom']);
}
if (empty($_POST['MailFromName'])){
	$data['MailFromName'] = 'Unimail';
} else {
	$data['MailFromName'] = addslashes($_POST['MailFromName']);
}
if (empty($_POST['MailReplyTo'])){
	$data['MailReplyTo'] = '';
} else {
	$data['MailReplyTo'] = $_POST['MailReplyTo'];
}
if (empty($_POST['MailReplyToName'])){
	$data['MailReplyToName'] = '';
} else {
	$data['MailReplyToName'] = addslashes($_POST['MailReplyToName']);
}
if (empty($_POST['WordWrap'])){
	$data['WordWrap'] = '70';
} else {
	$data['WordWrap'] = (int) $_POST['WordWrap'];
} 

if ($redirect_page) {
	// POST data to another page
	session_start();
	$_SESSION = $_POST;
	session_write_close();
	header('Location:/install/install3.php');
	exit();
}

// Load the classes and create the new objects
require_once('includes/core_class.php');

$core = new Core();
$err_message = "";

if (empty($err_message)) {
	// Extract 'install' grantparent dir
	$current_dir = dirname( dirname( getcwd() ) ) . '/';

	$data = array(
		'unml_mail_host'=>$data['MailHost'], 
		'unml_mail_port'=>$data['MailPort'],
		'unml_mail_SMTP_auth'=>$data['MailSMTPAuth'],
		'unml_mail_SMTP_sec'=>$data['MailSMTPSec'],
		'unml_mail_SMTP_user'=>$data['MailSMTPuser'],
		'unml_mail_SMTP_pswd'=>$data['MailSMTPpasswd'],
		'unml_mail_From'=>$data['MailFrom'],
		'unml_mail_FromName'=>$data['MailFromName'],
		'unml_mail_ReplyTo'=>$data['MailReplyTo'],
		'unml_mail_ReplyToName'=>$data['MailReplyToName'],
		'unml_mail_WrodWrap'=>$data['WordWrap']
		);

	if ($core->write_custom_config($mail_client_config_path, $data) == false) {
		$err_message = "The unimail configuration file could not be written, ";
		$err_message .= "please chmod " . $mail_client_config_path ." file to 777";
		
	} else if ($core->write_custom_config($mail_admin_config_path, $data) == false) {
		$err_message = "The admin unimail configuration file could not be written, ";
		$err_message .= "please chmod " . $mail_admin_config_path ." file to 777";
	}
}

/*
if ( empty($err_message) ) {
	//remove install dir
	if (! rrmdir("../install") ) {
		$err_message = "Could not remove <strong>install</strong> directory. Remove it manually!";
	}
}
*/

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
<title>Instalation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
<div id="container">
  <header>
    <div class="div1">
      <div class="div2"><strong>Unimail Installer</strong></div>
      <div class="div3"><img src="img/install_logo_5.png" alt="Unimail intaller : Step 1" /></div>
    </div>
  </header>
  <div id="content">
    <div id="ctr" align="center">
      <?php 
      	if (! empty($err_message)) {
      		echo '<div class="warning">' . $err_message . '</div>';
      	}
      ?>
      <form action="/admin/index.php" method="post" name="form" id="form" >
        <div class="install">
            <div id="stepbar">
              <div class="step-off">Paso 1: Licencia</div>
              <div class="step-off">Paso 2: Estado del sistema</div>
              <div class="step-off">Paso 3: Base de Datos</div>
              <div class="step-off">Paso 4: Configurar correo</div>
              <div class="step-on">Paso 5: Fin de la instalaci&oacute;n</div>
            </div>
            <div id="right">
              <div class="far-right">
                <input class="button" type="submit" name="next" value="Finalizar"/>
	      </div>
	      <div id="step">Paso 5: Fin de la instalaci&oacute;n</div>
	      <div class="clr"></div>
	      <h1>&nbsp; </h1>
	    </div>
	  <div class="clr"></div>
        </div>   
      </form>
    </div><!-- ctr -->
  </div><!-- content -->
</div><!-- container -->

<footer>
  <p>Unimail - &copy; <?php date_default_timezone_set('Europe/Madrid'); echo date('Y');?>
  &nbsp;&nbsp;[<a href="http://www.zundan.com" target="_blank">Desarrollo: Zundan.com</a>]
  </p>
</footer>
</body>
</html>
