
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box" style="width: 600px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><img src="/theme/img/pdf.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <form action="<?php echo $path_deny ?>" method="post" id="formB">
        <input type="hidden" name="id" value="<?php echo $notif['id'];?>">
	<table class="form">
          <tr>
            <td style="text-align: center;" rowspan="6"><img src="/theme/img/pdf_cert.png" alt="" /></td>
            <td></td>
          </tr>
          <tr>
            <td><?php echo $text_name; ?>: </td>
            <td><?php echo $text_dni; ?>:</td>
          </tr>
          <tr>
            <td><strong><?php echo $notif['name']; ?></strong></td>
            <td><strong><?php echo $notif['dni']; ?></strong></td>
          </tr>
          <tr>
            <td><?php echo $text_document; ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="2"><strong><?php echo $notif['document']; ?></strong></td>
          </tr>
          <tr><td></td><td></td></tr>
          <tr class="notopborder">
            <td style="text-align: right;"><br/><br/>
	      <a onclick="$('#formA').submit();" class="button"><?php echo $button_accept; ?></a>
	    </td>
	    <td style="text-align: right;"><br/><br/>
	      <a onclick="$('#formB').submit();" class="button"><?php echo $button_deny; ?></a>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
