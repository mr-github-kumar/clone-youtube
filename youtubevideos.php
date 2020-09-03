<?php
	include('db.php');	
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/mainvideos.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-auto-replace-svg></script>
<title>YouTube-Display</title>
</head>
<body>
<nav id="search" class="navbar navbar-light">
	<div class="navbar-left">
		<button class="navbar-toggler border-0" type="button" data-toggle="collapse">
		  <span class="navbar-toggler-icon"></span>
		</button>
		<button type="button" class="btn btn-link font-weight-bold">
		<span><img id="playIcon" src="img/play.jpg"></img></span><span class="text-dark">Studio</span>
		</button>
	</div>
	<div class="has-search">
		<span class="form-search"><i class="fa fa-search ml-2"></i></span>
		<input type="text" class="form-control" placeholder="Search here">
	</div>
	<div class="navbar-right">
	    <i class="fa fa-plus-square mr-2"></i>
		<i class="fa fa-ellipsis-h mr-2"></i>				
		<i class="fa fa-bell mr-2"></i>
		<i class="fa fa-font"></i>		
	</div>
</nav>
<div id="left" class="d-flex">
	<span id="leftContent">		
		<div class="view mt-4" > 
			<?php
				session_start();
				
				if(isset($_GET["id"])){
					$_SESSION["videoId"] = $_GET["id"];
				}
				$videoId = $_SESSION["videoId"];
				
				$query = "SELECT * FROM VIDEO WHERE video_id=$videoId LIMIT 1";
				
				$result = mysqli_query($con, $query);

				if(!$result)
					die("There is no records to show-left.");
				
				$row = mysqli_fetch_assoc($result);			
				 
			?>	
			<video id="videoScreen" class="video-fluid" autoplay loop muted controls >
			  <source src="videos/<?php echo $row["video_Link"]; ?>" type="video/mp4" />
			</video>
		</div>
		<div>
			<h6 class=""><?php echo $row["video_Desc"]; ?></h6>
			<div class="d-flex border-bottom">
				<small class="mr-auto pr-2 pt-2 pb-2">10,000 views. 11 May 2020</small>
				<div class="p-2">
					<i class="fa fa-thumbs-up ml-3 mr-1"></i><small>1.2k</small>
					<i class="fa fa-thumbs-down ml-3 mr-1"></i><small>50</small>
					<i class="fa fa-share ml-3 mr-1"></i><small>SHARE</small>
					<i class="fa fa-save ml-3 mr-1"></i><small>SAVE</small>
					<i class="fa fa-ellipsis-h ml-3"></i>
				</div>
			</div>
			
		</div>
		<div class="d-flex border-bottom">
			<i class="fa fa-fire fa-3x"></i>
			<small class="ml-3">ShishirKumar.com</small>			
		</div>
		<div>
			<form method="POST" action="youtubevideos.php">
				<div class="d-block border-bottom mt-3">
					<div>
						<small>100 comments</small>
						<a href="#" class="text-dark"><i class="fa fa-sort fa-lg ml-3"></i></a>
						<small class="ml-3">SORT BY</small>
					</div>
					<div class="d-flex">				
						<i class="fa fa-user fa-2x mr-2"></i>
						<textarea name="comments" class="border-0 col-lg-12" placeholder="Add a public comment..."></textarea>
					</div>
				</div>
				<div class="">
					<input type="submit" name="cancel" class="border-0" value="CANCEL"/>
					<input type="submit" name="commentButton" class="border-0" value="COMMENT"/>
				</div>
				<?php
					if(isset($_POST['commentButton'])){
						header('Location: '.$_SERVER['REQUEST_URI']);
					}
				?>
			</form>
		</div>	
		<div>
			<?php					
				$videoId = $_SESSION["videoId"];
				$query = "SELECT * FROM COMMENT WHERE video_id=$videoId";
				
				$result = mysqli_query($con, $query);

				if(!$result)
					die("There is no records to show-comment.");
				
				while($row = mysqli_fetch_assoc($result)) { ?>
				<div class="d-flex border-bottom">
					<i class="fa fa-user fa-2x mr-2"></i>
					<p><?php echo $row["comment_Body"]; ?> <br/>
					<i class="fa fa-thumbs-up mr-2 mt-2 "></i><small>10</small>
					<i class="fa fa-thumbs-down mr-3 ml-2"></i>
					<small class="ml-1">REPLY</small>
					</p>
				</div>
			<?php } ?>	
		</div>

	</span>
	<span id="rightContent">
		<div class="view mt-4" >
		<?php 			
			$query = "SELECT * FROM VIDEO";
			
			$result = mysqli_query($con, $query);

			if(!$result)
				die("There is no records to show.");
			
			$row = mysqli_fetch_assoc($result);
			while($row = mysqli_fetch_assoc($result)) { 
		?>
		
		<a href="#" class="btn border-0 d-flex">
			<div>
				<video id="videoScreenRight" class="video-fluid" autoplay loop muted controls >
			  <source src="videos/<?php echo $row["video_Link"]; ?>" type="video/mp4" />
			</video>
			</div>
			<div class="ml-2 text-dark text-left">
				<small><b><?php echo $row["video_Title"]; ?></b><br/>
				<?php echo $row["video_Desc"]; ?></br>
				109K views. 2 months ago</small>
			</div>
		</a>
		<?php } ?>
		</div>
	</span>
</div>
</body>
</html>
<?php
	
	if(isset($_POST['commentButton'])){		
		$comments = $_POST['comments'];	
		$videoId = $_SESSION["videoId"];
		
		if(isset($videoId) && isset($comments))
		{		
			$sql = "INSERT INTO comment (comment_Body, video_Id)
			VALUES ('$comments', $videoId)";			
			
			if ($con->query($sql) === FALSE) {
				  echo "Error: " . $sql . "<br>" . $con->error;
			}			
			
		}
			
		$con->close();
	}
?>