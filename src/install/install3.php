<?php

error_reporting('E_NONE'); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_admin_config_path = $_SERVER['DOCUMENT_ROOT'] .  '/admin/config/database.php';
$db_client_config_path = $_SERVER['DOCUMENT_ROOT'] . '/client/config/database.php';
$db_notif_config_path = $_SERVER['DOCUMENT_ROOT'] .  '/notif/config/database.php';

$mail_admin_config_path = $_SERVER['DOCUMENT_ROOT'] .  '/admin/config/mail.php';
$mail_client_config_path = $_SERVER['DOCUMENT_ROOT'] . '/client/config/mail.php';

if ( file_exists($db_client_config_path) && file_exists($mail_client_config_path) ) {
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

if (empty($_POST['DBhostname']) ){
	$redirect_page = true;
} else {
	$data['DBhostname'] = $_POST['DBhostname'];
}
if (empty($_POST['DBuserName']) ){
	$redirect_page = true;
} else {
	$data['DBuserName'] = $_POST['DBuserName'];
}
if (empty($_POST['DBpassword']) ){
	$redirect_page = true;
} else {
	$data['DBpassword'] = $_POST['DBpassword'];
}
if (empty($_POST['DBname']) ){
	$redirect_page = true;
} else {
	$data['DBname'] = $_POST['DBname'];
}
if (empty($_POST['DBPrefix']) ){
	$data['DBPrefix'] = "nm_";
} else {
	$data['DBPrefix'] = $_POST['DBPrefix'];
}
if (empty($_POST['webadmin']) ){
	$redirect_page = true;
	$data['webadmin'] = "admin";
} else {
	$data['webadmin'] = $_POST['webadmin'];
}
if (empty($_POST['webadminpass']) ){
	$redirect_page = true;
	require_once("includes/passwdgenerator.php");
	$data['webadminpass'] = generateStrongPassword();
	$_POST['webadminpass'] = $data['webadminpass']; 
} else {
	$data['webadminpass'] = $_POST['webadminpass'];
}

if ($redirect_page) {
	// POST data to another page
	session_start();
	$_SESSION = $_POST;
	session_write_close();
	header('Location:/install/install2.php');
	exit();
}

// Load the classes and create the new objects
require_once('includes/core_class.php');
require_once('includes/database_class.php');

$core = new Core();
$database = new Database();
$err_message = "";

if($core->validate_post($data) == true) {
	// First create the database, then create tables, then write config file
	if ($database->create_database($data) == false) {
		$err_message = "The database could not be created, please verify your settings.";
	} else if ($database->create_tables($data) == false) {
		$err_message = "The database tables could not be created, please verify your settings.";
	} else if ($core->write_DB_config($db_admin_config_path, $data) == false) {
		$err_message = "The admin database configuration file could not be written, ";
		$err_message .= "please chmod " . $db_admin_config_path ." file to 777";
	} else if ($core->write_DB_config($db_client_config_path, $data) == false) {
		$err_message = "The client database configuration file could not be written, ";
		$err_message .= "please chmod " . $db_client_config_path . " file to 777";
	} else if ($core->write_DB_config($db_notif_config_path, $data) == false) {
		$err_message = "The notif database configuration file could not be written, ";
		$err_message .= "please chmod " . $db_notif_config_path ." file to 777";
	}
}

if (empty($err_message)) {
	// Extract 'install' grantparent dir
	$current_dir = dirname( dirname( getcwd() ) ) . '/';

	// Create Unimail dirs
	$unimail_store_base = '/unimail_store';
	$cron_script = 'cron_notifier.php';
	
	$data = array(
		'unml_store'=>$unimail_store_base, 
		'unml_todo'=>$unimail_store_base. '/todo/', 
		'unml_cron'=>$unimail_store_base . '/cron/',
		'unml_cron_conffile'=>$unimail_store_base . '/cron/crontab.txt',
		'unml_cron_bin'=>$unimail_store_base . '/cron/bin/',
		'unml_cron_file'=>$unimail_store_base . '/cron/bin/' . $cron_script,
		'unml_cron_output'=>$unimail_store_base . '/output/',
		'unml_cron_output_now'=>$unimail_store_base . '/output/now/',
		'unml_cron_output_future'=>$unimail_store_base . '/output/future/',
		'unml_cron_output_tmp'=>$unimail_store_base . '/output/tmp/',
//		'unml_cron_sent'=>$unimail_store_base . '/sent/',
//		'unml_companies'=>$unimail_store_base . '/companies/',
		'unml_engines'=>$unimail_store_base . '/engines/',
		'unml_notif'=>$unimail_store_base . '/notif/',
		'unml_tsa'=>$unimail_store_base . '/tsa/'
		'unml_tsa_conffile'=>$unimail_store_base . '/tsa/tsa_server.cfg',
		'unml_tsa_serverfile'=>$unimail_store_base . '/tsa/tsa_server.cer'
		);
	
	$unimail_client_config_path = $_SERVER['DOCUMENT_ROOT'] . '/client/config/unimail.php';
	$unimail_admin_config_path = $_SERVER['DOCUMENT_ROOT'] .'/admin/config/unimail.php';

	if ($core->write_custom_config($unimail_client_config_path, $data) == false) {
		$err_message = "The unimail client configuration file could not be written, ";
		$err_message .= "please chmod " . $unimail_client_config_path ." file to 777";
		
	} else if ($core->write_custom_config($unimail_admin_config_path, $data) == false) {
		$err_message = "The admin unimail configuration file could not be written, ";
		$err_message .= "please chmod " . $unimail_admin_config_path ." file to 777";
		
	} else {
		require_once("includes/removedir.php");
		rrmdir($current_dir . $unimail_store_base);
	
		foreach ($data as $key => $value) {
			if (strpos($key, "file") !== false) {
				continue;
			} else if (! is_dir($current_dir.$value) && ! mkdir ($current_dir.$value) ){
				$err_message .= '<p>Could not create dir:'.$current_dir.$value.'</p>';
			} else if (! chmod ($current_dir. $value, 0700) ) {
				$err_message .= '<p>Could not chmod dir:' .$current_dir.$value .'</p>';
			}
		}	
	}
}

if ( empty($err_message) ){
	// Create script to be runned by Cron
	$orig_script = getcwd() . '/assets/' . $cron_script;
	$target_script = $current_dir . $data['unml_cron_file']; 
	
	$data2 = array('configfile' => $unimail_admin_config_path);
	
	if ($core->write_cron_script($orig_script, $target_script, $data2) === false ){
		$err_message = 'Could not copy cron script file: ' . $orig_script;
	} else if (! chmod ($target_script, 0700) ) {
		$err_message = 'Could not chmod file: ' . $target_script;
	}
}
if ( empty($err_message) ){
	
	// Create CRON job file
	// From left to right in crontab
	// Note last blank space!
	$minute = '0,5,45 '; // (0 - 59) 
	$hour = '* '; // (0 - 23)
	$day = '* '; // (1 - 31)
	$month = '* '; // (1 - 12)
	$weekday = '* ';  // (0 - 6) --> 0 == sunday
	$command = '/usr/bin/php ' . $current_dir . $data['unml_cron_file'];
	$comment = ' # unimail';
	$cron_string = $minute . $hour . $day . $month . $weekday . $command . $comment . PHP_EOL; 
	if (file_put_contents('../../' .  $data['unml_cron_conffile'], $cron_string) === false) {
		$err_message = 'Could not create crontab file:' .$data['unml_cron_conffile'] .'';
	}
	
}
if ( empty($err_message) ){
	// Set crontab from file created in previous instructions

	if (! empty( exec('crontab ' .$current_dir . $data['unml_cron_conffile']) ) ) {
		$err_message = 'Could not dump crontab file to cron:' .$data['unml_cron_conffile'] .'';
	}
}

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
      <form action="install4.php" method="post" name="form" id="form" >
        <div class="install">
            <div id="stepbar">
              <div class="step-off">Paso 1: Licencia</div>
              <div class="step-off">Paso 2: Estado del sistema</div>
              <div class="step-off">Paso 3: Base de Datos</div>
              <div class="step-on">Paso 4: Configurar correo</div>
              <div class="step-off">Paso 5: Fin de la instalaci&oacute;n</div>
            </div>
            <div id="right">
              <div class="far-right">
                <input class="button" type="submit" name="next" value="Continuar" tabindex="14" />
	      </div>
	      <div id="step">Paso 4: Configurar correo</div>
	      <div class="clr"></div>
	      <h1>Configuracion del correo para servidor SMTP remoto</h1>
	      <div class="install-form">
	        <div class="form-block">
	        <table class="content2">
              	  <tr>
              	    <td></td>
              	    <td></td>
              	    <td></td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Direccion del servidor SMTP
              	      <br/>
              	      <input class="inputbox" type="text" name="MailHost" tabindex="1" 
              	      value="<?php 
              	      	if ("$MailHost" != "") {
              	      		echo "$MailHost";
              	      	} ?>" />
              	    </td>
              	    <td>
              	      <em>Direcci&oacute;n del servidor remoto SMTP</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Puerto del servidor SMTP
              	      <br/>
              	      <input class="inputbox" type="text" name="MailPort" tabindex="2" 
              	     value="<?php 
              	      	if ("$MailPort" == "") {
              	      		echo "25";
              	      	} else { 
              	      		echo "$MailPort";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Direcci&oacute;n del servidor remoto SMTP. Por defecto 25.</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Autentificaci&oacute;n SMTP
              	      <br/>
              	      <input type="checkbox" name="MailSMTPAuth" value="true" tabindex="3" 
              	        <?php 
              	      	if (strcmp($MailSMTPAuth, "true") == 0) {
              	      		echo 'checked="checked" ';
              	      	} 
              	      	?> 
              	      />
              	    </td>
              	    <td>
              	      <em>Indica si se requiere autentificar contra servidor SMTP. Por defecto <i>TRUE</i>.</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre de usuario de SMTP
              	      <br/>
              	      <input class="inputbox" type="text" name="MailSMTPuser" tabindex="4" 
              	     value="<?php 
              	      	if ("$MailSMTPuser" != "") {
              	      		echo "$MailSMTPuser";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Nombre del usuario para autentificar en SMTP. Por ejemplo: po@popo.com</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Contraseña de usuario de SMTP
              	      <br/>
              	      <input class="inputbox" type="text" name="MailSMTPpasswd" tabindex="5" 
              	     value="<?php 
              	      	if ("$MailSMTPpasswd" != "") {
              	      		echo "$MailSMTPpasswd";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Contraseña del usuario para autentificar en SMTP.</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">SMTP seguro
              	      <br/>
              	      <select name="MailSMTPSec" tabindex="6">
              	        <option value="" <?php if ("$MailSMTPSec" == "") echo 'selected="selected"'; ?>>Nada</option>
              	        <option value="tls" <?php if (strcmp($MailSMTPSec, "tls") == 0) echo 'selected="selected"'; ?>>TLS</option>
              	        <option value="ssl" <?php if (strcmp($MailSMTPSec, "ssl") == 0) echo 'selected="selected"'; ?>>SSL</option>
              	      </select>
              	    </td>
              	    <td>
              	  </tr>
              	   </tr>
              	  <tr>
              	    <td colspan="2">Direcci&oacute;n del emisor
              	      <br/>
              	      <input class="inputbox" type="text" name="MailFrom" tabindex="7" 
              	      value="<?php 
              	      	if ("$MailFrom" != "") {
              	      		echo "$MailFrom";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Direcci&oacute;n del correo del emisor. Por ejemplo: info@unimail.com</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre del emisor
              	      <br/>
              	      <input class="inputbox" type="text" name="MailFromName" tabindex="8" 
              	      value="<?php 
              	      	if ("$MailFromName" != "") {
              	      		echo "$MailFromName";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Nombre del correo del emisor. Por ejemplo: Unimail Informaci&oacute;n</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Direcci&oacute;n de respuesta
              	      <br/>
              	      <input class="inputbox" type="text" name="MailReplyTo" tabindex="9" 
              	      value="<?php 
              	      	if ("$MailReplyTo" != "") {
              	      		echo "$MailReplyTo";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Direcci&oacute;n del correo para respuesta. Por ejemplo: info@unimail.com</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre del correo de respuesta
              	      <br/>
              	      <input class="inputbox" type="text" name="MailReplyToName" tabindex="10" 
              	      value="<?php 
              	      	if ("$MailReplyToName" != "") {
              	      		echo "$MailReplyToName";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Nombre del correo para respuesta. Por ejemplo: Unimail Informaci&oacute;n</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Longitud de las l&iacute;neas
              	      <br/>
              	      <input class="inputbox" type="text" name="WordWrap" tabindex="12" 
              	      value="<?php 
              	      	if ("$WordWrap" == "") {
              	      		echo "70";
              	      	} else {
              	      		echo "$WordWrap";
              	      	} ?>" 
              	      />
              	    </td>
              	    <td>
              	      <em>Longitud m&acute;xima de las lineas del mensaje. Por defecto 70</em>
              	    </td>
              	  </tr>
              	</table>
	        </div>
	      </div>
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
