<?php
error_reporting('E_NONE'); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../config/database.php';
$db_admin_config_path = '../admin/config/database.php';
$mail_config_path = $_SERVER['DOCUMENT_ROOT'] . '/config/mail.php';
$mail_admin_config_path = $_SERVER['DOCUMENT_ROOT'] .  '/admin/config/mail.php';

if (file_exists( $db_config_path ) && file_exists($mail_config_path) ) {
	header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/index.php');
	exit();
}

if (file_exists( $db_admin_config_path ) && file_exists( $mail_admin_config_path ) ) {
	header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/admin/index.php');
	exit();
}

session_start();
if (! empty($_SESSION) ){
	$DBhostname = $_SESSION['DBhostname'];
	$DBuserName = $_SESSION['DBuserName'];
	$DBpassword = $_SESSION['DBpassword'];
	$DBname = $_SESSION['DBname'];
	$DBPrefix = $_SESSION['DBPrefix'];
	$webadmin = $_SESSION['webadmin'];
	$webadminpass = $_SESSION['webadminpass'];
} else {
	$webadminpass = "";
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
<body onload="document.form.DBhostname.focus();">
<div id="container">
  <header>
    <div class="div1">
      <div class="div2"><strong>Unimail Installer</strong></div>
      <div class="div3"><img src="img/install_logo_3.png" alt="Unimail intaller : Step 1" /></div>
    </div>
  </header>
  <div id="content">
    <div id="ctr" align="center">
      <form action="install3.php" method="post" name="inst1form" >
        <div class="install">
          <div id="stepbar">
            <div class="step-off">Paso 1: Licencia</div>
            <div class="step-off">Paso 2: Estado del sistema</div>
            <div class="step-on">Paso 3: Base de Datos</div>
            <div class="step-off">Paso 4: Configurar correo</div>
            <div class="step-off">Paso 5: Fin de la instalaci&oacute;n</div>
          </div>
          <div id="right">
            <div class="far-right">
              <input class="button" type="submit" value="Continuar" tabindex="8"
       	            onclick="window.location=\'install3.php\" />
       	    </div>
            <div id="step">Paso 3: Base de Datos</div>
            <div class="clr"></div>
            <h1>Configuracion de la base de datos</h1>
            <div class="install-form">
              <div class="form-block">
              	<table class="content2">
              	  <tr>
              	    <td></td>
              	    <td></td>
              	    <td></td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Direccion del servidor
              	      <br/>
              	      <input class="inputbox" type="text" name="DBhostname" tabindex="1" 
              	      value="<?php 
              	      	if ("$DBhostname" == "") {
              	      		echo "localhost";
              	      	} else { 
              	      		echo "$DBhostname";
              	      	} ?>" />
              	    </td>
              	    <td>
              	      <em>Direcci&oacute;n IP o 'localhost'</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre usuario de MySQL
              	      <br/>
              	      <input class="inputbox" type="text" name="DBuserName" tabindex="2"
              	      value="<?php 
              	      if ("$DBuserName" == "") {
              	      	echo "root";
              	      } else { 
              	      echo "$DBuserName";
              	      } ?>" />
              	    </td>
              	    <td>
              	      <em>Puede ser 'root' u otro nombre del administrador de 
              	      la base de datos de MySQL</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Contrase&ntilde;a del usuario para MySQL
              	      <br/>
              	      <input class="inputbox" type="text" name="DBpassword" tabindex="3"
              	      value="<?php echo "$DBpassword"; ?>" />
              	    </td>
              	    <td>
              	      <em>Introducir contrase&ntilde;a del usuario de MySQL</em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre de la DB
              	      <br/>
              	      <input class="inputbox" type="text" name="DBname" tabindex="4"
              	      value="<?php 
              	      if ("$DBname" == "") {
              	      	echo "unimail";
              	      	} else { 
              	      	echo "$DBname";
              	      	} ?>" />
              	    </td>
              	    <td>
              	      <em>Por defecto ser&aacute; Unimail </em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Prefijo que se usar&aacute; para las tablas
              	      <br/>
              	      <input class="inputbox" type="text" name="DBPrefix" tabindex="5"
              	      value="<?php 
              	      if ("$DBPrefix" == "") {
              	      	echo "nm_";
              	      	} else { 
              	      	echo "$DBPrefix";
              	      	} ?>" />
              	    </td>
              	    <td></td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Nombre del administrador de la web
              	      <br/>
              	      <input class="inputbox" type="text" name="webadmin" tabindex="6"
              	      value="<?php if ("$DBname" == "") {
              	      		echo "admin";
              	      } else { 
              	      		echo "$webadmin";
              	      } ?>" />
              	    </td>
              	    <td>
              	      <em>Por defecto ser&aacute; 'admin' </em>
              	    </td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">Contrase&ntilde;a del administrador de la web
              	      <br/>
              	      <input class="inputbox" type="text" name="webadminpass" tabindex="7"
              	      value="<?php echo $webadminpass ?>" />
              	    </td>
              	    <td></td>
              	  </tr>
              	  <tr><td></td><td></td></tr>
              	</table>
             </div>
           </div>
         </div>
         <div class="clr"></div>
       </div>
     </form>
   </div> <!-- ctr -->
  </div><!-- content -->
</div><!-- container -->

<footer>
  <p>Unimail - &copy; <?php date_default_timezone_set('Europe/Madrid'); echo date('Y');?>
  &nbsp;&nbsp;[<a href="http://www.zundan.com" target="_blank">Desarrollo: Zundan.com</a>]
  </p>
</footer>
</body>
</html>
