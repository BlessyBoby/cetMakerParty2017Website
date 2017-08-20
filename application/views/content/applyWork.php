<?php
  if(empty($artName))
  {
    $artName="";
  }
  if(empty($artShortDescription))
  {
    $artShortDescription="";
  }
  if(empty($artLongDescription))
  {
    $artLongDescription="";
  }
  if(empty($patronClub))
  {
    $patronClub="";
  }
 ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sub/apply.css.php">
<div class="row">
  <div class="col-md-4">
    <div class="form_main">
      <h3 class="heading">Apply for your work.<span></span></h3>
      <div class="form">
        <form action="<?php echo base_url() ?>apply/complete" method="post" id="applicationForm" name="applicationForm" enctype="multipart/form-data" accept-charset="utf-8">
          <input id="inputWorkName" type="text" required="true" placeholder="Name of your work." value="<?php echo $artName; ?>" name="workName">
          <input id="inputWorkName" type="text" required="true" placeholder="Short description." value="<?php echo $artShortDescription; ?>" name="workShortDescription">
          <textarea id="textareaWorkDescription" required="true" placeholder="Long description." name="workLongDescription" type="text"><?php echo $artLongDescription; ?></textarea>
          <p style="margin-top: 20px;">Upload an image with ratio: 38:35. Upload high quality image if possible.</p>
          <span style="color:red;"><?php echo $error1;?></span>
          <input type="file" name="userfile1" required="true" size="20" />
          <p style="margin-top: 30px;">Upload an image with ratio: 16:3. Upload high quality image if possible.</p>
          <span style="color:red;"><?php echo $error2;?></span>
          <input type="file" name="userfile2" required="true" size="20" />
          <input type="submit" value="Apply" name="Apply" style="margin-top: 30px;">
        </form>
    </div>
  </div>
</div>
</div>
