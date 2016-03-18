<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/pdf.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="3" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="4" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update . $engine['id']; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="dataTable" class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $engine['name']; ?></strong></td>
              <td><?php echo $text_active; ?></td>
               <td><strong><?php echo $engine['active']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_old_filename; ?></td>
              <td><strong><?php echo $engine['filename'];?></strong></td>
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $engine['company']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_new_filename; ?></td>
              <td><input type="file" value="" name="upfile" tabindex="1" size="30"/></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td colspan="3"><textarea rows="5" cols="100" tabindex="2" name="comment"><?php echo $engine['comment'];?></textarea></td>
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
