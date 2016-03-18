<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/pdf.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="6" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="7" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="dataTable" class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><input type="text" name="name" tabindex="1" value="" size="30" /></td>
              <td><?php echo $text_active; ?></td>
               <td><input type="checkbox" name="active" tabindex="5" checked="checked" /></td>
            </tr>
            <tr>
              <td><?php echo $text_filename; ?></td>
              <td><input type="file" value="" name="upfile" tabindex="2" size="30"/></td>
              <td><?php echo $text_file_maxsize; ?></td>
              <td><strong><?php echo $file_max_size; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_company; ?></td>
              <td><?php echo $companies; ?></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td colspan="3"><textarea rows="5" cols="100" tabindex="4" name="comment"></textarea></td>
            </tr>
          </table>
      </form>
  </div>
</div>	
</div>
