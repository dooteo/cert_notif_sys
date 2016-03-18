<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $path_insert; ?>" class="button"><?php echo $button_new; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_delete; ?>" method="post" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="right"><?php echo $column_company; ?></td>
              <td class="right"><?php echo $column_status; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($mailtmpls) { ?>
            <?php foreach ($mailtmpls as $template) { ?>
            <tr>
              <td style="text-align: center;">
              	<input type="checkbox" name="selected[]" value="<?php echo $template['id']; ?>" />
              </td>
              <td class="left"><?php echo $template['name']; ?></td>
              <td class="right">[ <a href="<?php echo $path_company . $template['compId'];?>"><?php echo $template['company']; ?></a> ]</td>
              <td class="right"><?php if ($template['active']) {echo $text_active;}else {echo $text_inactive;} ?>
              [ <a href="
              <?php  
              	if ($template['active']) {
              		echo $path_desactivate . $template['id'] . '">' . $text_desactivate;
              	} else {
			echo $path_activate . $template['id'] . '">' . $text_activate;
              	} 
              ?></a> ] 
              </td>
              <td class="right">
              [ <a href="<?php echo $path_view . $template['id'];?>"><?php echo $text_view; ?></a> ] - 
              [ <a href="<?php echo $path_update . $template['id'];?>"><?php echo $text_edit; ?></a> ]</td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><div class="links"><?php echo $pagination; ?></div></div>
    </div>
  </div>
</div>
