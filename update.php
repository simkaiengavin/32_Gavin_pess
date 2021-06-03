<!doctype HTML>
<html>
<head>
	<link href="header_style.css" rel="stylesheet" type="text/css">
	<link href="content_style.css" rel="stylesheet" type="text/css">
		<meta charset="utf-8">
		
		<style>
			.button {
			  display: inline-block;
			  padding: 5px 15px;
			  font-size: 20px;
			  cursor: pointer;
			  text-align: center;
			  text-decoration: none;
			  outline: none;
			  color: #fff;
			  background-color: #4CAF50;
			  border: none;
			  border-radius: 15px;
			  box-shadow: 0 9px #999;
			}

			.button:hover {background-color: #3e8e41}

			.button:active {
			  background-color: #3e8e41;
			  box-shadow: 0 5px #666;
			  transform: translateY(4px);
			}
		</style>
		
		<script>
			function validateForm()
				{
					var x=document.forms["frmLogCall"] ["callerName"].value;
					if (x==null || x=="")
						{
							alert("Caller Name is required!");
							return false;
						}
				}
		</script>
	
</head>
<body style="background-color: aquamarine;">
	<?php require_once 'nav.php'; ?>
		<br><br>
	<?php if (!(isset($_POST["btnSearch"])))	{ ?>
		
		<form name="form1" method="post" onSubmit="return validateForm()" 
		action="<?php echo htmlentities($_SERVER['PHP_SELF']);	?>">
			<table class="ContentStyle">
				<tr></tr>
					<tr>
						<td>Patrol Car ID :</td>
						<td><input type="text" name="patrolCarId" id="patrolCarId"></td>
						<td><input type="submit" name="btnSearch" id="btnSearch" value="Search" class="button"></td>
					</tr>
			</table>
	
	<?php	}	
		else	{
			require_once 'db.php';
			
			$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
				if ($mysqli->connect_error)	{
					die("Connection failed! : " . $conn->connect_error);
				}
				
			$sql = "SELECT * FROM patrolcar WHERE patrolcar_id = ?";
			
				if (!($stmt = $mysqli->prepare($sql)))	{
					die("Prepare failed! : " . $mysqli->errno);
				}
				
					if (!($stmt->bind_param('s', $_POST['patrolCarId'])))	{
						die("Binding parameters failed! : " . $stmt->errno);
					}
				
				if (!($stmt->execute()))	{
					die("Execute failed! : " . $stmt->errno);
				}
				
					if (!($resultset = $stmt->get_result()))	{
						die("Getting result set failed! : " . $stmt->errno);
					}
					
				
				if ($resultset->num_rows == 0)	{
					?>
						<script type="text/javascript">window.location="./update.php";</script>
				<?php	}
				
					$patrolCarId;
					$patrolCarStatusId;
					
					while ($row = $resultset->fetch_assoc())	{
						$patrolCarId = $row['patrolcar_id'];
						$patrolCarStatusId = $row['patrolcar_status_id'];
					}
					
					$sql = "SELECT * FROM patrolcar_status";
						if (!($stmt = $mysqli->prepare($sql)))	{
							die("Prepare failed! : " . $mysqli->errno);
						}
						
							if (!($stmt->execute()))	{
								die("Execute failed! : " . $stmt->errno);
							}
				
						if (!($resultset = $stmt->get_result()))	{
							die("Getting result set failed! : " . $stmt->errno);
						}
					
						$patrolCarStatusArray;;
						
						while ($row = $resultset->fetch_assoc())	{
							$patrolCarStatusArray[$row['patrolcar_status_id']] = $row['patrolcar_status_desc'];
						}
						
						$stmt->close	();
						$resultset->close();
						$mysqli->close();
					?>								
					
		<form name="form2" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);	?>	">
				<table class="ContentStyle">
					<tr></tr>
					<tr>
						<td>ID :</td>
						<td><?php echo $patrolCarId	?>
							<input type="hidden"　name="patrolCarId" id="patrolCarId"
							value="<?php echo $patrolCarId	?>">
						</td>
					</tr>
					<tr>
						<td>Status :</td>
						<td><select name="patrolCarStatus" id="patrolCarStatus">
						 <?php foreach($patrolCarStatusArray as $key => $value) { ?>
						  <option value="<?php echo $key ?>"
						 <?php if ($key == $patrolCarStatusId)	{?> selected="selected"
						 <?php	}	?>
					>
						 <?php echo $value ?>
						  </option>
						 <?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><input type="reset" name="btnCancel" id="btnCancel" value="Reset" class="button"></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"
						name="btnUpdate" id="btnUpdate" value="Update" class="button"> </td>
					</tr>
				</table>
			</form>
			
		<?php
		if (isset($_POST["btnUpdate"]))	{
			require_once 'db.php';
			
			$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
			if ($mysqli->connect_errno)	{
				die("Failed to connect to MySQL! : " . $mysqli->connect_errno);
			}
			
				$sql = "UPDATE patrolcar SET patrolcar_status_id = ? WHERE patrolcar_id = ? ";
				if(!($stmt = $mysqli->prepare($sql)))	{
					die("Prepare failed! : " . $mysqli->errno);
				}
				
				if(!($stmt->bind_param('ss', $_POST['patrolCarStatus'], $_POST['patrolCarId'])))	{
					die("Binding parameters failed! : " . $stmt->errno);
				}
				
				if(!($stmt->ezecute()))	{
					die("Updating patrolcar table failed! : " .$stmt->errno);
				}
				
			if ($_POST["patrolCarStatus"] == '4')	{
				
				$sql = "UPDATE dispatch SET time_arrived = NOW()
						WHERE time_arrived is NULL AND patrolcar_id = ?";
						
				if (!($stmt = $mysqli->prepare($sql)))	{
					die("Prepare failed! : " . $mysqli->errno);
				}
					
					if (!($stmt->bind_param('s', $_POST['patrolCarId'])))	{
						die("Binding parameters failed! : " . $stmt->errno);
					}
					
				if (!($stmt->execute()))	{
					die("Updating patrolcar table failed! : ".$stmt->errno);
				}
				
			}	else if ($_POST["patrolCarStatus"] == '3')	{
				
				$sql = "SELECT incident_id FROM dispatch WHERE time_completed is NULL AND patrolcar_id = ?";
				
				if (!($stmt = $mysqli->prepare($sql)))	{
					die("Prepare failed! : " . $mysqli->errno);
				}
					
					if (!($stmt->bind_param('s', $_POST['patrolCarId'])))	{
						die("Binding parameters failed! : " . $stmt->errno);
					}
					
				if (!($stmt->execute()))	{
					die("Updating patrolcar table failed! : ".$stmt->errno);
				}
					
					if (!($resultset = $stmt->get_result()))	{
						die("Getting result set failed! : " . $stmt->errno);
					}
					
				$incidentId;
				
						while ($row = $resultset->fetch_assoc())	{
							$incidentId = $row['incident_id'];
						}
						
							$sql = "UPDATE dispatch SET time_completed = NOW()
							WHERE time_completed IS NULL AND patrolcar_id = ?";
							
							if (!($stmt = $mysqli->prepare($sql)))	{
								die("Prepare failed! : " . $mysqli->errno);
							}
								
								if (!($stmt->bind_param('s', $_POST['patrolCarId'])))	{
									die("Binding parameters failed! : " . $stmt->errno);
								}
								
							if (!($stmt->execute()))	{
								die("Updating patrolcar table failed! : ".$stmt->errno);
							}
							
							$sql = "UPDATE incident SET incident_status_id = '3' WHERE incident_id = '$incidentId'
							AND NOT EXISTS (SELECT * FROM dispatch WHERE time_completed IS NULL AND 
							incident_id = '$incidentId')";
							
							if (!($stmt = $mysqli->prepare($sql)))	{
								die("Prepare failed! : " . $mysqli->errno);
							}
			
								if (!($stmt->execute()))	{
									die("Updating patrolcar table failed! : ".$stmt->errno);
								}
								
							$resultset->close();
			}
			
			$stmt->close();
			$mysqli->close();
			?>
<script type="text/javascript">window.location="./logcall.php";</script>
		<?php	}
				}	?>
</body>
</html>