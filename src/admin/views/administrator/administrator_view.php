<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="/admin/theme/img/vtrs/fireman.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" tabindex="14" class="button"><?php echo $button_edit; ?></a><a href="<?php echo $path_cancel; ?>" tabindex="15" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $path_update . $administrator['id']; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $text_username; ?></td>
              <td><strong><?php echo $administrator['username']; ?></strong></td>
              <td><?php echo $text_active; ?></td>
              <td><strong><?php if ($administrator['active']) { echo $text_active;} else {echo $text_inactive;} ?></strong></td>
            </tr>
            <tr>		
              <td><?php echo $text_firstName; ?></td>
              <td><strong><?php echo $administrator['firstName']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_lastName; ?></td>
              <td><strong><?php echo $administrator['lastName']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td><strong><?php echo $administrator['email']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_phone; ?></td>
              <td><strong><?php echo $administrator['phone']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><?php echo $text_cellphone; ?></td>
              <td><?php echo $administrator['cellphone']; ?></strong></td>
              <td></td>
              <td></td>
            </tr>
          </table>
      </form>
  </div>
</div>
</div>
