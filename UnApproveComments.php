<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php
if(isset($_GET['id'])){
	$IdFromUrl=$_GET['id'];
	global $ConnectionDB;
	$Query="UPDATE comments	SET status='OFF' WHERE id ='$IdFromUrl'";
	$Execute=mysql_query($Query);
	if($Execute){
			$_SESSION["SuccessMessage"]= "Comment UnApproved Successfully ";
			redirect_to("Comments.php");	
		}else{
			$_SESSION["ErrorMessage"]= "Failed to UnApprove Comment";
			redirect_to("Comments.php");	
		}
}
?>