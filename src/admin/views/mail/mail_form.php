
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="12" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="13" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
    <form action="<?php echo $path_update; ?>" method="post" id="form">
    	<table class="form">
            <tr>
              <td><?php echo $text_SMTP_host; ?></td>
              <td><input class="inputbox" type="text" name="MailHost" value="<?php echo $mailconf['host']; ?>" tabindex="1" size="30"/></td>
              <td><?php echo $text_SMTP_port; ?></td>
              <td><input class="inputbox" type="text" name="MailPort" value="<?php echo $mailconf['port']; ?>" tabindex="2" size="5"/></td>  
            </tr>
            <tr>
              <td><?php echo $text_SMTP_auth; ?></td>
              <td><input type="checkbox" name="MailSMTPAuth" value="true" tabindex="3" 
              	  <?php 
              	  if (strcmp($mailconf['SMTP_auth'], "true") == 0) {
              	  	echo 'checked="checked" ';
              	  } 
              	  ?> 
              	  /></td>
              <td><?php echo $text_SMTP_sec; ?></td>
              <td>
                <select name="MailSMTPSec" tabindex="4">
              	  <option value="" <?php if ($mailconf['SMTP_sec'] == "") echo 'selected="selected"'; ?>><?php echo $text_none; ?></option>
              	  <option value="tls" <?php if (strcmp($mailconf['SMTP_sec'], "tls") == 0) echo 'selected="selected"'; ?>>TLS</option>
              	  <option value="ssl" <?php if (strcmp($mailconf['SMTP_sec'], "ssl") == 0) echo 'selected="selected"'; ?>>SSL</option>
              	</select></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_user; ?></td>
              <td><input class="inputbox" type="text" name="MailSMTPuser" value="<?php echo $mailconf['SMTP_user']; ?>" tabindex="5" size="30"/></td>
              <td><?php echo $text_SMTP_passwd; ?></td>
              <td><input class="inputbox" type="text" name="MailSMTPpasswd" value="<?php echo $mailconf['SMTP_pswd']; ?>" tabindex="6" size="30"/></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_From; ?></td>
              <td><input class="inputbox" type="text" name="MailFrom" value="<?php echo $mailconf['From']; ?>" tabindex="7" size="30"/></td> 
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_FromName; ?></td>
              <td><input class="inputbox" type="text" name="MailFromName" value="<?php echo $mailconf['FromName']; ?>" tabindex="8" size="30"/></td>
              <td><?php echo $text_WordWrap; ?></td>
              <td><input class="inputbox" type="text" name="WordWrap" value="<?php echo $mailconf['WrodWrap']; ?>" tabindex="11" size="5"/></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_ReplyTo; ?></td>
              <td><input class="inputbox" type="text" name="MailReplyTo" value="<?php echo $mailconf['ReplyTo']; ?>" tabindex="9" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_ReplyToName; ?></td>
              <td><input class="inputbox" type="text" name="MailReplyToName" value="<?php echo $mailconf['ReplyToName']; ?>" tabindex="10" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
    </div>
  </div>
</div>
