<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Service System</title>
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
</head>

<body Class="ContentStyle">
	<?php require_once 'nav.php'; ?>
	<form name="login" onSubmit="return validateLogIn()" action="logcall.php">
<table>
	<tr>
		<td>User Log In:</td>
		<td></td>
	</tr>
	<tr>
		<td style="text-align: center;">Name:</td>
		<td><input type="text" name="userName" id="userName"></td>
	</tr>
	<tr>
		<td style="text-align: center;">Password:</td>
		<td><input type="text" name="password" id="password" ></td>
	</tr>
	<tr>
		<td></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnLogin" id="btnLogin" value="Log in" class="button"></td>
	</tr>
</table>	
</form>
</body>
</html>