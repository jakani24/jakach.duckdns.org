
  <html>
  <head>
  <title>Upload Folder using PHP </title>
  </head>
  <body>
  <form action="#" method="post" enctype="multipart/form-data"> 
  Type Folder Name:<input type="text" name="foldername" /><br/><br/>
  Select Folder to Upload: <input type="file" name="files[]" id="files" multiple directory="" webkitdirectory="" moxdirectory="" /><br/><br/> 
  <input type="Submit" value="Upload" name="upload" />
  </form>
  </body>
  </html>


  <?php
  if(isset($_POST['upload']))
  {
  	if($_POST['foldername'] != "")
  	{
  		$foldername=$_POST['foldername'];
  		if(!is_dir("test/".$foldername)) mkdir("test/".$foldername);
  		foreach($_FILES['files']['name'] as $i => $name)
		{
  		    if(strlen($_FILES['files']['name'][$i]) > 1)
  		    {  move_uploaded_file($_FILES['files']['tmp_name'][$i],"test/".$foldername."/".$name);
  		    }
  		}
  		echo "Folder is successfully uploaded";
  	}
  	else
  	    echo "Upload folder name is empty";
  }
  ?>
