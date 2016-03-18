<!-- Container -->
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><?php echo $text_enter_details; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
        <?php if (isset($error) && $error): ?>
        <div class="warning"><?php echo $text_error;?></div>
	<?php endif; ?>
        <?php echo form_open('common/login/login_user', $attributes) ?>
        <table style="width: 100%;">
          <tr>
            <td style="text-align: center;" rowspan="4"><img src="/admin/theme/img/login.png" alt="Por favor introduce los detalles de tu cuenta." /></td>
          </tr>
          <tr>
            <td><?php echo $text_username;?>:<br />
              <input type="text" name="username" value="" autofocus="autofocus" style="margin-top: 4px;" />
              <br />
              <br />
              <?php echo $text_password;?>:<br />
              <input type="password" name="password" value="" style="margin-top: 4px;" />
              <br />
              <a href="admin/index.php?route=common/forgotten"><?php echo $text_forgot_password;?></a>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="text-align: right;"><a onclick="$('#login_form').submit();" class="button"><?php echo $button_start_session; ?></a></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<!-- End of Container -->
