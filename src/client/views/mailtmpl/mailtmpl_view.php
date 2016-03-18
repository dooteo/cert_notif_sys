
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="15" class="button"><?php echo $button_edit; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="16" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update . $msgtemplate['id']; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="dataTable" class="form">
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><strong><?php echo $msgtemplate['name']; ?></strong></td>
              <td><?php echo $text_subjtag; ?></td>
              <td><strong><?php echo $msgtemplate['subjtag']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td><strong><?php echo $msgtemplate['comment']; ?></strong></td>
              <td><?php echo $text_subject; ?></td>
              <td><strong><?php echo $msgtemplate['subject']; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $msgtemplate['company']; ?></strong></td>
              <td><?php echo $text_greeting; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="80"><?php echo $msgtemplate['greeting']; ?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><?php echo $text_URL_before_hdr; ?></td>
              <td><strong><?php echo $msgtemplate['bfrHDR']; ?></strong></td>
            </tr>
            <tr class="notopborder">
              <td><?php echo $text_active; ?></td>
              <td><strong><?php echo $msgtemplate['active']; ?></strong></td>
              <td><?php echo $text_bodyhdr; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="80"><?php echo $msgtemplate['bodyhdr']; ?></textarea></td>
            </tr>
             <tr>
              <td></td>
              <td></td>
              <td><?php echo $text_URL_before_body; ?></td>
              <td><strong><?php echo $msgtemplate['bfrMDL']; ?></strong></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_body; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="80"><?php echo $msgtemplate['body']; ?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><?php echo $text_URL_before_ftr; ?></td>
              <td><strong><?php echo $msgtemplate['bfrFTR']; ?></strong></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_bodyftr; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="80"><?php echo $msgtemplate['bodyftr']; ?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><?php echo $text_URL_before_sgnt; ?></td>
              <td><strong><?php echo $msgtemplate['bfrSGNT']; ?></strong></td>
            </tr>
            <tr class="notopborder">
              <td></td>
              <td></td>
              <td><?php echo $text_signature; ?></td>
              <td><textarea readonly="readonly" rows="8" cols="80"><?php echo $msgtemplate['signature']; ?></textarea></td>
            </tr>
          </table>
      </form>
  </div>
</div>	
</div>
