
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/office-building2.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="14" class="button"><?php echo $button_edit; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="15" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
    <div id="tabs" class="htabs">
    	<a href="#tab-general"><?php echo $text_general; ?></a>
    	<a href="#tab-users"><?php echo $text_users; ?></a>
    	<a href="#tab-engines"><?php echo $text_engines; ?></a>
    </div>
    <form action="<?php echo $path_update ?>" method="post" id="form">
    	<div id="tab-general">
          <table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $company['name']; ?></strong></td>
              <td><?php echo $text_status; ?></td>
              <td><strong><?php echo $company['active'] ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_dirpath; ?></td>
              <td><strong><?php echo $company['dirpath'];?></strong></td>
              <td><?php echo $text_certification; ?></td>
              <td><strong><?php echo $company['mustCert']; ?></strong></td>
            </tr>
            <tr>		
              <td><?php echo $text_nif; ?></td>
              <td><strong><?php echo $company['nif']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_address; ?></td>
              <td><strong><?php echo $company['address']; ?></strong></td>
              <td><?php echo $text_phone1; ?></td>
              <td><strong><?php echo $company['phone1']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_postcode; ?></td>
              <td><strong><?php echo $company['postcode']; ?></strong></td>
              <td><?php echo $text_phone2; ?></td>
              <td><strong><?php echo $company['phone2']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_city; ?></td>
              <td><strong><?php echo $company['city']; ?></strong></td>
              <td><?php echo $text_email1; ?></td>
              <td><strong><?php echo $company['email1']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_state; ?></td>
              <td><strong><?php echo $company['state']; ?></strong></td>
              <td><?php echo $text_email2; ?></td>
              <td><strong><?php echo $company['email2']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_country; ?></td>
              <td><strong><?php echo $company['country']; ?></strong></td>
              <td><?php echo $text_website; ?></td>
              <td><strong><?php echo $company['website']; ?></strong></td>
            </tr>
          </table>
          </div> <!-- tab-general -->
          <div id="tab-users">
  	    <table class="list">
              <thead>
	      <tr>
	       <!-- <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>-->
	        <td>&nbsp;</td>
	        <td class="left"><?php echo $column_username; ?></td>
	        <td class="right"><?php echo $column_name; ?></td>
	        <td class="right"><?php echo $column_status; ?></td>
	        <td class="right"><?php echo $column_action; ?></td>
	      </tr>
	      </thead>
	    <tbody>
	<?php if ($users) { $i = 1;?>
		<?php foreach ($users as $user) { ?>
		<tr>
		  <!--<td style="text-align: center;">
		    <input type="checkbox" name="selected[]" value="<?php echo $user['id']; ?>" />
		  </td>-->
		  <td> <?php echo $i++;?> </td>
		  <td class="left"><?php echo $user['username']; ?></td>
		  <td class="right"><?php echo $user['firstName'] . " " . $user['lastName']; ?></td>
		  <td class="right"><?php if ($user['active']) {echo $text_active;}else {echo $text_inactive;} ?>
		  </td>
		  <td class="right">
		      [ <a href="<?php echo $path_user_view . $user['id'];?>"><?php echo $text_view; ?></a> ] - 
		      [ <a href="<?php echo $path_user_update . $user['id'];?>"><?php echo $text_edit; ?></a> ]</td>
		</tr>
		<?php } ?>
	<?php } else { ?>
		<tr>
		  <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
		</tr>
	<?php } ?>
		</tbody>
	      </table>
          </div>  <!-- tab-users -->
          <div id="tab-engines">
            <table class="list">
              <thead>
	      <tr>
	        <!--<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>-->
	        <td>&nbsp;</td>
	        <td class="left"><?php echo $column_name; ?></td>
	        <td class="right"><?php echo $column_status; ?></td>
	        <td class="right"><?php echo $column_action; ?></td>
	      </tr>
	    </thead>
	    <tbody>
	<?php if ($engines) { $i=1; ?>
		<?php foreach ($engines as $engine) { ?>
		<tr>
		 <!-- <td style="text-align: center;">
		    <input type="checkbox" name="selected[]" value="<?php echo $engine['id']; ?>" />
		  </td>-->
		  <td> <?php echo $i++;?> </td>
		  <td class="left"><?php echo $engine['name']; ?></td>
		  <td class="right"><?php if ($engine['active']) {echo $text_active;}else {echo $text_inactive;} ?>
		  </td>
		  <td class="right">
		      [ <a href="<?php echo $path_engine_view . $engine['id'];?>"><?php echo $text_view; ?></a> ] - 
		      [ <a href="<?php echo $path_engine_update . $engine['id'];?>"><?php echo $text_edit; ?></a> ]</td>
		</tr>
		<?php } ?>
	<?php } else { ?>
		<tr>
		  <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
		</tr>
	<?php } ?>
		</tbody>
	      </table>
          </div>  <!-- tab-engines -->

      </form>
  </div>
</div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
