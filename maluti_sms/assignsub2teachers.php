<?php
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assigning Subject to Teachers</title>
	<style>
		
		h2{
			color: #009870;
			margin-left: 15%;
			margin-bottom: 30px;
		}
		a .click{
			background: #009870;
			color: white;
			padding: 12px;
			width: 150px;
			border-radius: 15px;
			border: 0;
			transition: .3s ease;
			cursor: pointer;
		}
		.click:hover{
			background: skyblue;
		}
		a .btn-click{
			background: skyblue;
			color: white;
			width: 60px;
			height: 20px;
			padding: 3px;
			border-radius: 15px;
			border: 0;
			transition: .3s ease;
			cursor: pointer;
		}
		.btn-click:hover{
			background: #009870;
		}
		.content{
			border-radius: 10px 10px 0 0;
			overflow: hidden;
			border-collapse: collapse;
			margin: 25px 0;
			font-size: 0.9em;
			min-width: 400px;
			box-shadow: 0 0 20px rgba(0, 0, 0, .15);
			width: 100%;
		}
		.content thead tr{
			background: #009870;
			color: white;
			text-align: left;
			font-weight: bold;
		}
		.content th,
		.content td{
			padding: 12px 15px;
		}
		.content tbody tr{
			border-bottom: 1px solid #dddddd;
		}
		.content tbody tr:nth-of-type(even){
			background-color: #f3f3f3;
		}
		.content tbody tr:last-of-type{
			border-bottom: 2px solid #009829;
		}

	</style>
</head>
<body>
		<h2>Assigning Subjests to Teachers</h2>
		<a href="addtea2subject.php"><button class="click">ASSIGN SUBJECT</button></a>
		<br>
		<table class="content">
			<thead>
				<tr>
					<th>SubjectID</th>
					<th>Subjectname</th>
					<th>Teachername</th>
					<th>Studentname</th>
					<th>Academicyear</th>
					<th>Added At</th>
					<th>Updated At</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				
			    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');
			    if ($conn->connect_error) {
			        die('Connection Failed: ' . $conn->connect_error);
			    }

			    $query = "SELECT * FROM subjects";
			    $result = $conn->query($query);

			    if(!$result){
			    	die("Query was not successfully executed! ". $connection->error);
			    }

			    while($row = $result->fetch_assoc()){
			    	echo "
				    	<tr>
						<td>$row[subject_id]</td>
						<td>$row[subjectname]</td>
						<td>$row[teachername]</td>
						<td>$row[studentname]</td>
						<td>$row[academicyear]</td>
						<td>$row[added_at]</td>
						<td>$row[updated_at]</td>
						<td>
							<a href='assignsub2teachersupdate.php?subject_id={$row['subject_id']}'><button class='btn-click'>UPDATE</button></a>
						</td>
					</tr>
			    	"; 
			    }

				?>
				
			</tbody>
		</table>
	
</body>
</html>