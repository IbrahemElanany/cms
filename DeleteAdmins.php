<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php
if(isset($_GET['id'])){
	$IdFromUrl=$_GET['id'];
	global $ConnectionDB;
	$Query="DELETE FROM admins WHERE id ='$IdFromUrl'";
	$Execute=mysql_query($Query);
	if($Execute){
			$_SESSION["SuccessMessage"]= "User Deleted Successfully ";
			redirect_to("Admins.php");	
		}else{
			$_SESSION["ErrorMessage"]= "Failed to Delete User";
			redirect_to("Admins.php");	
		}
}
?>