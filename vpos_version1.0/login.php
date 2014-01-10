<?php
// PsychStudioXpress provides tools to behavioral and social science researchers.
// Copyright (C) 2013 William Kelly Hudgins
// This program is free software: you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by
// the Free Software Foundation, version 3.
//
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
// or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
// more details.
//
// You should have received a copy of the GNU General Public License along with
// this program. If not, see <http://www.gnu.org/licenses>.
//
// If you have questions, please email wkhudgins@psychstudioxpress.net

###############################################################################################
######################               vPOS                 #####################################
######################             Login File             #####################################
###################### DEVELOPED BY WILLIAM KELLY HUDGINS #####################################
###################### Contact: wkhudgins@tlu.edu         #####################################
# This file is used to log participants into the system.                                      #
# Sets session variables including:															  #
# Supervisor (SID) - The supervisor's ID.									                  #
# CONDITION - The Condition a participant is assigned to.									  #
# password - The supervisor's password, encrypted.			                                  #
# Participant ID (PID) - An MD5 hash that is used as the ID of a participant.                 #
# workstation - The computer the participant is working at.                                   #
###############################################################################################

include 'conf.php';

# Redirects a user if they're already logged in
if (isset($_SESSION['password']))
{
header("Location: vPOS.php");
}

	# Processes Login
	if ($_POST['username'] != '' && $_POST['password'] != '' && $_POST['workstation'] != '' && $_POST['condition'] != '')
	{
	$usern = clean($_POST['username']);
	$passw= crypto($_POST["password"]);

	# Compares user credentials with the DB
	$db_check = mysql_query("SELECT * FROM users WHERE username='$usern' AND password='$passw'");
	
		# Logs in the user
		if ($user = mysql_fetch_array($db_check)) 
		{
			$_SESSION['SID'] = 7; // The ID of the supervisor
			$_SESSION['CONDITION'] = clean($_POST['condition']); 
			$_SESSION['password'] = md5($user['password']);
$random_digit = rand(100,999);
				$_SESSION['PID'] = md5(time().$random_digit); 			
				$_SESSION['workstation'] = clean($_POST['workstation']);
			header("Location: vPOS.php");
		}
			// If the supplied data doesn't match the database data
			else { echo "<div align=\"center\">Sorry, the credentials you supplied do not match those found in our database. Please try again. If you continue to experience problems, 	please contact your system administrator or project manager.</div>"; }
	} 
		// If the form is incomplete
		else if (isset($_POST['submit_check']) && ($_POST['username'] == '' || $_POST['password'] =='' || $_POST['workstation'] == '' 
		|| $_POST['condition'])) 
		{ echo "<div align=\"center\">In order to login, please fill out the entire form.</div>"; }

// Login Screen		
echo "
<html>

<head>
	<title>".$project_title." ".$usage." ".$build_no." Login</title>
</head>

<body> 
	<div align=\"center\">
	<h3>Login to ".$project_title." ".$usage."</h3>
		<form method=\"post\" action=\"\">
			<table>
				<tr><td>Username:</td><td><input type=\"text\" name=\"username\" /></td></tr>
				<tr><td>Password:</td><td><input type=\"password\" name=\"password\" /></td></tr>
				<tr><td>Program:</td><td><input type=\"text\" name=\"condition\" size=\"2\" /></td></tr>
				<tr><td>Workstation:</td><td><input type=\"text\" name=\"workstation\" size=\"2\" /></td></tr>
						<input type=\"hidden\" name=\"submit_check\" value=\"1\" />
				<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Login\" /></td></tr>
				<tr><td colspan=\"2\" align=\"center\"><input type=\"reset\" /></td></tr>
			</table>
		</form>
	</div>
</body>
</html>";
?>
