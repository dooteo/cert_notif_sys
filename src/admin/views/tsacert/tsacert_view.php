<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/cert.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $path_delete; ?>" tabindex="15" class="button"><?php echo $button_delete; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="15" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
    	<a href="#tab-settings"><?php echo $text_settings; ?></a>
    	<a href="#tab-tsa_info"><?php echo $text_tsa_info; ?></a>
    </div>
    <form action="<?php echo $path_update; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-settings">
            <table class="form">
              <tr>
                <td><?php echo $text_name; ?></td>
                <td><strong><?php echo $name; ?></strong></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td><?php echo $text_tsa_url; ?></td>
                <td><strong><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></strong></td>
                <td><?php echo $text_hash_type; ?></td>
              <td><strong><?php echo $hashtype; ?></strong></td>
              </tr>
              <tr>
                <td><?php echo $text_identifier; ?></td>
                <td><strong><?php echo $identifier; ?></strong></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td><?php echo $text_secret; ?></td>
                <td><strong><?php echo $secret; ?></strong></td>
                <td></td>
                <td></td>
              </tr>
            </table>
          </div> <!-- tab-settings -->
          <div id="tab-tsa_info">
          <table class="form">
            <tr>
              <td colspan="2"><h1><?php echo $ident; ?></h1></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_identity; ?></td>
              <td><strong><?php echo $ident; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_verif_by; ?></td>
              <td><strong><?php echo $verif_by; ?></strong></td>
              <td><?php echo $text_not_valid_before; ?></td>
              <td><strong><?php echo $valid_not_before; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_expires; ?></td>
              <td><strong><?php echo $expires; ?></strong></td>
              <td><?php echo $text_not_valid_after; ?></td>
              <td><strong><?php echo $valid_not_after; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_version; ?></td>
              <td><strong><?php echo $version; ?></strong></td>
              <td><?php echo $text_serial_number; ?></td>
              <td><strong><?php echo $serialNumber; ?></strong></td>
            </tr>
            <tr>
              <td colspan="2"><h3><?php echo $text_theme_name; ?></h3></td>
              <td colspan="2"><h3><?php echo $text_issuer_name; ?></h3></td>
            </tr>
            <tr>
              <td><?php echo $text_c_country; ?></td>
              <td><strong><?php echo $t_C; ?></strong></td>
              <td><?php echo $text_c_country; ?></td>
              <td><strong><?php echo $i_C; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_o_organization; ?></td>
              <td><strong><?php echo $t_O; ?></strong></td>
              <td><?php echo $text_o_organization; ?></td>
              <td><strong><?php echo $i_O; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_ou_ogr_unit; ?></td>
              <td><strong><?php echo $t_OU; ?></strong></td>
              <td><?php echo $text_ou_ogr_unit; ?></td>
              <td><strong><?php echo $i_OU; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_cn_common_name; ?></td>
              <td><strong><?php echo $t_CN; ?></strong></td>
              <td><?php echo $text_cn_common_name; ?></td>
              <td><strong><?php echo $i_CN; ?></strong></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><?php echo $text_email; ?></td>
              <td><strong><?php echo $email; ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_nsCaRevocUrl; ?></td>
              <td><strong><?php echo $nsCaRevocationUrl; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_nsRevocUrl; ?></td>
              <td><strong><?php echo $nsRevocationUrl; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
          </div> <!-- tab-general -->
      </form>
  </div>
</div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
