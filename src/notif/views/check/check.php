<!-- Container -->
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><?php echo $text_enter_identification; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
      <table style="width: 100%;">
        <tr>
          <td style="text-align: center;" rowspan="4"><img src="/notif/theme/img/receipt.png" alt="Por favor introduce los detalles de tu cuenta." /></td>
        </tr>
        <tr>
          <td><strong><?php echo $text_identifier;?></strong>:<br />
            <form action="<?php echo $path_check; ?>" method="post" id="form">
            <input type="text" name="ident" value="" style="margin-top: 4px;" size="20"/></form>
            <br />
            <i><?php echo $text_description;?></i>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
         <td style="text-align: right;"><a href="#" onclick="form.submit();" class="button"><?php echo $button_access; ?></a></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<!-- End of Container -->
