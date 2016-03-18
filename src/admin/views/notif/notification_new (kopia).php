<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/calc.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="6" class="button"><?php echo $button_save; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="7" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_insert; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="dataTable" class="form">
            <tr>
              <td><?php echo $text_pdf_filename; ?></td>
              <td><input type="file" value="" name="pdfupfile" tabindex="2" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_csv_filename; ?></td>
              <td><input type="file" value="" name="csvupfile" tabindex="2" size="30"/></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td colspan="3"><textarea rows="5" cols="100" tabindex="3" name="comment"></textarea></td>
            </tr>
            <tr>
              <td><?php echo $text_company; ?></td>
              <td><?php echo $companies; ?></td>
              <td></td>
              <td></td>
            </tr>
            <tr class="notopborder">
              <td><?php echo $text_msg_template; ?></td>
              <td colspan="3"><div id="msgtmpls"><?php echo $msgtemplates; ?></div></td>
            </tr>
          </table>
      </form>
  </div>
</div>	
</div>
