
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="19" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="20" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update ; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="dataTable" class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $msgtemplate['name'];?></strong>
              <input type="hidden" name="id" value="<?php echo $msgtemplate['id']; ?>" /></td>
              <td><?php echo $text_subjtag; ?></td>
              <td><input type="text" name="subjtag" tabindex="8" value="<?php echo $msgtemplate['subjtag'];?>" size="30" /></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td><textarea rows="6" cols="50" name="comment" tabindex="2"><?php echo $msgtemplate['comment'];?></textarea></td>
              <td><?php echo $text_subject; ?></td>
              <td><input type="text" name="subject" tabindex="9" value="<?php echo $msgtemplate['subject'];?>" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo $text_company; ?></td>
              <td><?php echo $company['name']; ?></td>
              <td><?php echo $text_greeting; ?></td>
              <td><textarea rows="5" cols="80" name="greeting" tabindex="10"><?php echo $msgtemplate['greeting'];?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $text_active; ?></td>
              <td><input type="checkbox" name="active" tabindex="5" <?php echo $msgtemplate['active'];?>/></td>
              <td colspan="2"><?php echo $text_URL_before_hdr; ?>
              <input type="checkbox" name="URLhdr" tabindex="11" <?php echo $msgtemplate['bfrHDR'];?>/></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_bodyhdr; ?></td>
              <td><textarea rows="8" cols="80" name="bodyhdr" tabindex="12"><?php echo $msgtemplate['bodyhdr'];?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td colspan="2"><?php echo $text_URL_before_body; ?>
              <input type="checkbox" name="URLbdy" tabindex="13" <?php echo $msgtemplate['bfrMDL'];?>/></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_body; ?></td>
              <td><textarea rows="8" cols="80" name="body" tabindex="14" <?php echo $msgtemplate['bfrHDR'];?>><?php echo $msgtemplate['body'];?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td colspan="2"><?php echo $text_URL_before_ftr; ?>
              <input type="checkbox" name="URLftr" tabindex="15" <?php echo $msgtemplate['bfrFTR'];?>></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_bodyftr; ?></td>
              <td><textarea rows="8" cols="80" name="bodyftr" tabindex="16"><?php echo $msgtemplate['bodyftr'];?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td colspan="2"><?php echo $text_URL_before_sgnt; ?>
              <input type="checkbox" name="URLsgnt" tabindex="17" <?php echo $msgtemplate['bfrSGNT'];?>/></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_signature; ?></td>
              <td><textarea rows="5" cols="80" name="signature" tabindex="18"><?php echo $msgtemplate['signature'];?></textarea></td>
            </tr>
          </table>
      </form>
  </div>
</div>	
</div>
