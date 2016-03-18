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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="<?php echo $base_link_path;?>theme/css/styles.css">
  <script type="text/javascript" src="<?php echo $base_link_path;?>/views/js/jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_link_path;?>/views/js/tabs.js"></script>

  <?php echo $javascript; ?>
  
</head>
<body>
<div id="container">
<header>
 <div class="div1">
    <div class="div2"><a href="/admin/"><strong>Unimail</strong> | Administración</a></div>
    <div class="div3"><img src="/admin/theme/img/vtrs/fireman2.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $name ?></div>
  </div>
  <!-- Menu navigation-->
  <nav >
  <ul id="menu1">
  <?php echo zdn_header_menu($menu, $maxmenu_level); ?>
	<li class="right"><a href="/admin/index.php/common/login/logout_user">Logout</a></li>
  </ul>
  <!-- End of Logout link -->
  </nav>
  <!-- End of Menu navigation-->
</header>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
