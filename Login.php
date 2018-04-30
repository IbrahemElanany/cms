<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php
if(isset($_POST["Submit"])){
	$UserName = mysql_real_escape_string($_POST["UserName"]);
	$Password = mysql_real_escape_string($_POST["Password"]);
	if(empty($UserName)||empty($Password)){
		$_SESSION["ErrorMessage"]= "All Fields Must Be Filled Out";
		redirect_to("Login.php");
		
	}elseif(strlen($Password)<4){
		$_SESSION["ErrorMessage"]= "Password At least 4 characters";
		redirect_to("Login.php");
	}else{
	$Found_Account=Login_Attempt($UserName,$Password);
	if($Found_Account){
		$_SESSION['UserId']=$Found_Account['id'];
		$_SESSION['UserName']=$Found_Account['username'];
		$_SESSION["SuccessMessage"]= "Welcome {$_SESSION['UserName']} ";
			redirect_to("dashboard.php");	
	}else{
			$_SESSION["ErrorMessage"]= "Invalid User / Password";
			redirect_to("Login.php");	
	
	}
	}
}elseif(isset($_POST["ForgotPassword"])){
	$UserName=$_POST['UserName'];
	if(empty($UserName)){
		$_SESSION["ErrorMessage"]= "Enter UserName And try To Login First";
		redirect_to("Login.php");
		
	}elseif(!empty($UserName)&&empty($Password)){
		global $ConnectionDB;
		$SelectQuery = "SELECT * FROM admins WHERE username='$UserName'";
		$Execute=mysql_query($SelectQuery);
		if($Execute){
		while($DateRows=mysql_fetch_array($Execute)){
			$MyPassword=$DateRows['password'];
			//mail
			$emailTo="hema.3nany@gmail.com";
			 $subject="Login Attempt";
			 $body="Your Password Is ".$MyPassword;
			 $headers="From:iroms@gmail.com";
				 if (mail($emailTo, $subject, $body, $headers)) {
							$_SESSION["SuccessMessage"]= "Ckeck Your Mail : hem...@gmail.com for your Password";
						redirect_to("Login.php");	
					}else{
						$_SESSION["ErrorMessage"]= "Failed to Send mail Try Again";
						redirect_to("Login.php");	
					}
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
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
body{
	background-color:#FFFFFF;
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
           
       </div>
    </div>
</nav>
<div class="Line"></div>

<div class="container-fluid">
	<div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        <br><br><br><br>
        <div><?php echo Message();
				echo SuccessMessage();
			 ?></div>
             <br><br>
        	<h2> Welcome back !</h2>
            <form action="Login.php" method="post">
            	<fieldset>
                    <div class="form-group">
                        <label for="UserName"><span class="FieldInfo">UserName :</span></label>
                        <div class="input-group input-group-lg">
                        <span class="input-group-addon">
                        	<span class="glyphicon glyphicon-envelope text-primary"></span>
                        </span>
                        <input class="form-control" type="text" name="UserName" id="UserName" placeholder="UserName">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Password"><span class="FieldInfo">Password :</span></label>
                        <div class="input-group input-group-lg">
                        <span class="input-group-addon">
                        	<span class="glyphicon glyphicon-lock text-primary"></span>
                        </span>
                        <input class="form-control" type="password" name="Password" 
                        id="Password"  placeholder="password">
                        </div>
                    </div>
                    <br>
                    <input class="btn btn-info btn-block" type="submit" name="Submit" value="Login"><br>
                    <div class="field-group">
                    <div><input class="btn btn-danger btn-block" type="submit" name="ForgotPassword" 
                    id="forgot-password" value="Forget Password !"  class="form-submit-button"></div>
                </div>
                </fieldset>
                <br>
            </form>
            
        </div>
        <!-- Ending of Main Area -->
    </div>
    <!-- Ending of Row -->
</div>
<!-- Ending of Container-Fluid -->
</body>

</html>
