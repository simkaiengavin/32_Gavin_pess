	<!doctype html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Police Emergency Service System</title>
	<link rel="stylesheet" href="content_style.css">
	<link rel="stylesheet" href="header_style.css">
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
	<?php //import nav.php
	define('__ROOT__', dirname(dirname(__FILE__)));
	require_once(__ROOT__.'\pess\nav.php');
	?>
	<?php //Import db.php
	require_once(__ROOT__.'\pess\db.php');

	//Create a new connection
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check the connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

		$sql = "SELECT * FROM incident_type";

		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()){
			// create an associative array of $incidentType {incident_type_id, incident_type_desc}
			$incidentType[$row['incident_type_id']] = $row['incident_type_desc'];
		}
	}

	$conn->close();
	?>
		
	</head>

	<body class="ContentStyle">
	<script type="text/javascript">
			function validateForm()
			{ //Make sure callerName is not empty
				var x=document.forms["frmLogCall"]["callerName"].value;
				if (x==null || x=="")
					{
						alert("Caller Name is required.");
						return false;
					}
				//Make sure contactNo is not empty
				var x=document.forms["frmLogCall"]["contactNo"].value;
				if (x==null || x=="")
					{
						alert("Contact Number is required.");
						return false;
					}	

				//Make sure location is not empty
				var x=document.forms["frmLogCall"]["location"].value;
				if (x==null || x=="")
					{
						alert("Please input location of the incident.");
						return false;
					}

				//Make sure description is not empty
				var x=document.forms["frmLogCall"]["incidentDesc"].value;
				if (x==null || x=="")
					{
						alert("Please input a short description of incident.");
						return false;
					}

			}
	</script>
		<form name="frmLogCall" method="post"
			  onSubmit="return validateForm()" action="dispatch3.php">
		<table>
			<tr>
				<td colspan="2">Log Call Panel</td>
			</tr>
			<tr>
				<td>Caller's Name :</td>
				<td><input type="text" name="callerName" id="callerName"></td>
			</tr>
			<tr>
				<td>Contact No :</td>
				<td><input type="text" name="contactNo" id="contactNo"></td>
			</tr>
			<tr>
				<td>Location :</td>
				<td><input type="text" name="location" id="location"></td>
			</tr>
			<tr>
				<td>Incident Type :</td>
				<td><select name="incidentType" id="incidentType">
					<?php // populate a combo box with $incidentType
						foreach($incidentType as $key => $value){
					?>
						<option value="<?php echo $key?>">
							<?php echo $value ?>
					</option>
					<?php 
						}
					?>
					</select></td>
			</tr>
			<tr>
				<td>Description :</td>
				<td><textarea name="incidentDesc" id="incidentDesc" cols="45" rows="5"></textarea></td>
			</tr>
			<tr>
				<td><input type="reset" name="btnCancel" id="btnCancel" value="Reset" class="button"></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnProcessCall" id="btnProcessCall" value="Process Call..." class="button"></td>
			</tr>
		</table>
		</form>
	</body>
	</html>