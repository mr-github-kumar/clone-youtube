<?php

	$con = mysqli_connect('localhost','root','root');

	createDatabase();
	
	//select your database
    mysqli_select_db($con,'youtube');	
	
	createTables();

	function createDatabase(){		
				
		$sql = "CREATE DATABASE IF NOT EXISTS youtube";
		
		global $con;
		
		if ($con->query($sql) == FALSE) {
		  echo "Error creating database: " . $con->error;
		}
	}
	
	function createTables(){	
		global $con;
		$val = mysqli_query($con,'select 1 from video LIMIT 1');
		
		if(!$val){
			$sql = "CREATE TABLE video (
				video_Id INT AUTO_INCREMENT PRIMARY KEY,
				video_Title VARCHAR(200) NULL,
				video_Desc TEXT NULL,
				video_Link VARCHAR(200) NOT NULL)";
			
			
			if ($con->query($sql) == FALSE) {
			  echo "Error creating table: " . $con->error;
			}
		}

		$val1 = mysqli_query($con,'select 1 from comment LIMIT 1');
		
		if(!$val1){
			$sql1 = "CREATE TABLE comment (
				comment_Id INT AUTO_INCREMENT PRIMARY KEY,
				comment_Body TEXT NOT NULL,
				video_Id INT NOT NULL,
				FOREIGN KEY (video_Id)	REFERENCES video(video_Id)  ON UPDATE CASCADE ON DELETE CASCADE)";
			
			
			if ($con->query($sql1) == FALSE) {
			  echo "Error creating table: " . $con->error;
			}
		}			
	}

?>