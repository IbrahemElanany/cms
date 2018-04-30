<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php Confirm_Login(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<!-- BootStrap Including -->
<link rel="stylesheet" href="css/bootstrap.min.css" />
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/adminstyles.css" />
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
            	<li class="active"><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span>
                &nbsp;DashBoard</a></li>
                <li><a href="AddNewPost.php"><span class="glyphicon glyphicon-list-alt"></span>
                &nbsp;Add New Post</a></li>
                <li><a href="Categories.php"><span class="glyphicon glyphicon-tags"></span>
                &nbsp;Categories</a></li>
                <li><a href="Admins.php"><span class="glyphicon glyphicon-user"></span>
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
                <li><a href="Blog.php?page=1" target="_blank"><span class="glyphicon glyphicon-equalizer"></span>
                &nbsp;Live Blog</a></li>
                <li><a href="Logout.php"><span class="glyphicon glyphicon-log-out"></span>
                &nbsp;Logout</a></li>
            </ul>
  
		</div>
        <!-- Ending of Side Area -->
        <div class="col-sm-10"> <!-- Main Area -->
        	<h1> Admin Dashboard</h1>
            <div><?php echo Message();
				echo SuccessMessage();
			 ?></div>
            <div class="table-responsive">
            	<table class="table table-striped table-hover">
                	<tr>
                    	<th>NO</th>
                        <th>Title</th>
                        <th>Date & Time</th>
                        <th> Author</th>
                        <th>Category</th>
                        <th>Banner</th>
                        <th>Comments</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                    <?php
					global $ConnectionDB;
					$SrNo=0;
					$ViewQuery="SELECT * FROM admin_panel ORDER BY id desc";
					$Execute = mysql_query($ViewQuery);
					while($DataRows=mysql_fetch_array($Execute)){
						$Id=$DataRows['id'];
						$DateTime=$DataRows['datetime'];
						$Title=$DataRows['title'];
						$Category=$DataRows['category'];
						$Admin=$DataRows['author'];
						$Image=$DataRows['image'];
						$Post=$DataRows['post'];
						$SrNo++;
						
					?>
                    <tr>
                    	<td><?php echo $SrNo; ?></td>
                        <td style="color:#5e5eff;"><?php
						if(strlen($Title)>20){
							$Title=substr($Title,0,20).'..';
						}
						 echo $Title; ?></td>
                        <td><?php
						if(strlen($DateTime)>11){
							$DateTime=substr($DateTime,0,11).'..';
						}
						echo $DateTime; ?></td>
                        <td><?php
						if(strlen($Admin)>8){
							$Admin=substr($Admin,0,8).'..';
						}
						echo $Admin; ?></td>
                        <td><?php echo $Category; ?></td>
                        <td><img src="Upload/<?php echo $Image;?>" width="150px"; height="50";></td>
                        <td>
                        <?php
							global $ConnectionDB;
							$QueryApproved="SELECT COUNT(*) FROM comments WHERE admin_panel_id='$Id' AND status = 'ON'";
							$ExecuteApproved=mysql_query($QueryApproved);
							$RowApproved=mysql_fetch_array($ExecuteApproved);
							$TotalApproved=array_shift($RowApproved);
							if($TotalApproved>0){
						?>
                        <span class="label label-success pull-right"><?php echo $TotalApproved;?></span>
                        <?php } ?>
                        
                        <?php
							global $ConnectionDB;
							$QueryUnApproved="SELECT COUNT(*) FROM comments WHERE admin_panel_id='$Id' AND status = 'OFF'";
							$ExecuteUnApproved=mysql_query($QueryUnApproved);
							$RowUnApproved=mysql_fetch_array($ExecuteUnApproved);
							$TotalUnApproved=array_shift($RowUnApproved);
							if($TotalUnApproved>0){
						?>
                        <span class="label label-danger pull-left"><?php echo $TotalUnApproved;?></span>
                        <?php } ?>
                        </td>
                        <td>
                        <a href="EditPost.php?Edit=<?php echo $Id; ?>">
                        <span class="btn btn-warning">Edit</span></a>
                        <a href="DeletePost.php?Delete=<?php echo $Id; ?>">
                        <span class="btn btn-danger">Delete</span></a>
                         </td>
                        <td><a href="FullPost.php?id=<?php echo $Id; ?>" target="_blank">
                        <span class="btn btn-info">Live Preview</span></a></td>
                    </tr>
                    <?php	
					}
					?>
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
