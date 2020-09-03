<?php 
	include('db.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-auto-replace-svg></script>
</head>
<body>
<div class="border-bottom overflow-hidden">
	<h4 class="mb-2 ml-4">channel videos</h4>
	<div class="tab ml-3">
	  <button type="button" class="btn btn-link tablinks text-left text-dark" data-toggle="modal" data-target="#uploadFiles">Uploads</button>
	  <button class="btn btn-link tablinks text-left text-dark">Live</button>
	</div>
</div>
<div class="m-2 overflow-hidden">
	<i class="fa fa-filter ml-4"></i>
	<input class="ml-4 col-lg-10" type="text" placeholder="Filter">
</div>
<div>
	<table class="table">		
		<thead>
		<tr>
		  <th scope="col"><input type="checkbox"></th>
		  <th scope="col">Video</th>
		  <th scope="col">Title</th>
		  <th scope="col">Description</th>
		  <th scope="col">Date</th>
		  <th scope="col">Views</th>
		  <th scope="col">Edit</th>
		  <th scope="col">Delete</th>
		</tr>
	  </thead>
	  <tbody>	
	  <?php 
		$query = "SELECT * FROM video";
		$result = mysqli_query($con, $query);

		if($result){		
			while($row = mysqli_fetch_assoc($result)) { ?>
			<tr>
				<form action="" method="POST">
					<input type="hidden" name="id" value="<?php echo $row["video_Id"]; ?>" >
					<td scope="row"><input type="checkbox"></td>	
					<td>
						<a href="youtubevideos.php?id=<?php echo $row["video_Id"]; ?>" target="_blank">
							<video width="150" height="100" class="video-fluid" autoplay loop muted controls>
							<source src="videos/<?php echo $row["video_Link"]; ?>" type="video/mp4">
							</video>
						</a>
					</td>
					<td><textarea cols="30" rows="3" class="border-0 col-lg-8" type="text" name="title" ><?php echo $row["video_Title"]; ?></textarea></td>
					<td><textarea cols="30" rows="4" class="border-0 col-lg-10" type="text" name="desc" /><?php echo $row["video_Desc"]; ?></textarea></td>			
					<td>05/11/2020</td>
					<td>800</td>
					<td><input class="border-0" type="submit" name="edit" value="Edit" ></td>
					<td><input class="border-0" type="submit" name="delete" value="Delete" ></td>
					<?php
						if(isset($_POST['edit']) || isset($_POST['delete'])){
							header('Location: '.$_SERVER['REQUEST_URI']);
						}
					?>
				</form>
			</tr>			
		<?php } } ?>
	  </tbody>
	</table>
	<hr>
	<div class="text-right">
		<span>Rows per page: 30 
		<i class="fa fa-caret-down ml-2 mr-2"></i>
		<span class="mr-2">1-1 of 1</span>
		<i class="fa fa-step-backward  mr-2"></i>
		<i class="fa fa-chevron-left  mr-2"></i>
		<i class="fa fa-chevron-right  mr-2"></i>
		<i class="fa fa-step-forward  mr-2"></i>
		</span>
	</div>
	<hr>
	<?php 
		$query = "SELECT * FROM video";
		$result = mysqli_query($con, $query);
		if(mysqli_num_rows($result) < 1){ ?>
			<div class="text-center">
				<img src="img/upload_video.svg"/><br/>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadFiles">UPLOAD VIDEOS</button>
			</div>
	<?php } ?>
</div>

<div class="modal" id="uploadFiles" role="dialog">
    <div id="fileModal" class="modal-dialog modal-dialog-centered">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
		  <h4 class="modal-title">Upload videos</h4>
		  <a href="#" class="btn border-0 align-top text-dark">
			<i class="fa fa-rocket"></i>
			<span class="font-weight-normal">UPLOAD WITH CLASSIC</span>
		  </a>
          <button type="button" class="close" data-dismiss="modal">&times;</button>          
        </div>
        <div class="modal-body">
         <div id="">
			<form method="POST" action="" enctype="multipart/form-data">
				<span>Title: <input type="text" name="title"/></span><br/><br/>
				<span>Description: <input type="text" name="desc"/></span><br/><br/>
				<input class="btn btn-primary" type="file" name="file"/>
				<input type="submit" name="upload" value="UPLOAD"/>	
				<?php
					if(isset($_POST['upload'])){
						header('Location: '.$_SERVER['REQUEST_URI']);
					}
				?>			
			</form>
		 </div>
        </div>
        <div class="modal-footer">
          By submitting your videos to YouTube, you acknowledge that you agree to YouTube's Terms of Service and Community Guidelines.
		  Please make sure that you do not violate others' copyright or privacy rights. Learn more
        </div>
      </div>
      
    </div>
</div>
</body>
</html>
<?php
	if(isset($_POST['upload'])){
		//echo print_r($_POST); exit;
		$name = $_FILES['file']['name'];
		$temp = $_FILES['file']['tmp_name'];
		$title = $_POST['title'];
		$desc = $_POST['desc'];
				
		move_uploaded_file($temp,"videos/".$name);
		
		$sql = "INSERT INTO video (video_Title, video_Desc,video_Link)
		VALUES ('$title', '$desc', '$name')";
		
		//$res = mysqli_query($con, $sql);
		
		if ($con->query($sql) === FALSE) {
			  echo "Error: " . $sql . "<br>" . $con->error;
		}
			
		$con->close();
	}

	if(isset($_POST['delete'])){
		$Id=$_POST['id'];
		deleteRecords($Id);
		
	}

	if(isset($_POST['edit'])){
		$Id = $_POST['id'];
		$title = $_POST['title'];
		$desc = $_POST['desc'];
		updateRecords($title, $desc,$Id);
	}

	function updateRecords($value1, $value2, $value3){		
		$sql ="";
		
		if(!$value1 && !$value2)
		{
			die("cannot update");
		}else{
			$sql="UPDATE video SET ";
			$sql.= !empty($value1)? "video_Title='$value1'":"";
			$sql.= (!empty($value1) && !empty($value2)) ? ", ":"";
			$sql.= !empty($value2)? "video_Desc='$value2'":"";
			$sql.=" WHERE video_Id = $value3";
		}
			
		global $con;
		if ($con->query($sql) === FALSE) {
		  echo "Error: " . $sql . "<br>" . $con->error;
		}
		
		$con->close();
	}
	
	function deleteRecords($option){
		$sql = "DELETE FROM comment where video_Id = $option";
		global $con;
		if ($con->query($sql) === FALSE) {		 
		  echo "Error deleting record: " . $con->error;
		}
		
		$sql = "DELETE FROM video where video_Id = $option";		
		
		
		if ($con->query($sql) === FALSE) {		 
		  echo "Error deleting record: " . $con->error;
		}		
		$con->close(); 		
	}
	

?>