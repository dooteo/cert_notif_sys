<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="16" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="17" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><input type="text" name="name" tabindex="1" value="" size="30" /></td>
              <td><?php echo $text_company; ?></td>
              <td><?php echo $companies; ?></td>
            </tr>
            <tr>		
              <td><?php echo $text_comment; ?></td>
              <td><textarea rows="6" cols="50" name="comment" tabindex="2"></textarea></td>
              <td><?php echo $text_active; ?></td>
              <td><input type="checkbox" name="active" tabindex="4" checked="checked" /></td>
            </tr>
            <tr>
              <td><?php echo $text_host; ?></td>
              <td><input type="text" name="host" tabindex="5" value="" size="30" /></td>
              <td><?php echo $text_port; ?></td>
              <td><input type="text" name="port" tabindex="6" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_smtpauth; ?></td>
              <td><input type="checkbox" name="smtpauth" tabindex="7" checked="checked" /></td>
              <td><?php echo $text_smtpsec; ?></td>
              <td>
                <select name="smtpsec" tabindex="8">
              	  <option value=""><?php echo $text_none; ?></option>
              	  <option value="tls" >TLS</option>
              	  <option value="ssl" >SSL</option>
              	</select></td>
            </tr>
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><input type="text" name="username" tabindex="9" value="" size="30" /></td>
              <td><?php echo $text_password; ?></td>
              <td><input type="text" name="password" tabindex="10" value="" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_mailfrom; ?></td>
              <td><input type="text" name="mailfrom" tabindex="11" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_mailfromname; ?></td>
              <td><input type="text" name="mailfromname" tabindex="12" value="" size="30" /></td>
              <td><?php echo $text_wordwrap; ?></td>
              <td><input type="text" name="wordwrap" tabindex="13" value="70" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_mailreplyto; ?></td>
              <td><input type="text" name="mailreplyto" tabindex="14" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_mailreplytoname; ?></td>
              <td><input type="text" name="mailreplytoname" tabindex="15" value="" size="30" /></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
