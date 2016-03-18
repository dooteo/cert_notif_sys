<?php
error_reporting(E_NONE); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

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

$system_complete = true;	// Initialize as system was complete
if (! function_exists('json_encode')) {
	$isJson = false;
	$system_complete = false;
} else {
	$isJson = true;
}
// Extract 'install' grantparent dir
$unimail_store = dirname( dirname( getcwd() ) ) . '/unimail_store/_tmp';
if (is_dir($unimail_store)) {
	rmdir($unimail_store);	
}
if (! mkdir($unimail_store, 700, true) ) {
	$isUnimailDirWritable = false;
	$system_complete = false;
} else {
	$isUnimailDirWritable = true;
	rmdir($unimail_store);
}

// Check Exec works
if (exec('echo EXEC') != 'EXEC'){
	$isExec = false;
	$system_complete = false;
} else {
	$isExec = true;
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
      <div class="div3"><img src="img/install_logo_2.png" alt="Unimail intaller : Step 1" /></div>
    </div>
  </header>
  <div id="content">
    <div id="ctr" align="center">
      <form action="install2.php" method="post" name="inst1form" >
        <div class="install">
          <div id="stepbar">
            <div class="step-off">Paso 1: Licencia</div>
            <div class="step-on">Paso 2: Estado del sistema</div>
            <div class="step-off">Paso 3: Base de Datos</div>
            <div class="step-off">Paso 4: Configurar correo</div>
            <div class="step-off">Paso 5: Fin de la instalaci&oacute;n</div>
          </div>
          <div id="right">
	<?php 
		if ($system_complete) {
			echo '<div class="far-right">
				<input class="button" type="submit" value="Continuar" tabindex="8" 
				onclick="window.location=\'install2.php\';" />
				</div>';
		}
	?>
            <div id="step">Paso 2: Estado del sistema</div>
            <div class="clr"></div>
            <h1>Comprobaci&oacute;n del Sistema</h1>
            <div class="install-form">
              <div class="form-block">
              	<table class="content2">
              	  <tr>
              	    <td></td>
              	    <td></td>
              	    <td></td>
              	  </tr>
              	  <tr>
              	    <td colspan="2">JSON parser</td>
              	    <td>
              	      <?php 
			if ($isJson) {
				echo '<div style="color:green;">Yes</div>';
			} else {
				echo '<div style="color:red;">No</div>';
				echo 'Instalar el paquete <strong>php5-json</strong> en el sistema';
			}
		      ?>
              	    </td>
              	  </tr>
		  <tr>
              	    <td colspan="2">Unimail_Store directory</td>
              	    <td>
              	      <?php 
			if ($isUnimailDirWritable) {
				echo '<div style="color:green;">Writable</div>';
			} else {
				echo '<div style="color:red;">Not Writable</div>';
				echo 'Crear carpeta <strong>unimail_store</strong> en padre del <i>www</i> y dar su propiedad a Apache';
			}
		      ?>
              	    </td>
              	  </tr>
		  <tr>
              	    <td colspan="2">Exec()</td>
              	    <td>
              	      <?php 
			if ($isExec) {
				echo '<div style="color:green;">Yes</div>';
			} else {
				echo '<div style="color:red;">No</div>';
				echo 'PHP no puede usar la shell Exec. Configurar Apache para poder realizar el acceso';
			}
		      ?>
              	    </td>
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
