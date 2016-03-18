
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><?php echo $btn_data; ?></div>
    </div>
    <div class="content">
    <form action="<?php echo $path_update ?>" method="post" id="form">
	  <table class="form">
	    <tr>
	      <td><?php echo $text_name; ?></td>
	      <td><strong><?php echo $company['name']; ?></strong></td>
	       <td><?php echo $text_active; ?></td>
	       <td><strong><?php if ($company['active']) { echo $text_active;} else {echo $text_inactive;} ?>
	       </strong></td>
	    </tr>
	    <tr>		
	      <td><?php echo $text_nif; ?></td>
	      <td><strong><?php echo $company['nif']; ?></strong></td>
	      <td></td>
	      <td></td>
	    </tr>
	    <tr>
	      <td><?php echo $text_address; ?></td>
	      <td><strong><?php echo $company['address']; ?></strong></td>
	      <td><?php echo $text_phone1; ?></td>
	      <td><strong><?php echo $company['phone1']; ?></strong></td>
	    </tr>
	    <tr>
	      <td><?php echo $text_postcode; ?></td>
	      <td><strong><?php echo $company['postcode']; ?></strong></td>
	      <td><?php echo $text_phone2; ?></td>
	      <td><strong><?php echo $company['phone2']; ?></strong></td>
	    </tr>
	    <tr>
	      <td><?php echo $text_city; ?></td>
	      <td><strong><?php echo $company['city']; ?></strong></td>
	      <td><?php echo $text_email1; ?></td>
	      <td><strong><?php echo $company['email1']; ?></strong></td>
	    </tr>
	    <tr>
	      <td><?php echo $text_state; ?></td>
	      <td><strong><?php echo $company['state']; ?></strong></td>
	      <td><?php echo $text_email2; ?></td>
	      <td><strong><?php echo $company['email2']; ?></strong></td>
	    </tr>
	    <tr>
	      <td><?php echo $text_country; ?></td>
	      <td><strong><?php echo $company['country']; ?></strong></td>
	      <td><?php echo $text_website; ?></td>
	      <td><strong><?php echo $company['website']; ?></strong></td>
	    </tr>
	  </table>
      </form>
  </div>
</div>
</div>
