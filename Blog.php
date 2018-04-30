<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Blog Page</title>
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
                <li><a href="dashboard.php">Home</a></li>
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
    <div class="row">
    	<div class="col-sm-8"> <!-- Main Blog Area-->
        	<?php
				global $ConnectionDB;
				// Query When Search Button is Active
				if(isset($_GET['SearchButton'])){
					$Search =$_GET['Search'];
				$ViewQuery="SELECT * FROM admin_panel WHERE 
				datetime LIKE '%$Search%'
				OR title LIKE '%$Search%'
				OR category LIKE '%$Search%'
				OR author LIKE '%$Search%'
				OR post LIKE '%$Search%' ORDER BY id desc ";
				}
				// Query When Pagination is Active
				elseif(isset($_GET["category"])){
					$Categories=$_GET["category"];
					$ViewQuery = "SELECT * FROM admin_panel WHERE category='$Categories' ORDER BY id desc";
				}
				elseif(isset($_GET["page"])){
					$Page = $_GET["page"];
					if($Page<=0){
						$ShowPostFrom=0;
					}else{
					$ShowPostFrom = ($Page*5)-5;
					}
					$ViewQuery = "SELECT * FROM admin_panel ORDER BY id desc LIMIT $ShowPostFrom,5";
				}
				// The Default Query For Blog.php Page
				else{
				$ViewQuery = "SELECT * FROM admin_panel ORDER BY id desc LIMIT 0,5";}
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
                     Published on : <?php echo htmlentities($DateTime);?>
                     <!-- Extract Number Fo Comments -->
                     <?php
							global $ConnectionDB;
							$QueryApproved="SELECT COUNT(*) FROM comments WHERE admin_panel_id='$PostId' AND status = 'ON'";
							$ExecuteApproved=mysql_query($QueryApproved);
							$RowApproved=mysql_fetch_array($ExecuteApproved);
							$TotalApproved=array_shift($RowApproved);
							if($TotalApproved>0){
						?>
                        <span class="badge pull-right">Comments : <?php echo $TotalApproved;?></span>
                        <?php } ?>
                     </p>
                     <p class="post"> <?php
					 if(strlen($Post)>150){
						$Post=substr($Post,0,150)."..."; 
					 }
					  echo $Post ?></p>
                </div>
                <a href="FullPost.php?id=<?php echo $PostId;?>">
                	<span class="btn btn-info">Read more &rsaquo;&rsaquo;</span>
                </a>
            </div>
            <?php	
				}
			?>
            <!-- Pagination with Forward and Backward -->
            <nav>
            <ul class="pagination pagination-lg pull-left">
            <!-- backward Button on pagination -->
			<?php 
			if(isset($Page)){
			if($Page>1){
			?>
            <li><a href="Blog.php?page=<?php echo $Page-1;?>">&laquo;</a></li>
			<?php	
			}}
			?>
            <?php
			// Create Pagination
			global $ConnectionDB;
			$QueryPagination ="SELECT COUNT(*) FROM admin_panel";
			$Execute=mysql_query($QueryPagination);
			$RowPagination=mysql_fetch_array($Execute);
			$TotalPosts=array_shift($RowPagination);
			$NumberOfPages=ceil($TotalPosts/5);
			for($i=1;$i<=$NumberOfPages;$i++){
			if(isset($Page)){
				if($i==$Page){
					
			?>
            <li class="active">
            <a href="Blog.php?page=<?php echo $i;?>"><?php echo $i;?></a>
            </li>
            <?php }else{ ?>
            <li>
            <a href="Blog.php?page=<?php echo $i;?>"><?php echo $i;?></a>
            </li>
			<?php
			}
			}
			}
			?>
            <!-- forward Button on pagination -->
			<?php 
			if(isset($Page)){
			if($Page<$NumberOfPages){
			?>
            <li><a href="Blog.php?page=<?php echo $Page+1;?>">&raquo;</a></li>
			<?php	
			}}
			?>
            </ul>
            </nav>
        </div> <!-- Ending Main Blog Area-->
        <div class="col-sm-offset-1 col-sm-3">
        	<h1 style="margin-left:20px;">About me</h1>
            <img src="images/me.jpg" class="imageicon img-responsive img-circle"  /><br>
            <a href="https://drive.google.com/file/d/1btOrbkn_nGh9jpUg8UJLOWjwwjaaVIgn/view" 
            target="_blank"><h4 id="heading">Ibrahim Moustsfa Alanany</h4></a>
            <p class="text-justify">I am a hard working, honest individual. I am a good timekeeper, always willing to learn new skills. I am friendly, helpful and polite, have a good sense of humour. I am able to work independently in busy environments and also within a team setting. I am outgoing and tactful, and able to listen effectively when solving problems.</p>
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
            </div>
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
