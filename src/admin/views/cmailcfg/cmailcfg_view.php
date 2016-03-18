<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="1" class="button"><?php echo $button_edit; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="2" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update . $mailcfg['id']; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $mailcfg['name']; ?></strong></td>
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $company; ?></strong></td>
            </tr>
            <tr>		
              <td><?php echo $text_comment; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="60"><?php echo $mailcfg['comment']; ?></textarea></td>
              <td><?php echo $text_active; ?></td>
              <td><strong><?php if ($mailcfg['active']) { echo $text_yes;} else {echo $text_no;} ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_host; ?></td>
              <td><strong><?php echo $mailcfg['host']; ?></strong></td>
              <td><?php echo $text_port; ?></td>
              <td><strong><?php echo $mailcfg['port']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_smtpauth; ?></td>
              <td><strong><?php if ($mailcfg['active']) { echo $text_yes;} else {echo $text_no;} ?></strong></td>
              <td><?php echo $text_smtpsec; ?></td>
              <td><strong><?php echo $mailcfg['SMTPsec']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><strong><?php echo $mailcfg['username']; ?></strong></td>
              <td><?php echo $text_password; ?></td>
              <td><strong><?php echo $mailcfg['password']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_mailfrom; ?></td>
              <td><strong><?php echo $mailcfg['MailFrom']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_mailfromname; ?></td>
              <td><strong><?php echo $mailcfg['MailFromName']; ?></strong></td>
              <td><?php echo $text_wordwrap; ?></td>
              <td><strong><?php echo $mailcfg['WordWrap']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_mailreplyto; ?></td>
              <td><strong><?php echo $mailcfg['MailReplyTo']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_mailreplytoname; ?></td>
              <td><strong><?php echo $mailcfg['MailReplyToName']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
