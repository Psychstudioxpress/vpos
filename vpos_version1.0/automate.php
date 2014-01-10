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

// vPOS
// Version 1.0
// automate.php
// This file contains many of the key automation components of the vPOS system. Other          
// components are found in the form of if-statements in vPOS.php                               

// This sets a counter that ensures previous orders are not replayed
if (!isset($_SESSION['order_number'])) 
{ 
	$_SESSION['order_number'] = 0; 		
	$_SESSION['wait'] = TRUE;
}

// Redirect at conclusion of experiment
if ($_SESSION['order_number'] == 19)
{
	header("Location: vccs_collection.php");
}

		// Creates the Javascript for repeating orders
		echo "<script language=\"javascript\" type=\"text/javascript\">
		function repeatOrder()
		{
		// Stores the number of repeats as a string and not an integer...
		var repeats = readCookie('repeats') + 1;
		document.cookie=\"repeats=\" + repeats; // The total number of times an order is repeated
		
		var hiddenIframe = document.createElement('iframe');
		var footer = document.getElementById('footer');
		hiddenIframe.setAttribute('id', 'orderClip');
		hiddenIframe.setAttribute('height', '0');
		hiddenIframe.setAttribute('width', '0');
		hiddenIframe.setAttribute('src', '".$SOUND_PATH."/order".$_SESSION['order_number'].".mp3');
		hiddenIframe.setAttribute('border', '0');
		footer.appendChild(hiddenIframe);
		
		}
		</script>";
		// This block starts a new order

		if ($_GET['action'] == 'order' AND $_SESSION['wait'] == False)
		{			
			// Prevents an order from being triggered if one is already in process
			if (!isset($_SESSION['activeOrder']))
			{
			setcookie("start_time", time()); // Starts the order timer
			// Places the order
			echo "<iframe height=\"0\" width=\"0\" src=\"".$SOUND_PATH."/order".$_SESSION['order_number'].".mp3\" border=\"0\" onLoad=\"startTimer()\" id=\"orderClip\" 			  
			 style=\"visibility: hidden;\"></iframe>
					<!-- Generates the repeat order key -->
					<script language=\"javascript\" type=\"text/javascript\">
					var linksPane = document.getElementById('linksPane');
					var repeatSoundKey = document.createElement('input');
					repeatSoundKey.setAttribute('id', 'repeatSoundKey');
					repeatSoundKey.setAttribute('type', 'button');
					repeatSoundKey.setAttribute('name', 'repeatSoundKey');
					repeatSoundKey.setAttribute('value', 'Repeat Order');
					repeatSoundKey.setAttribute('onclick', 'repeatOrder()');
					var tr = document.createElement('tr');
					linksPane.appendChild(tr);
					tr.appendChild(repeatSoundKey);
					</script>";
			$_SESSION['activeOrder'] = 1; // Sets that an order is active, unset when an order is stored
			$_SESSION['order_number'] += 1; // This is a counter that ensures previous orders are not replayed
			}
		}

	// If an order is not current in process, this sets a timer and then triggers an order to be placed
	if ($_SESSION['wait'] == True)
	{
		$rand = mt_rand(20,90); // Sets up the auto refresh of the page
		$_SESSION['wait'] = False;
		header("refresh: $rand; url=vPOS.php?action=order");
    }
