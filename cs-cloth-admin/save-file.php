<?php
 include_once("library/uploader.php");
 include("library/imageManager.php");
 
 $u = new Uploader();
 $u->max_file_size = 100000000;
 $u->allowed_extensions = array('jpg','png','gif','bmp');
 $u->temp_path = "temp";
 $u->upload_path = "../img/cloth";
 if(isset($_FILES['productimg'])){
	 $newName = $_POST['new_name'];
	 $uploaded_fle_name = $u->uploadFile("productimg", $newName);
	 $u->displayErrors();
	 $uploadedImagepath = "../img/cloth/" . $uploaded_fle_name;
	 //Saves original image 
	 $uploadedImageFilename = pathinfo($uploadedImagepath,PATHINFO_FILENAME);
       $ext = pathinfo($uploadedImagepath,4);
	 if($uploaded_fle_name != ""){
		 sleep(1);
	     ?><script type="text/javascript">window.top.window.uploadFinished("<?php echo $uploadedImageFilename .".". $ext; ?>");</script><?php 
	 }
	 else{
		 ?><script type="text/javascript">window.top.window.uploadFinished("0");</script><?php 
	 }
 }
?>