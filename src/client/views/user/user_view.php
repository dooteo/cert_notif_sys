<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/assassin.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="14" class="button"><?php echo $button_edit; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><strong><?php echo $user['username']; ?></strong></td>
              <td><?php echo $text_company; ?></td>
              <td><strong><?php echo $company; ?></strong></td>
            </tr>
            <tr>		
              <td><?php echo $text_firstName; ?></td>
              <td><strong><?php echo $user['firstName']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_lastName; ?></td>
              <td><strong><?php echo $user['lastName']; ?></strong></td>
              <td><?php echo $text_company_admin; ?></td>
              <td><strong><?php if ($user['isCompAdmin']) { echo $text_yes;} else {echo $text_no;} ?>
</strong></td>
            </tr>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td><strong><?php echo $user['email']; ?></strong></td>
              <td><?php echo $text_active; ?></td>
              <td><strong><?php if ($user['active']) { echo $text_active;} else {echo $text_inactive;} ?></strong></td>
            </tr>
            <tr>
              <td><?php echo $text_phone; ?></td>
              <td><strong><?php echo $user['phone']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_cellphone; ?></td>
              <td><?php echo $user['cellphone']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  <div class="pagination"><div class="links"><?php echo $pagination; ?></div></div>
  </div>
</div>
</div>
