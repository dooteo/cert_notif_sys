<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="11" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="12" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><input type="text" name="username" tabindex="1" value="" size="30" />
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $company; ?></strong></td>
            </tr>
            <tr>		
              <td><?php echo $text_firstName; ?></td>
              <td><input type="text" name="firstName" tabindex="2" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_lastName; ?></td>
              <td><input type="text" name="lastName" tabindex="3" value="" size="30" /></td>
              <td><?php echo $text_company_admin; ?></td>
              <td><input type="checkbox" name="isCompAdmin" tabindex="9" /></td>
            </tr>
            <tr>
              <td><?php echo $text_password; ?></td>
              <td><input type="password" name="passwd1" tabindex="4" value="" size="30" /></td>
              <td><?php echo $text_active; ?></td>
              <td><input type="checkbox" name="active" tabindex="10" checked="checked" /></td>
            </tr>
            <tr>
              <td><?php echo $text_password2; ?></td>
              <td><input type="password" name="passwd2" tabindex="5" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td><input type="text" name="email" tabindex="6" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_phone; ?></td>
              <td><input type="text" name="phone" tabindex="7" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_cellphone; ?></td>
              <td><input type="text" name="cellphone" tabindex="8" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
