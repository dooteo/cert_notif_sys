<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

<div class="box">
    <div class="heading">
    <h1><img src="/admin/theme/img/home.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
    	<div class="calendar">
    		<div class="dashboard-heading"><?php echo $text_calendar;?></div>
    		<div class="dashboard-content"><?php echo $calendar; ?></div>
    	</div>
    	<div class="statistic">
    		<div class="dashboard-heading"><?php echo $text_statistics;?></div>
    		<div class="dashboard-content">popo</div>
    	</div>
    	<div class="latest">
    	</div>
    </div>
</div>

</div>
