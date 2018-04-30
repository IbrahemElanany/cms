<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php Confirm_Login(); ?>
<?php
if(isset($_POST["Submit"])){
	$Title = mysql_real_escape_string($_POST["Title"]);
	$Category = mysql_real_escape_string($_POST["Category"]);
	$Post = mysql_real_escape_string($_POST["Post"]);
	// to get timezone 
	date_default_timezone_set("Africa/Cairo");
	$Currenttime = time();
	$datetime = strftime("%B-%d-%Y %H:%M:%S",$Currenttime);
	$datetime;
	$Admin = "Ibrahim";
	$Image=$_FILES["Image"]["name"];
	$Target = "Upload/".basename($_FILES["Image"]["name"]);
	
	// 1- Get Conncetion
		global $ConnectionDB;
		
	// 2- Query
		$DeleteFromUrl=$_GET['Delete'];
		$Query = "DELETE FROM admin_panel WHERE id='$DeleteFromUrl'";
		$Execute = mysql_query($Query);
		
		// to move images from a folder to the specific folder
		move_uploaded_file($_FILES["Image"]["tmp_name"],$Target);
		if($Execute){
			$_SESSION["SuccessMessage"]= "Post Deleted Successfully ";
			redirect_to("dashboard.php");	
		}else{
			$_SESSION["ErrorMessage"]= "Post Failed to Delete";
			redirect_to("dashboard.php");	
		}	
	}

?>
<!DOCTYPE html>
<html>
<head>
<title>Delete Post</title>
<!-- BootStrap Including -->
<link rel="stylesheet" href="css/bootstrap.min.css" />
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/adminstyles.css" />
<style>
.FieldInfo{
	color: rgb(251, 174, 44);
        font-family: Bitter,Georgia,"Times New Roman",Times,serif;
        font-size: 1.5em;
	
}
</style>
</head>

<body>
<div id="line"></div>
<nav class="navbar navbar-inverse" role="navigation">
	<div class="container">
    	<div class="navbar-header">
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
            	<span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
				<img id="brand-img" src="images/logo.png"  />           
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li><a href="#">Home</a></li>
                <li class="active"><a href="Blog.php">Blog</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Feature</a></li>
            </ul>
            <form action="Blog.php" class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" name="Search" />
                </div>
                <button class="btn btn-default" name="SearchButton">Go</button>
            </form>
       </div>
    </div>
</nav>
<div class="Line"></div>
<div class="container-fluid">
	<div class="row">
    	<div class="col-sm-2">
        <br><br>
            <ul id="Side_Menu" class="nav nav-pills nav-stacked">
            	<li><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span>
                &nbsp;DashBoard</a></li>
                <li class="active"><a href="AddNewPost.php"><span class="glyphicon glyphicon-list-alt"></span>
                &nbsp;Add New Post</a></li>
                <li><a href="Categories.php"><span class="glyphicon glyphicon-tags"></span>
                &nbsp;Categories</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-user"></span>
                &nbsp;Manage Admins</a></li>
                <li><a href="Comments.php"><span class="glyphicon glyphicon-comment"></span>
                &nbsp;Comments
                <?php
					global $ConnectionDB;
					$QueryTotal="SELECT COUNT(*) FROM comments WHERE status = 'OFF'";
					$ExecuteTotal=mysql_query($QueryTotal);
					$RowTotal=mysql_fetch_array($ExecuteTotal);
					$Total=array_shift($RowTotal);
					if($Total>0){
					?>
                     <span class="label pull-right label-warning"><?php echo $Total;?></span>
                    <?php } ?> </a>
                </li>
                <li><a href="Blog.php?page=1"><span class="glyphicon glyphicon-equalizer"></span>
                &nbsp;Live Blog</a></li>
                <li><a href="Logout.php"><span class="glyphicon glyphicon-log-out"></span>
                &nbsp;Logout</a></li>
            </ul>
  
		</div>
        <!-- Ending of Side Area -->
        <div class="col-sm-10">
        	<h1> Delete Post</h1>
            <div><?php echo Message();
				echo SuccessMessage();
			 ?></div>
             <?php
						//1- Establish Connection
						global $ConnectionDB;
						//2- Select Query
						$UpdateRecord=$_GET['Delete'];
						$ShowQuery = "SELECT * FROM admin_panel WHERE id='$UpdateRecord'";
						$Execute = mysql_query($ShowQuery);
						while($DataRows = mysql_fetch_array($Execute)){
							$Id=$DataRows['id'];
							$UploadedTitle=$DataRows['title'];
							$UploadedImage=$DataRows['image'];
							$UploadedCategory=$DataRows['category'];
							$UploadedPost=$DataRows['post'];
						}
							?>
            <form action="DeletePost.php?Delete=<?php echo $UpdateRecord;?>" method="post" enctype="multipart/form-data">
            	<fieldset>
                    <div class="form-group">
                        <label for="title"><span class="FieldInfo">Title :</span></label>
                        <input disabled class="form-control" type="text" name="Title" id="title" 
                        value="<?php echo $UploadedTitle?>">
                    </div>
                    <div class="form-group">
                    	<span class="FieldInfo">Existing Category : </span>
                        <?php echo $UploadedCategory;?>
                        <br>
                        <label for="categoryselect"><span class="FieldInfo">Category :</span></label>
                        <select disabled class="form-control" id="categoryselect" name="Category" >
                          <?php
						//1- Establish Connection
						global $ConnectionDB;
						//2- Select Query
						$ViewQuery = "SELECT * FROM category ORDER BY datetime desc";
						$Execute = mysql_query($ViewQuery);
						while($DataRows = mysql_fetch_array($Execute)){
							$CategoryName=$DataRows["name"];
							?>
                         <option><?php echo $CategoryName; ?></option>   
						<?php } ?> 
                        </select>
                    </div>
                    <div class="form-group">
                    <span class="FieldInfo">Existing Image : </span>
                        <img src="Upload/<?php echo $UploadedImage;?>" width="170"; height="70";>
                        <br>
                        <label for="imageselect"><span class="FieldInfo">Select Image :</span></label>
                        <input disabled type="File" class="form-control" name="Image" id="imageselect">
                    </div>
                    <div class="form-group">
                        <label for="postarea"><span class="FieldInfo">Post :</span></label>
                        <textarea disabled class="form-control" name="Post" id="postarea" ><?php echo $UploadedPost;?></textarea>
                    </div>
                    <br>
                    <input class="btn btn-danger btn-block" type="submit" name="Submit" value="Delete Post">
                </fieldset>
                <br>
            </form>
            <?php 
			if(isset($_POST["Submit"])){
				
				}
			?>
        </div>
        <!-- Ending of Main Area -->
    </div>
    <!-- Ending of Row -->
</div>
<!-- Ending of Container-Fluid -->
<div id="Footer">
<hr><p>Theme By | Ibrahim Alanany |&copy;2018-2020 --- All right reserved.
</p>
<a style="color: white; text-decoration: none; cursor: pointer; font-weight:bold;" 
href="https://www.facebook.com/ibrahem.elanany" target="_blank">
<p>
This site is only used for Study purpose Ibrahim Alanany have all the rights. no one is allow to distribute
copies  </p><hr>
</a>
	
</div>
<!-- Ending of Footer -->
<div style="height:10px; background:#27aae1;"></div>
</body>

</html>
