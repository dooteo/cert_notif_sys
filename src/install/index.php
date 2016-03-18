<?php

error_reporting(E_NONE); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../config/database.php';
$db_admin_config_path = '../admin/config/database.php';


if (file_exists( $db_config_path ) && filesize( $db_config_path ) > 10) {
	header( "Location: ../index.php" );
	exit();
}

if (file_exists( $db_admin_config_path ) && filesize( $db_admin_config_path ) > 10) {
	header( "Location: ../index.php" );
	exit();
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
      <div class="div3"><img src="img/install_logo_0.png" alt="Unimail intaller : Step 1" /></div>
    </div>
  </header>
  <div id="content">
    <div id="ctr" align="center">
      <div class="install">
        <div id="stepbar">
          <div class="step-on">Paso 1: Licencia</div>
          <div class="step-off">Paso 2: Base de Datos</div>
          <div class="step-off">Paso 3: Configurar correo</div>
          <div class="step-off">Paso 4: Fin de la instalaci&oacute;n</div>
        </div>
        <div id="right">
          <div id="step">Paso 1: Licencia</div>
          <div class="far-right">
            <input name="Button2" type="submit" class="button" value="Continuar"
                	onclick="window.location='install1.php';" />
          </div>
          <div class="clr"></div>
          <h1>GNU/GPL Licencia:</h1>
          <div class="clr"></div>
          <div class="license-form">
            <div class="form-block" style="padding: 0px;">
              <iframe src="gpl.html" class="license" frameborder="0" 
		  scrolling="auto"></iframe>
            </div>
          </div>
          <div class="clr"></div>
          <div class="clr"></div>
        </div>
        <div id="break"></div>
        <div class="clr"></div>
        <div class="clr"></div>
      </div>
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
