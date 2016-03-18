<!-- Container -->
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><?php echo $text_click_selection; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
        <table style="width: 100%;">
          <tr>
            <td style="text-align: center;" rowspan="5"><img src="/notif/theme/img/pdf_cert2.png" alt="Por favor introduce los detalles de tu cuenta." /></td>
          </tr>
          <tr>
            <td><?php echo $text_name;?>:</td>
            <td colspan="2"><strong><?php echo $name;?></strong></td>
          </tr>
          <tr>
            <td><?php echo $text_lastnames;?>:</td>
            <td colspan="2"><strong><?php echo $lastname;?></strong></td>
          </tr>
          <tr>
            <td><?php echo $text_identifier;?>:</td>
            <td colspan="2"><strong><?php echo $ident;?></strong></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><hr/></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td style="text-align: left;">
            <form action="<?php echo $path_check; ?>" method="post" id="formA">
            <input type="hidden" name="answer" value="accept"/>
            </form>
            <a onclick="formA.submit();" class="button"><?php echo $button_accept; ?></a>
            </td>
            <td style="text-align: right;">
            <form action="<?php echo $path_check; ?>" method="post" id="formR">
            <input type="hidden" name="answer" value="refuse"/>
            </form>
            <a onclick="formR.submit();" class="button"><?php echo $button_refuse; ?></a>
            </td>
            <td style="text-align: right;">
            <form action="<?php echo $path_check; ?>" method="post" id="formI">
            <input type="hidden" name="answer" value="ignore"/>
            </form>
            <a onclick="formI.submit();" class="button"><?php echo $button_ignore; ?></a>
            </td>
          </tr>
        </table>
    </div>
  </div>
</div>
<!-- End of Container -->
