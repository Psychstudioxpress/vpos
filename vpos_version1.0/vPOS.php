<?php
// vPOS
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

// Version 1.0
// vPOS.php
// The Following file contains the vPOS header (displays time, date, etc), vPOS item keys, and 
// the code for storing orders, logging out, and performing over rings.						  
// Session varaibles set by this file:                                                         
// Overrings - Number of overrrings.															  
// Cookie variables set by this file:														  
// start_time - Time when an order is first begun.											  
// Files called:                                                                               
// automate.php - Included via PHP if vPOS is set in automatic mode.                           
// cod.php - Passes the current order via the source attribute of a hidden iframe in order to  
//           load the COD.																	  

include 'conf.php';

// Logs users out
if ($_GET['action'] == 'logout')
{
	session_destroy();
	unset($_SESSION['SID']);
	unset($_SESSION['password']);
	unset($_SESSION['CONDITION']);
	
	// Unset Cookies
	if (isset($_SERVER['HTTP_COOKIE'])) 
	{
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		
		foreach($cookies as $cookie) 
		{
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
		}
	} 
}

// Performs an Overring
if ($_GET['action'] == md5('overring'))
{
	// Records that an overring has occured
	$_SESSION['overrings'] += 1;

	// Deletes any cookie and session values that might be present
	setcookie("order", "", time()-3600);
	setcookie("start_time", "", time()-3600);
	setcookie("mods", "", time()-3600);
	setcookie("totalReductions", "", time()-3600);
	setcookie("repeats", "", time()-3600);
	unset($_COOKIE['order']);
	unset($_COOKIE['start_time']);
	unset($_COOKIE['mods']);
	unset($_SESSION['price']);
	unset($_COOKIE['totalReductions']);
	unset($_COOKIE['repeats']);
}

// Redirects non-logged in users
if (!isset($_SESSION['password']))
{
	header("Location: login.php");
}

// This code is used to generate each of the sub menus
function pull_menu($menu_type)
{
// This code pulls items from the database and then displays the buttons necessary to ring the items up
$menu_type = clean($menu_type);
$get_items = mysql_query("SELECT * FROM items WHERE category=$menu_type")or die(mysql_error());

echo "	// This code gets rid of any prior menus displayed on screen
		var menu_pane = document.getElementById('menu_pane');
		if (menu_pane.hasChildNodes())
		{
			while (menu_pane.childNodes.length >= 1)
			{
			menu_pane.removeChild(menu_pane.firstChild);
			}
		}";
		
// Generates the Javascript needed for each item key
$k = 0;
while ($item = mysql_fetch_array($get_items))
	{
	$k = $k++;
	echo "
	var itemKey = document.createElement('input');
	itemKey.setAttribute('id', 'item".$k."');
	itemKey.setAttribute('type', 'button');
	itemKey.setAttribute('name', 'item".$k."');
	itemKey.setAttribute('value', '".$item['ShortTitle']."');
	itemKey.setAttribute('onclick', 'addItem(".$item['ID'].")');
	itemKey.setAttribute('style', 'min-width: 120px;');
	menu_pane.appendChild(itemKey);";
	}
}

// This is the procss through which orders are stored
if (isset($_POST['submit_check']))
{
	// This code is used to ensure that if a user has voided an item from an order, without ringing another item up, that the script pulls the order, as modified, up.
	if (isset($_COOKIE['order'])) { $order = clean($_COOKIE['order']); }
		else { $order = clean($_POST['order']); }

// Items to be entered into the database
$start_time = clean($_COOKIE['start_time']);
$now = time();
$order = str_replace("%3B", ";", $order); // Replaces URL encoding with the proper character
$mods = clean($_COOKIE['mods']); 
$totalReductions = strlen(clean($_COOKIE['totalReductions'])); // Have to lengths of the string because JS doesn't treat it like an integer
$repeats = strlen(clean($_COOKIE['repeats'])); // Have to lengths of the string because JS doesn't treat it like an integer

if ($AUTOMATE == 'no') { $_SESSION['order_number'] = 0; }

$store_order = mysql_query("INSERT INTO orders (`oid`, `supervisor_ID`, `participant_ID`, `workstation`, `start_time`, `stored_time`, `items`, `modifications`, `reductions`, 		`repeats`, `price`) VALUES ($_SESSION[order_number], '$_SESSION[SID]', '$_SESSION[PID]', '$_SESSION[workstation]', '$start_time', '$now', '$order', '$mods', '$totalReductions', '$repeats', '$_SESSION[price]')")or die(mysql_error());

// Deletes any cookie and session values that might be present
setcookie("order", "", time()-3600);
setcookie("start_time", "", time()-3600);
setcookie("mods", "", time()-3600);
setcookie("totalReductions", "", time()-3600);
setcookie("repeats", "", time()-3600);
unset($_COOKIE['order']);
unset($_COOKIE['start_time']);
unset($_COOKIE['mods']);
unset($_SESSION['price']);
unset($_COOKIE['totalReductions']);
unset($_COOKIE['re[eats']);

if ($AUTOMATE == 'yes') 
{ 
	unset($_SESSION['activeOrder']); 
	$_SESSION['wait'] = True;
}
}

echo $_SESSION['activeOrder'];

// HTML Header
echo "
<html>
<head><title>".$PROJECT_TITLE." ".$USAGE." ".$BUILD_NO."</title>

<script language=\"javascript\" type=\"text/javascript\">";

// This displays an alert window after an order is sucessfully stored
if (isset($_POST['submit_check']))
{
echo "alert('Order Sucessfully Stored');";
}

echo "
// Date and Time Script, used to display current date and time
/* Adapted from: Visit http://www.yaldex.com/ for full source code and get more free JavaScript, CSS and DHTML scripts! */
function datetime() {
var today = new Date();
var hour = today.getHours();
var hourUTC = today.getUTCHours();
var diff = hour - hourUTC;
var hourdifference = Math.abs(diff);
var minute = today.getMinutes();
var minuteUTC = today.getUTCMinutes();
var minutedifference;
var second = today.getSeconds();
var timezone;
if (hour <= 9) hour = \"0\" + hour;
if (minute <= 9) minute = \"0\" + minute;
if (second <= 9) second = \"0\" + second;
time = hour + \":\" + minute + \":\" + second;
document.datetime.display.value = time;
window.setTimeout(\"datetime();\", 500);
}
window.onload=datetime;


// Modified stopwatch script by James Edwards - http://www.brothercake.com/ 
var base = 60;
var clocktimer,dateObj,dh,dm,ds,ms;
var readout='';
var h=1;
var m=1;
var tm=1;
var s=0;
var ts=0;
var ms=0;
var show=true;

function startTIME() {

var cdateObj = new Date();
var t = (cdateObj.getTime() - dateObj.getTime())-(s*1000);

if (t>999) { s++; }

if (s>=(m*base)) {
	ts=0;
	m++;
	} else {
	ts=parseInt((ms/100)+s);
	if(ts>=base) { ts=ts-((m-1)*base); }
	}

if (m>(h*base)) {
	tm=1;
	h++;
	} else {
	tm=parseInt((ms/100)+m);
	if(tm>=base) { tm=tm-((h-1)*base); }
	}

ms = Math.round(t/10);
if (ms>99) {ms=0;}
if (ms==0) {ms='00';}
if (ms>0&&ms<=9) { ms = '0'+ms; }

if (ts>0) { ds = ts; if (ts<10) { ds = '0'+ts; }} else { ds = '00'; }
dm=tm-1;
if (dm>0) { if (dm<10) { dm = '0'+dm; }} else { dm = '00'; }
dh=h-1;
if (dh>0) { if (dh<10) { dh = '0'+dh; }} else { dh = '00'; }

readout = dh + ':' + dm + ':' + ds + '.' + ms;
if (show==true) { document.clockform.clock.value = readout; }

clocktimer = setTimeout(\"startTIME()\",1);
}
		

function startTimer() {
	var start_time = Math.round((new Date()).getTime() / 1000);
	document.cookie = \"start_time = \"+start_time; // This cookie is retrieved when an order is stored and saved as the start time of the order
	dateObj = new Date();
	startTIME();
}

// Read Cookie Script
// Quirks Mode
function readCookie(name) {
	var nameEQ = name + \"=\";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

// Delete Cookie Script
// Quirks Mode
function del_cookie(name)
{
document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}

/* Order Script
This script is used to store what items the participant selects in working memory until the order is actually stored */

var order = new String();  // This string contains the list of items ordered
function addItem(item_ID)
{";
	if ($AUTOMATE == 'no')
	{
		echo "// Starts an order timer
		if (order == '')
		{
		startTimer();
		}";
	}

echo "
// Checks to make sure that a modified order has not been submited by cod.php
var x = readCookie('order');

	if (order != '')
	{
	/* If a modified order has been submitted by cod.php, then it loads that order. If not, then it just loads
	the last value for order */
	if (x) { order = x + \";\" + item_ID; }
	else { order = order + \";\" + item_ID; }
	}
		else { order = item_ID;}

del_cookie('order'); // Deletes the order cookie created by cod.php

/* The following creates a link that is sent to COD.php by means of a hidden <iframe>.  
The info in this link is submitted to the database and converted into item names */
var newurl = \"cod.php?order=\"+order;
var d = document.getElementById(\"COD\"); 
d.setAttribute(\"src\", newurl);

// This value updates the store order key by changing it's value to the curent order
var store_orderKey = document.getElementById(\"store_order\");
store_orderKey.setAttribute('value', order);
	// Alters the store order key in order to prevent an empty order from being stored
	var store_order_SubmitKey = document.getElementById(\"store_orderButton\");
	store_order_SubmitKey.setAttribute('type', 'submit');
	store_order_SubmitKey.removeAttribute('onclick');
}

function generate_menu(menu_type)
{
/*
Category Conversions: Number to Text 
1: Main Menu
2: Drinks
3: Sandwiches
4: Sides
5: Desserts
6: Condiments
7: Manager Menu
*/

// If the user hasn't selected a menu then it defaults to Main Menu
if (menu_type == null)
{
menu_type = 1;
}
	if (menu_type == 1)
	{";
	pull_menu(1);
	echo "}
		else if (menu_type == 2)
		{";
		pull_menu(2);
		echo "}
			else if (menu_type == 3)
			{";
			pull_menu(3);
			echo "}
				else if (menu_type == 4)
				{";
				pull_menu(4);
				echo "}
					else if (menu_type == 5)
					{";
					pull_menu(5);
					echo "}
						else if (menu_type == 6)
						{";
						pull_menu(6);
						echo "}
							else if (menu_type == 7)
							{";
							pull_menu(7);
							echo "}
}

// Displays the Manager Screen
function managerFunctions()
{
var password=prompt('Please enter the system password','');
	if (password!=null && password!='')
	{
		// MD5 this later
		if (password == '".$SYSTEM_PASSWORD."')
		{
			// This code gets rid of any prior menus displayed on screen
			var menu_pane = document.getElementById('menu_pane');
			if (menu_pane.hasChildNodes())
			{
				while (menu_pane.childNodes.length >= 1)
				{
				menu_pane.removeChild(menu_pane.firstChild);
				}
			}
		// Creates a Heading
		var headDiv = document.createElement('div');
		headDiv.setAttribute('align', 'center');
		var head = document.createElement('h4');
		var text = document.createTextNode('Manager Functions');
		head.appendChild(text);
		headDiv.appendChild(head);
		menu_pane.appendChild(headDiv);
			// Creates the keys for any manager functions
			var overingKey = document.createElement('input');
			overingKey.setAttribute('id', 'overingKey');
			overingKey.setAttribute('type', 'button');
			overingKey.setAttribute('name', 'overingKey');
			overingKey.setAttribute('value', 'OVERRING');
			overingKey.setAttribute('onclick', 'location.replace(\"vPOS.php?action=".md5(overring)."\")');
			overingKey.setAttribute('style', 'min-width: 120px;');
			menu_pane.appendChild(overingKey);
				var logoutKey = document.createElement('input');
				logoutKey.setAttribute('id', 'logoutKey');
				logoutKey.setAttribute('type', 'button');
				logoutKey.setAttribute('name', 'logoutKey');
				logoutKey.setAttribute('value', 'LOG OUT OT');
				logoutKey.setAttribute('onclick', 'location.replace(\"vPOS.php?action=logout\")');
				logoutKey.setAttribute('style', 'min-width: 120px;');
				menu_pane.appendChild(logoutKey);
		}
		else { alert('Sorry, the password entered was not valid.'); }
	}
}
</script>
</head>

<!-- HTML Body -->
<body onLoad=\"generate_menu()\"> <!-- The onLoad ensures that the main menu is displayed upon starting the program -->

<table width=\"100%\" border=\"1\"><tr>
<!-- Confirm Order Display (COD) Screen -->
<td rowspan=\"2\">

	<!-- SRC attribute is created by addItem(item_ID) -->
	<!-- 348 width works excellent in Chrome, in any Chrome applications 348 should be used -->
	<iframe name=\"COD\" id=\"COD\" src=\"\" width=\"365\" height=\"500\" scrolling=\"auto\"></iframe></td>

<!-- Information Panel at Top of Screen -->
<td align=\"right\" width=\"80%\">
	<table border=\"1\" width=\"100%\">
<tr>
	
	<!-- Order Timer -->
	<td width=\"20%\" align=\"center\">
	<form name=\"clockform\">
	<input name=\"clock\" type=\"text\" value=\"00:00:00.00\" style=\"border:0;\" />
	</form>
	</td>

	<!-- Name of Study -->
	<td align=\"center\">".$PROJECT_TITLE." Participant</td>
	
	<!-- Current Date/Time Display -->
	<td width=\"20%\" align=\"center\">
	<form name=\"datetime\">
	".date('m/d/Y')."<input type=\"text\" name=\"display\" size=\"6\" style=\"border:0;\">
	</form>
	</td>
</tr>
	<!-- Displays the client's IP -->
	<tr><td colspan=\"3\" align=\"center\">".$_SERVER['REMOTE_ADDR']." POS(3)</td></tr>
	
	</table>
	
</td></tr>

<!-- Displays the selected menu's items. Populated by generate_menu(menu_type). -->
<tr><td height=\"432\" valign=\"top\" id=\"menu_pane\"></td>

<!-- List of Submenus -->
<tr><td colspan=\"2\" height=\"139\" valign=\"middle\" id=\"footer\">
<table width=\"100%\">
<tr id=\"linksPane\">
<td><input type=\"button\" name=\"Menu_1\" value=\"Main Menu\" onClick=\"generate_menu(1)\" /></td>
<td><input type=\"button\" name=\"Menu_2\" value=\"Drinks\" onClick=\"generate_menu(2)\" /></td>
<td><input type=\"button\" name=\"Menu_3\" value=\"Sandwiches\" onClick=\"generate_menu(3)\" /></td>
<td><input type=\"button\" name=\"Menu_4\" value=\"Sides\" onClick=\"generate_menu(4)\" /></td>
<td><input type=\"button\" name=\"Menu_5\" value=\"Desserts\" onClick=\"generate_menu(5)\" /></td>
<td><input type=\"button\" name=\"Menu_6\" value=\"Condiments\" onClick=\"generate_menu(6)\" /></td>
<!-- Link to the Manager Functions Screen -->
<td><input type=\"button\" name=\"SpeicalFunctions\" value=\"Manager Functions\" onClick=\"managerFunctions()\" /></td>

	<!-- The store order button. -->
	<td align=\"right\" width=\"50%\">";
	
if ($AUTOMATE == 'yes' && $_SESSION['activeOrder'] == 1)
{
	echo "<button onClick=\"repeatOrder()\">Repeat Order</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

	echo "<form name=\"store_order\" method=\"post\">
		<!-- VALUE attribute populated by addItem(item_ID) -->
		<input type=\"hidden\" name=\"order\" id=\"store_order\" />
	<input type=\"hidden\" name=\"submit_check\" value=\"1\" />
	<input type=\"button\" name=\"store_orderButton\" id=\"store_orderButton\" value=\"Store Order\" onClick=\"alert('Error: Cannot store an empty order.')\" />
	</form>
	</td>

<!-- HTML Footer -->	
</tr></table></body>";

if ($AUTOMATE == 'yes')
{
	include 'automate.php';
}
?>
