<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>

<?php
function redirect_to($NewLocation){
	header("Location:".$NewLocation);
		exit;
}
?>

<?php 

function Login_Attempt($UserName,$Password){
	global $ConnectionDB;
	$Query="SELECT * FROM admins WHERE username='$UserName' AND password='$Password'";
	$Execute=mysql_query($Query);
		if($Admin=mysql_fetch_assoc($Execute)){
			return $Admin;	
		}else{
			return null;
		
		
	}
}

function Login(){
	if(isset($_SESSION['UserId'])){
		return true;
	}	
}
function Confirm_Login(){
	if(!Login()){
		$_SESSION["ErrorMessage"]= "Login required !";
		redirect_to("Login.php");
	}	
}
?>