<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>

<!-- Adding Comment -->
<?php
if(isset($_POST["Submit"])){
	$Name = mysql_real_escape_string($_POST["Name"]);
	$Email = mysql_real_escape_string($_POST["Email"]);
	$Comment = mysql_real_escape_string($_POST["Comment"]);
	// to get timezone 
	date_default_timezone_set("Africa/Cairo");
	$Currenttime = time();
	$datetime = strftime("%B-%d-%Y %H:%M:%S",$Currenttime);
	$datetime;
	$PostId=$_GET['id'];
	if(empty($Name)||empty($Email)||empty($Comment)){
		$_SESSION["ErrorMessage"]= "All Fields Must Be Filled Out";
		
	}elseif(strlen($Comment)>500){
		$_SESSION["ErrorMessage"]= "Comment should be less than 500 Characters";
	}elseif(!preg_match("/^[A-Za-z. ]*$/",$Name)){
			$_SESSION["ErrorMessage"]= "Only Letters and white spaces are allowed";
		
	}elseif(!preg_match("/[a-zA-Z0-9._-]{3,}@[a-zA-Z0-9._-]{3,}[.]{1}[a-zA-Z0-9._-]{2,}/",$Email)){
			$_SESSION["ErrorMessage"]= "Invalid Email Format";
	}else{
	// 1- Get Conncetion
		global $ConnectionDB;
		
	// 2- Query
		$Query = "INSERT INTO comments (datetime,name,email,comment,approvedby,status,admin_panel_id)
		VALUES ('$datetime','$Name','$Email','$Comment','Pending','OFF','$PostId')";
		$Execute = mysql_query($Query);
		if($Execute){
			$_SESSION["SuccessMessage"]= "Comment Submitted Successfully ";
			redirect_to("FullPost.php?id={$PostId}");	
		}else{
			$_SESSION["ErrorMessage"]= "Comment Failed to Submit";
			redirect_to("FullPost.php?id={$PostId}");	
			
		}	
	}
}
function test_user_name($data){
		return($data);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Full Post</title>
<!-- BootStrap Including -->
<link rel="stylesheet" href="css/bootstrap.min.css" />
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/publicstyles.css" />
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
<div class="container"> <!-- Container -->
	<div class="blog-header">
    	<h1>The complete php CMS</h1>
        <p class="lead">the complete blog using php</p>
    </div>
    <div><?php echo Message();
				echo SuccessMessage();
			 ?></div>
    <div class="row">
    	<div class="col-sm-8"> <!-- Main Blog Area-->
        	<?php
				global $ConnectionDB;
				if(isset($_GET['SearchButton'])){
					$Search =$_GET['Search'];
				$ViewQuery="SELECT * FROM admin_panel WHERE 
				datetime LIKE '%$Search%'
				OR title LIKE '%$Search%'
				OR category LIKE '%$Search%'
				OR author LIKE '%$Search%'
				OR post LIKE '%$Search%'";
				}elseif(isset($_GET["category"])){
					$Categories=$_GET["category"];
					$ViewQuery = "SELECT * FROM admin_panel WHERE category='$Categories' ORDER BY datetime desc";
				}else{
				$PostIdUrl=$_GET['id'];
				$ViewQuery = "SELECT * FROM admin_panel WHERE id ='$PostIdUrl' ORDER BY datetime desc";}
				$Execute = mysql_query($ViewQuery);
				while($DataRows=mysql_fetch_array($Execute)){
					$PostId=$DataRows['id'];
					$DateTime=$DataRows['datetime'];
					$Title=$DataRows['title'];
					$Category=$DataRows['category'];
					$Admin=$DataRows['author'];
					$Image=$DataRows['image'];
					$Post=$DataRows['post'];
			?>
            <div class="thumbnail blogpost">
            	<img class="img-responsive img-rounded" src="Upload/<?php echo $Image; ?>" />
                <div class="caption">
                	<h1 id="heading"><?php echo htmlentities($Title); ?></h1>
                    <p class="description"> Category : <?php echo htmlentities($Category);?>
                     Published on : <?php echo htmlentities($DateTime);?></p>
                     <p class="post"> <?php echo nl2br($Post) ?></p>
                </div>
            </div>
            <?php	
				}
			?>
            <!-- Comment Form -->
            <div>
            <br><br>
            <span class="FieldInfo">Comments</span>
            <br><br>
            <?php
			global $ConnectionDB;
			$CommentsId=$_GET['id'];
			$CommentQuery="SELECT * FROM comments WHERE admin_panel_id='$CommentsId' AND status='ON'";
			$Execute=mysql_query($CommentQuery);
			while($DataRows=mysql_fetch_array($Execute)){
				$CommentDateTime=$DataRows['datetime'];
				$CommenterName=$DataRows['name'];
				$Comments=$DataRows['comment'];
			?>
            <div class="CommentBlock">
            <img class="UserImage pull-left img-rounded" src="images/headshot.jpg">
            <p class="CommentInfo"><?php echo $CommenterName?></p>
            <p class="Commentdescription"><?php echo $CommentDateTime?></p>
            <p class="Comment"><?php echo nl2br($Comments)?></p>
            </div>
            <br>
            <hr>
			<?php
			}
			?>
            <br><br>
            <span class="FieldInfo">Share Your Thoughts On Page</span>
            <br><br>
            <form action="FullPost.php?id=<?php echo $PostId?>" method="post">
            	<fieldset>
                    <div class="form-group">
                        <label for="name"><span class="FieldInfo">Name :</span></label>
                        <input class="form-control" type="text" name="Name" id="name">
                    </div>
          			<div class="form-group">
                        <label for="email"><span class="FieldInfo">Email :</span></label>
                        <input class="form-control" type="email" name="Email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="commentarea"><span class="FieldInfo">Comment :</span></label>
                        <textarea class="form-control" name="Comment" id="commentarea" ></textarea>
                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" name="Submit" value="Submit Comment">
                </fieldset>
                <br>
            </form>
            </div> <!-- Ending Comment Form -->
        </div> <!-- Ending Main Blog Area-->
        <div class="col-sm-offset-1 col-sm-3">
        	<h1 style="margin-left:20px;">About me</h1>
            <img src="images/me.jpg" class="imageicon img-responsive img-circle"  /><br>
        	<a href="https://drive.google.com/file/d/1btOrbkn_nGh9jpUg8UJLOWjwwjaaVIgn/view" 
            target="_blank"><h4 id="heading">Ibrahim Moustsfa Alanany</h4></a>
            <p class="text-justify">I am a hard working, honest individual. I am a good timekeeper,
             always willing to learn new skills. I am friendly, helpful and polite, have a good sense of humour.
              I am able to work independently in busy environments and also within a team setting
              . I am outgoing and tactful, and able to listen effectively when solving problems.</p>
            <!-- Category Panel -->
            <div class="panel panel-primary">
            	<div class="panel-heading">
                	<h2 class="panel-title">Categories</h2>
                </div>
                <div class="panel-body">
<?php 
global $ConnectionDB;
$CategoriesQuery="SELECT * FROM category";
$CategoriesExecute=mysql_query($CategoriesQuery);
while($DataRows=mysql_fetch_array($CategoriesExecute)){
	$CategoryName=$DataRows['name'];
?>
<a href="Blog.php?category=<?php echo $CategoryName?>">
	<span id="heading"><?php echo $CategoryName."<br>";?></span>
</a>
<?php
}
?>
                </div>
                <div class="panel-footer"></div>
            </div>
            <!-- Recent Posts Panel -->
            <div class="panel panel-primary">
            	<div class="panel-heading">
                	<h2 class="panel-title">Recent Posts</h2>
                </div>
                                <div class="panel-body">
<?php 
global $ConnectionDB;
$ViewQuery="SELECT * FROM admin_panel ORDER BY id desc LIMIT 0,5";
$Execute=mysql_query($ViewQuery);
while($DataRows=mysql_fetch_array($Execute)){
	$Id=$DataRows['id'];
	$Title=$DataRows['title'];
	$DateTime=$DataRows['datetime'];
	$Image=$DataRows['image'];
	if(strlen($DateTime)>11){$DateTime=substr($DateTime,0,11);}
?>
<div>
<img class="pull-left" style="margin-left:10px;margin-top:10px;" 
src="Upload/<?php echo htmlentities($Image);?>" width="70px"; height="50px";>
<a href="FullPost.php?id=<?php echo $Id;?>">
<p id="heading" style="margin-left:90px;"><?php echo htmlentities($Title);?></p>
</a>
<p class="description" style="margin-left:90px;"><?php echo htmlentities($DateTime);?></p><hr>
</div>
<?php
}
?> 
                </div>
                <div class="panel-footer"></div>
        </div> <!-- Ending Side Area-->
    </div> <!-- Ending Row-->
</div><!-- Ending Container-->
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
