<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/cert.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="2" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="3" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
      		<input type="hidden" name="up" value="1"/>
          <table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><input type="text" value="" name="name" tabindex="1" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_cert_file; ?></td>
              <td><input type="file" value="" name="upfile" tabindex="1" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_tsa_url; ?></td>
              <td><input type="text" value="" name="url" tabindex="1" size="30"/></td>
              <td><?php echo $text_hash_type; ?></td>
              <td><?php echo $hashtypes; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_identifier; ?></td>
              <td><input type="text" value="" name="identifier" tabindex="1" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_secret; ?></td>
              <td><input type="text" value="" name="secret" tabindex="1" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
