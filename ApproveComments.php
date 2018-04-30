<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php
if(isset($_GET['id'])){
	$IdFromUrl=$_GET['id'];
	global $ConnectionDB;
	$Admin = $_SESSION['UserName'];
	$Query="UPDATE comments	SET status='ON',approvedby='$Admin' WHERE id ='$IdFromUrl'";
	$Execute=mysql_query($Query);
	if($Execute){
			$_SESSION["SuccessMessage"]= "Comment Approved Successfully ";
			redirect_to("Comments.php");	
		}else{
			$_SESSION["ErrorMessage"]= "Failed to Approve Comment";
			redirect_to("Comments.php");	
		}
}
?>