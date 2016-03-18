<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/office-building2.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="15" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="16" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><input type="text" name="name" tabindex="1" value="" size="30" />
              <td><?php echo $text_active; ?></td>
              <td><input type="checkbox" name="active" tabindex="8" checked="checked" /></td>
            </tr>
            <tr>		
              <td><?php echo $text_nif; ?></td>
              <td><input type="text" name="nif" tabindex="2" value="" size="30" /></td>
              <td><?php echo $text_certification; ?></td>
              <td><input type="checkbox" name="certif" tabindex="9" checked="checked" /></td>
            </tr>
            <tr>
              <td><?php echo $text_address; ?></td>
              <td><input type="text" name="address" tabindex="3" value="" size="30" /></td>
              <td><?php echo $text_phone1; ?></td>
              <td><input type="text" name="phone1" tabindex="10" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_postcode; ?></td>
              <td><input type="text" name="postcode" tabindex="4" value="" size="30"  /></td>
              <td><?php echo $text_phone2; ?></td>
              <td><input type="text" name="phone2" tabindex="11" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_city; ?></td>
              <td><input type="text" name="city" tabindex="5" value="" size="30" /></td>
              <td><?php echo $text_email1; ?></td>
              <td><input type="text" name="email1" tabindex="12" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_state; ?></td>
              <td><input type="text" name="state" tabindex="6" value="" size="30" /></td>
              <td><?php echo $text_email2; ?></td>
              <td><input type="text" name="email2" tabindex="13" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_country; ?></td>
              <td><input type="text" name="country" tabindex="7" value="" size="30" /></td>
              <td><?php echo $text_website; ?></td>
              <td><input type="text" name="website" tabindex="14" value="" size="30" /></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
