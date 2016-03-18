<!-- Container -->
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><?php echo $text_download_docum; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
      <table style="width: 100%;">
        <tr>
          <td style="text-align: center;" rowspan="3"><img src="/notif/theme/img/pdf_cert2.png" alt="Por favor introduce los detalles de tu cuenta." /></td>
          <td colspan="2">&nbsp;</td>
        </tr>
         <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
         <tr>
          <td>&nbsp;</td>
          <td style="text-align: center;"><a href="<?php echo $path_download; ?>" 
          onclick="form.submit();" class="button"><?php echo $button_download; ?></a></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $text_download_description;?> </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<!-- End of Container -->
