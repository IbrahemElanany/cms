<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php Confirm_Login(); ?>
<?php
if(isset($_POST["Submit"])){
	$UserName = mysql_real_escape_string($_POST["UserName"]);
	$Password = mysql_real_escape_string($_POST["Password"]);
	$ConfirmPassword = mysql_real_escape_string($_POST["ConfirmPassword"]);
	// to get timezone 
	date_default_timezone_set("Africa/Cairo");
	$Currenttime = time();
	$datetime = strftime("%B-%d-%Y %H:%M:%S",$Currenttime);
	$datetime;
	$Admin = $_SESSION['UserName'];
	if(empty($UserName)||empty($Password)||empty($ConfirmPassword)){
		$_SESSION["ErrorMessage"]= "All Fields Must Be Filled Out";
		redirect_to("Admins.php");
		
	}elseif(strlen($Password)<4){
		$_SESSION["ErrorMessage"]= "Password At least 4 characters";
		redirect_to("Admins.php");
	}elseif($Password !==$ConfirmPassword){
		$_SESSION["ErrorMessage"]= "Password / ConfirmPassword Does Not Match";
		redirect_to("Admins.php");
	}else{
	// 1- Get Conncetion
		global $ConnectionDB;
		
	// 2- Query
		$Query = "INSERT INTO admins (datetime,username,password,addedby) 
		VALUES ('$datetime','$UserName','$Password','$Admin')";
		$Execute = mysql_query($Query);
		if($Execute){
			$_SESSION["SuccessMessage"]= "New Admin Added Successfully ";
			redirect_to("Admins.php");	
		}else{
			$_SESSION["ErrorMessage"]= "Admin Failed to Add";
			redirect_to("Admins.php");	
		}	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Admins</title>
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
                <li><a href="AddNewPost.php"><span class="glyphicon glyphicon-list-alt"></span>
                &nbsp;Add New Post</a></li>
                <li><a href="Categories.php"><span class="glyphicon glyphicon-tags"></span>
                &nbsp;Categories</a></li>
                <li class="active"><a href="Admins.php"><span class="glyphicon glyphicon-user"></span>
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
                <li><a href="Blog.php" target="_blank"><span class="glyphicon glyphicon-equalizer"></span>
                &nbsp;Live Blog</a></li>
                <li><a href="Logout.php"><span class="glyphicon glyphicon-log-out"></span>
                &nbsp;Logout</a></li>
            </ul>
  
		</div>
        <!-- Ending of Side Area -->
        <div class="col-sm-10">
        	<h1> Manage Admins</h1>
            <div><?php echo Message();
				echo SuccessMessage();
			 ?></div>
            <form action="Admins.php" method="post">
            	<fieldset>
                    <div class="form-group">
                        <label for="UserName"><span class="FieldInfo">UserName :</span></label>
                        <input class="form-control" type="text" name="UserName" id="UserName" placeholder="UserName">
                    </div>
                    <div class="form-group">
                        <label for="Password"><span class="FieldInfo">Password :</span></label>
                        <input class="form-control" type="password" name="Password" 
                        id="Password"  placeholder="password">
                    </div>
                    <div class="form-group">
                        <label for="ConfirmPassword"><span class="FieldInfo">Confirm Password :</span></label>
                        <input class="form-control" type="password" name="ConfirmPassword" 
                        id="ConfirmPassword" placeholder="Retype the same password">
                    </div>
                    <br>
                    <input class="btn btn-success btn-block" type="submit" name="Submit" value="Add New Admin">
                </fieldset>
                <br>
            </form>
            <div class="table-responsive">
            	<table class="table table-striped table-hover">
                	<tr>
                    	<th>Sr.NO</th>
                        <th>Date & Time</th>
                        <th>Admin Name</th>
                        <th>Added By</th>
                        <th>Action</th>
                    </tr>
                    <?php
					//1- Establish Connection
					global $ConnectionDB;
					//2- View Query
					$ViewQuery = "SELECT * FROM admins ORDER BY id desc";
					$Execute = mysql_query($ViewQuery);
					$SrNo=0;
					while($DataRows = mysql_fetch_array($Execute)){
						$Id=$DataRows["id"];
						$DateTime=$DataRows["datetime"];
						$UserName=$DataRows["username"];
						$Admin=$DataRows["addedby"];
						$SrNo++;
					?>
                    <tr>
                    	<td><?php echo $SrNo; ?></td>
                        <td><?php echo $DateTime; ?></td>
                        <td><?php echo $UserName; ?></td>
                        <td><?php echo $Admin; ?></td>
                        <td><a href="DeleteAdmins.php?id=<?php echo $Id;?>">
                        <span class="btn btn-danger">Delete</span>
                        </a></td>
                    </tr>    
                    <?php } ?>
                </table>
            </div>
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
