
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="2" class="button"><?php echo $button_edit; ?></a></div>
    </div>
    <div class="content">
    <form action="<?php echo $path_update; ?>" method="post" id="form">
    	<table class="form">
            <tr>
              <td><?php echo $text_SMTP_host; ?></td>
              <td><strong><?php echo $mailconf['host']; ?></strong></td>
               <td><?php echo $text_SMTP_port; ?></td>
               <td><strong><?php echo $mailconf['port']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_user; ?></td>
              <td><strong><?php echo $mailconf['SMTP_user']; ?></strong></td>
               <td><?php echo $text_SMTP_auth; ?></td>
               <td><strong><?php echo $mailconf['SMTP_auth']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_passwd; ?></td>
              <td><strong><?php echo $mailconf['SMTP_pswd']; ?></strong></td>
              <td><?php echo $text_SMTP_sec; ?></td>
              <td><strong>
                <?php 
                if ($mailconf['SMTP_sec'] == "") {
                	echo $text_none;
                } else { 
                	echo strtoupper($mailconf['SMTP_sec']);
                }?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_From; ?></td>
              <td><strong><?php echo $mailconf['From']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_FromName; ?></td>
              <td><strong><?php echo $mailconf['FromName']; ?></strong></td>
              <td><?php echo $text_WordWrap; ?></td>
              <td><strong><?php echo $mailconf['WrodWrap']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_ReplyTo; ?></td>
              <td><strong><?php echo $mailconf['ReplyTo']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_SMTP_ReplyToName; ?></td>
              <td><strong><?php echo $mailconf['ReplyToName']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
    </div>
  </div>
</div>
