<!doctype HTML>
<html>
<head>
	<title>Police Emergency Service System</title>
	<meta charset="utf-8">
		<link href="header_style.css" rel="stylesheet" type="text/css">
		<link href="content_style.css" rel="stylesheet" type="text/css">
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
		
<?php if (!isset($_POST["btnProcessCall"]) && !isset($_POST["btnDispatch"]))
{header("Location: logcall.php");}
?>
<?php 
if (isset($_POST["btnDispatch"]))
{require_once 'db.php';
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno)
{ die("Failed to connect to MySQL: " . $mysqli->connect_errno);}
					
$patrolcarDispatched = $_POST["chkPatrolCar"];
$numOfPatrolcarDispatched = count($patrolcarDispatched);
$incidentStatus;
if ($numOfPatrolcarDispatched > 0)
{$incidentStatus = '2'; } 
else
{$incidentStatus = '1'; }
			
$sql = "INSERT INTO incident(caller_name, phone_number, incident_type_id, incident_location, incident_Desc, incident_status_id) VALUES (?, ?, ?, ?, ?, ?)";

if (!($stmt = $mysqli->prepare($sql)))
{die("Prepare failed! incident : " . $mysqli->errno);}

if (!$stmt->bind_param('ssssss', $_POST['callerName'], $_POST['contactNo'], $_POST['incidentType'], $_POST['location'], $_POST['incidentDesc'],	$incidentStatus))
{die("Binding parameters failed! incident : " . $stmt->errno);}

if (!$stmt->execute())	{
						die("Insert incident table failed! : " . $stmt->errno);
					}
// bug 1 start RETRIEVE INCIDENT_ID FOR THE NEWLY INSERTED INCIDENT
//$incidentId=mysqli_insert_id($sqli);;
	$incidentId=mysqli_insert_id($mysqli);;
	//echo $incidentId ;
	//bug 1 end
for($i=0; $i < $numOfPatrolcarDispatched; $i++)
{
 $sql = "UPDATE patrolcar SET patrolcar_status_id='1' WHERE patrolcar_id= ?";
								
if (!($stmt = $mysqli->prepare($sql)))
{ die("Prepare FAILED! : " . $mysqli->errno); }

//bug 2  								
//if(!$stmt = bind_param('s', $patrolcarDispatched[$i]))
 if (!$stmt->bind_param('s', $patrolcarDispatched[$i])){
			die("Binding parameters failed: ".$stmt->errno);
		}
								
								if(!$stmt->execute())	{
									die("Update patrolcar_status table failed! : " . $stmt->errno);
								}
								
							$sql = "INSERT INTO dispatch (incident_id, patrolcar_id, time_dispatched)
							VALUES (?, ?, NOW())";
							
								if (!($stmt = $mysqli->prepare($sql)))	{
									die("Prepare FAILED! : " . $mysqli->errno);
								}
									
									if (!$stmt->bind_param('ss', $incidentId, $patrolcarDispatched[$i])){
										die("Binding parameters failed! : " . $stmt->errno);
									}
								
								if	(!$stmt->execute())	{
									die("Insert dispatch table failed! : ".$stmt->errno);
								}
}
						$stmt->close();
						$mysqli->close();
			?>
		<script type="text/javascript">window.location="./logcall.php";</script>
		<?php } ?>
</head>

<body>
<?php require_once 'nav.php'; ?>

	<form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?> ">
		<table class="ContentStyle">
			<tr>
				<td colspan="2">Incident Detail</td>
			</tr>
			<tr>
				<td>Caller's Name :</td>
				<td><?php echo $_POST['callerName'] ?>
					<input type="hidden" name="callerName" id="callerName"
					value = "<?php echo $_POST['callerName'] ?>"></td>
			</tr>
				<tr>
					<td>Contact No. :</td>
					<td><?php echo $_POST['contactNo'] ?>
						<input type="hidden" name="contactNo" id="contactNo"d
						value = "<?php echo $_POST['contactNo'] ?>"></td>
				</tr>
			<tr>
				<td>Location : </td>
				<td><?php echo $_POST['location'] ?>
					<input type="hidden" name="location" id="location"
					value = "<?php echo $_POST['location'] ?>"></td>
			</tr>
				<tr>
					<td>Incident Type :</td>
					<td><?php echo $_POST['incidentType'] ?>
						<input type="hidden" name="incidentType" id="incidentType"
						value = "<?php echo $_POST['incidentType'] ?>"></td>
				</tr>
			<tr>
				<td>Description :</td>
				<td><textarea name="incidentDesc" cols="45" rows="5" readonly id="incidentDesc">
					<?php echo $_POST['incidentDesc'] ?> </textarea>
						<input name="incidentDesc" type="hidden" id="incidentDesc"
						value = "<?php echo $_POST['incidentDesc'] ?>"> </td>
			</tr>
		</table>
		<br><br>
				<?php require_once 'db.php';
				$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					if ($mysqli->connect_errno)	{
						die("Failed to connect to MySQL! : " . $mysql->connect_errno);
					}
					
				$sql = "SELECT patrolcar_id, patrolcar_status_desc FROM patrolcar JOIN patrolcar_status ON patrolcar.patrolcar_status_id=patrolcar_status.patrolcar_status_id WHERE patrolcar.patrolcar_status_id='2' OR patrolcar.patrolcar_status_id='3'";
				
					if(!($stmt = $mysqli->prepare($sql))) {
						die("Prepare FAILED! : " . $mysqli->errno);
					}
						if(!($stmt->execute())) {
							die("Execute failed! : " . $stmt->errno);
						}
					if(!($resultset = $stmt->get_result())) {
						die("Getting result set failed! : " . $stmt->errno);
					}
					
				$patrolcarArray;
					while($row = $resultset->fetch_assoc())	{
						$patrolcarArray[$row['patrolcar_id']]=$row['patrolcar_status_desc'];
					}
			$stmt->close();
			$resultset->close();
			$mysqli->close();
		?>
		
	
		<table class="ContentStyle">
			<tr>
				<td colspan="3">Dispatch Patrolcar Panel</td>
			</tr>
			<?php foreach($patrolcarArray as $key=>$value)	{ ?>
			<tr>
				<td><input type="checkbox" name="chkPatrolCar[]"
					value = "<?php echo $key ?>"></td>
				<td><?php echo $key ?></td>
				<td><?php echo $value?></td>
			</tr>
			<?php }	?>	
			<tr>
				<td><input type="reset" name="btnCancel" id="btnCancel" value="Reset" class="button"></td>
				<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="btnDispatch" id="btnDispatch" value="Dispatch" class="button"></td>
			</tr>
		</table>
	</form>
</body>
</html>	