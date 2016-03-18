
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/pdf.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $path_csv . $engine['id']; ?>" tabindex="1" class="button"><?php echo $button_csv; ?></a><a onclick="$('#form').submit();" tabindex="2" class="button"><?php echo $button_edit; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="3" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
    <form action="<?php echo $path_update. $engine['id'] ?>" method="post" id="form">
    	<table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $engine['name']; ?></strong></td>
               <td><?php echo $text_active; ?></td>
               <td><strong><?php if ($engine['active']) { echo $text_active;} else {echo $text_inactive;} ?>
               </strong></td>
            </tr>
            <tr>
              <td><?php echo $text_filename; ?></td>
              <td><strong><?php echo $engine['filename']; ?></strong></td>
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $engine['company']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td><strong><?php echo $engine['comment']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_fields; ?></td>
              <td><strong><?php echo $engine['fields']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
