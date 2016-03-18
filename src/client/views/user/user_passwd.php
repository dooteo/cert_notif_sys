<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="3" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="4" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update_pwd . $user['id']; ?>" method="post" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_password; ?></td>
              <td><input type="password" name="passwd1" tabindex="1" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_password2; ?></td>
              <td><input type="password" name="passwd2" tabindex="2" value="" size="30" /></td>
            </tr>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
