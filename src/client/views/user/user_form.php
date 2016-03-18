<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $path_update_pwd . $user['id']; ?>" tabindex="14" class="button"><?php echo $button_passwd; ?></a><a onclick="$('#form').submit();" tabindex="15" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="16" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update; ?>" method="post" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $text_company; ?></td>
              <td><?php echo $company; ?></td>
            </tr>
            <tr>		
              <td><?php echo $text_firstName; ?></td>
              <td><input type="text" name="firstName" tabindex="2" value="<?php echo $user['firstName']; ?>" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_lastName; ?></td>
              <td><input type="text" name="lastName" tabindex="3" value="<?php echo $user['lastName']; ?>" size="30" /></td>
              <td><?php echo $text_company_admin; ?></td>
              <td><?php echo $user['isCompAdmin']; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td><input type="text" name="email" tabindex="6" value="<?php echo $user['email']; ?>" size="30" /></td>
              <td><?php echo $text_active; ?></td>
              <td><input type="checkbox" name="active" tabindex="11" <?php echo $user['active'];?> /></td>
            </tr>
            <tr>
              <td><?php echo $text_phone; ?></td>
              <td><input type="text" name="phone" tabindex="7" value="<?php echo $user['phone']; ?>" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_cellphone; ?></td>
              <td><input type="text" name="cellphone" tabindex="8" value="<?php echo $user['cellphone']; ?>" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
