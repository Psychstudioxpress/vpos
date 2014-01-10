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
// cod.php
//
// This file is used to create the COD panel on the left of the vPOS page and is included via  
// an iframe.                                                                                  

include 'conf.php';
ob_start();
// This code is used to generate each of the grill menus
function pull_grillMenu($grill_category)
{
echo "	// This code clears the COD screen
		var cod_table = document.getElementById('cod_table');
		if (cod_table.hasChildNodes())
		{
			while (cod_table.childNodes.length >= 1)
			{
			cod_table.removeChild(cod_table.firstChild);
			}
		}
			// This code creates the structure for the table that will contain grill options
			
			// The main grill table
			var grillTable = document.createElement('table');
			grillTable.setAttribute('width', '100%');
			grillTable.setAttribute('style', 'font-size: 12px;');
			cod_table.appendChild(grillTable);
				// The first row and cell of the table
				var tr = document.createElement('tr');
				grillTable.appendChild(tr);
				var tdAdd = document.createElement('td');
				tdAdd.setAttribute('id', 'tdAdd');
				var addTitle = document.createTextNode('Option');
				tdAdd.appendChild(addTitle);
				tr.appendChild(tdAdd);";
				
// Eventually recode into a function
// Creates the table headings for the various grill menus
			// Ssandwich items
			if ($grill_category ==3)
			{
			echo "
			var tdAdd = document.createElement('td');
			tdAdd.setAttribute('id', 'tdAdd');
			var addTitle = document.createTextNode('Add');
			tdAdd.appendChild(addTitle);
			tr.appendChild(tdAdd);
				var tdWO = document.createElement('td');
				tdWO.setAttribute('id', 'tdWO');
				var addTitle = document.createTextNode('W/O');
				tdWO.appendChild(addTitle);
				tr.appendChild(tdWO);
					var tdXtra = document.createElement('td');
					tdXtra.setAttribute('id', 'tdXtra');
					var addTitle = document.createTextNode('XTRA');
					tdXtra.appendChild(addTitle);
					tr.appendChild(tdXtra);
						var tdLight = document.createElement('td');
						tdLight.setAttribute('id', 'tdLight');
						var addTitle = document.createTextNode('Light');
						tdLight.appendChild(addTitle);
						tr.appendChild(tdLight);
							var tdOnly = document.createElement('td');
							tdOnly.setAttribute('id', 'tdOnly');
							var addTitle = document.createTextNode('Only');
							tdOnly.appendChild(addTitle);
							tr.appendChild(tdOnly);
								var tdSub = document.createElement('td');
								tdSub.setAttribute('id', 'tdSub');
								var addTitle = document.createTextNode('Sub');
								tdSub.appendChild(addTitle);
								tr.appendChild(tdSub);";
			}
				// Drinks excluding coffe
				if ($grill_category == 2)
				{
				echo "
				// Soft Drinks excluding coffee
			var tdAdd = document.createElement('td');
			tdAdd.setAttribute('id', 'tdAdd');
			tr.appendChild(tdAdd);
				var tdWO = document.createElement('td');
				tdWO.setAttribute('id', 'tdWO');
				var addTitle =document.createTextNode('W/O');
				tdWO.appendChild(addTitle);
				tr.appendChild(tdWO);
					var tdXtra = document.createElement('td');
					tdXtra.setAttribute('id', 'tdXtra');
					var addTitle =document.createTextNode('XTRA');
					tdXtra.appendChild(addTitle);
					tr.appendChild(tdXtra);
						var tdLight = document.createElement('td');
						tdLight.setAttribute('id', 'tdLight');
						var addTitle =document.createTextNode('Light');
						tdLight.appendChild(addTitle);
						tr.appendChild(tdLight);
							var tdOnly = document.createElement('td');
							tdOnly.setAttribute('id', 'tdOnly');
							var addTitle =document.createTextNode('Only');
							tdOnly.appendChild(addTitle);
							tr.appendChild(tdOnly);";
				}
					// Side items
					if ($grill_category == 4)
					{
					echo "
						var tdAdd = document.createElement('td');
						tdAdd.setAttribute('id', 'tdAdd');
						tr.appendChild(tdAdd);
							var tdWO = document.createElement('td');
							tdWO.setAttribute('id', 'tdWO');
							var addTitle =document.createTextNode('W/O');
							tdWO.appendChild(addTitle);
							tr.appendChild(tdWO);
								var tdXtra = document.createElement('td');
								tdXtra.setAttribute('id', 'tdXtra');
								var addTitle =document.createTextNode('XTRA');
								tdXtra.appendChild(addTitle);
								tr.appendChild(tdXtra);";
					}
						// Desserts
						if ($grill_category == 5)
						{
						echo "
						var tdAdd = document.createElement('td');
						tdAdd.setAttribute('id', 'tdAdd');
						var addTitle =document.createTextNode('Add');
						tdAdd.appendChild(addTitle);
						tr.appendChild(tdAdd);
							var tdWO = document.createElement('td');
							tdWO.setAttribute('id', 'tdWO');
							var addTitle =document.createTextNode('W/O');
							tdWO.appendChild(addTitle);
							tr.appendChild(tdWO);
								var tdXtra = document.createElement('td');
								tdXtra.setAttribute('id', 'tdXtra');
								var addTitle =document.createTextNode('XTRA');
								tdXtra.appendChild(addTitle);
								tr.appendChild(tdXtra);
									var tdLight = document.createElement('td');
									tdLight.setAttribute('id', 'tdLight');
									var addTitle =document.createTextNode('Light');
									tdLight.appendChild(addTitle);
									tr.appendChild(tdLight);
										var tdSub = document.createElement('td');
										tdSub.setAttribute('id', 'tdSub');
										var addTitle = document.createTextNode('Sub');
										tdSub.appendChild(addTitle);
										tr.appendChild(tdSub);";
						}
							// Condiments and items for which there are no grill options
							if ($grill_category == 6)
							{
							echo "
							var td = document.createElement('td');
							td.setAttribute('id', 'td');
							var addTitle =document.createTextNode('Sorry, there are no grill options for this item.');
							td.appendChild(addTitle);
							tr.appendChild(td);";
							}



// This code pulls grill options from the database based off the category an item belongs to
$grill_category= clean($grill_category); // Reminder: $grill_category is a parameter of the PHP pull_grillMenu function
$get_options = mysql_query("SELECT * FROM grillmenus WHERE category=$grill_category")or die(mysql_error());

// Generates the Javascript needed for each option key
$k = 0;			
while ($option = mysql_fetch_array($get_options))
	{
	$k = $k++;
	// Lists the title of the option. Example: Cheese, Mayo, etc.
	echo "
	var tr".$k." = document.createElement('tr');
	grillTable.appendChild(tr".$k.")
	var optionTitle = document.createElement('td');
	optionTitle.setAttribute('id', '".$option['Name']."');
	var addTitle = document.createTextNode('".$option['Name']."');
	optionTitle.appendChild(addTitle);
	tr".$k.".appendChild(optionTitle);";
	
/* The follow code creates buttons which have an onClick property. These buttons call the JavaScript function addModification(item_ID, newMod, option).
The first parameter, item_ID, is the ID of the item being modified and is used to eventually link the modifications back to a given item.
The second parameter, newMod, is the actual modification being made.
Lastly, the option parameter, stores the name of the option-item (examples: cheese, mayo, ice, etc) being modified and is used to change the table row that continues 
the option keys associated with the modification.
*/

// Yes/No Option for items like Well Done and Plain
if ($option['Yes/No'] == 1)
{
echo "
var optionTD  = document.createElement('td');
optionTD.setAttribute('colspan', '3');
var optionKey = document.createElement('input');
optionKey.setAttribute('style', 'font-size: 12px;');
optionKey.setAttribute('id', 'item".$k."');
optionKey.setAttribute('type', 'button');
optionKey.setAttribute('name', 'item".$k.$option['Name']."');
optionKey.setAttribute('value', '".$option['Name']."');
optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"".$option['Name']."\", \"".$option['Name']."\")');
optionTD.appendChild(optionKey);
tr".$k.".appendChild(optionTD);";
}
	// The Add Key
	if ($option['ADD'] == 1)
	{
	echo "
	var optionTD  = document.createElement('td');
	var optionKey = document.createElement('input');
	optionKey.setAttribute('style', 'font-size: 12px;');
	optionKey.setAttribute('id', 'item".$k."');
	optionKey.setAttribute('type', 'button');
	optionKey.setAttribute('name', 'item".$k."Add');
	optionKey.setAttribute('value', '+');
	optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"ADD ".$option['Name']."\", \"".$option['Name']."\")');
	optionTD.appendChild(optionKey);
	tr".$k.".appendChild(optionTD);";
	}
		// If the option in question is not applicable, a place holder is created
		else { 
			echo "
			var optionTD  = document.createElement('td');
			var spaceHolder = document.createTextNode('');
			optionTD.appendChild(spaceHolder);
			tr".$k.".appendChild(optionTD);";
				} 
		// The without option
		if ($option['W/O'] == 1)
		{
		echo "
		var optionTD  = document.createElement('td');
		var optionKey = document.createElement('input');
		optionKey.setAttribute('style', 'font-size: 12px;');
		optionKey.setAttribute('id', 'item".$k."');
		optionKey.setAttribute('type', 'button');
		optionKey.setAttribute('name', 'item".$k."W/O');
		optionKey.setAttribute('value', '-');
		optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"W/O ".$option['Name']."\", \"".$option['Name']."\")');
		optionTD.appendChild(optionKey);
		tr".$k.".appendChild(optionTD);";
		}
			// If the option in question is not applicable, a place holder is created
			else { 
				echo "
				var optionTD  = document.createElement('td');
				var spaceHolder = document.createTextNode('');
				optionTD.appendChild(spaceHolder);
				tr".$k.".appendChild(optionTD);";
				} 
			
			// The Extra Option
			if ($option['XTRA'] == 1)
			{
			echo "
			var optionTD  = document.createElement('td');
			var optionKey = document.createElement('input');
			optionKey.setAttribute('style', 'font-size: 12px;');
			optionKey.setAttribute('id', 'item".$k."');
			optionKey.setAttribute('type', 'button');
			optionKey.setAttribute('name', 'item".$k."XTRA');
			optionKey.setAttribute('value', 'XTRA');
			optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"XTRA ".$option['Name']."\", \"".$option['Name']."\")');
			optionTD.appendChild(optionKey);
			tr".$k.".appendChild(optionTD);";
			}
				// If the option in question is not extra-able, a place holder is created
				else { 
					echo "
					var optionTD  = document.createElement('td');
					var spaceHolder = document.createTextNode('');
					optionTD.appendChild(spaceHolder);
					tr".$k.".appendChild(optionTD);";
					} 
					
				// The Light Option, Example: Light Ice, Light Mayo.
				if ($option['LIGHT'] == 1)
				{
				echo "
				var optionTD  = document.createElement('td');
				var optionKey = document.createElement('input');
				optionKey.setAttribute('style', 'font-size: 12px;');
				optionKey.setAttribute('id', 'item".$k."');
				optionKey.setAttribute('type', 'button');
				optionKey.setAttribute('name', 'item".$k."Light');
				optionKey.setAttribute('value', 'LITE');
				optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"LITE ".$option['Name']."\", \"".$option['Name']."\")');
				optionTD.appendChild(optionKey);
				tr".$k.".appendChild(optionTD);";
				}
					// If the option in question is not applicable, a place holder is created
					else { 
						echo "
						var optionTD  = document.createElement('td');
						var spaceHolder = document.createTextNode('');
						optionTD.appendChild(spaceHolder);
						tr".$k.".appendChild(optionTD);";
						} 
						
					// The Only Option	
					if ($option['ONLY'] == 1)
					{
					echo "
					var optionTD  = document.createElement('td');
					var optionKey = document.createElement('input');
					optionKey.setAttribute('style', 'font-size: 12px;');
					optionKey.setAttribute('id', 'item".$k."');
					optionKey.setAttribute('type', 'button');
					optionKey.setAttribute('name', 'item".$k."Only');
					optionKey.setAttribute('value', 'ONLY');
					optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"ONLY ".$option['Name']."\", \"".$option['Name']."\")');
					optionTD.appendChild(optionKey);
					tr".$k.".appendChild(optionTD);";
					}
						// If the option in question is not applicable, a place holder is created
						else { 
							echo "
							var optionTD  = document.createElement('td');
							var spaceHolder = document.createTextNode('');
							optionTD.appendChild(spaceHolder);
							tr".$k.".appendChild(optionTD);";
							} 
						
						// The Substitute Option
						if ($option['SUB'] == 1)
						{
						echo "
						var optionTD  = document.createElement('td');
						var optionKey = document.createElement('input');
						optionKey.setAttribute('style', 'font-size: 12px;');
						optionKey.setAttribute('id', 'item".$k."');
						optionKey.setAttribute('type', 'button');
						optionKey.setAttribute('name', 'item".$k."Sub');
						optionKey.setAttribute('value', 'Sub');
						optionKey.setAttribute('onclick', 'addModification(\"'+item_ID+'\", \"SUB ".$option['Name']."\", \"".$option['Name']."\")');
						optionTD.appendChild(optionKey);
						tr".$k.".appendChild(optionTD);";
						}	
							// If the option in question is not applicable, a place holder is created
							else { 
								echo "
								var optionTD  = document.createElement('td');
								var spaceHolder = document.createTextNode('');
								optionTD.appendChild(spaceHolder);
								tr".$k.".appendChild(optionTD);";
								} 
	
	} // End the while-loop started at line 198
	
	// Creates the buttons to store a grill order or clear a grill screen
	echo "
		// Creates the Table Row which will house teh buttons
		var inputTR = document.createElement('tr');
		inputTR.setAttribute('valign', 'top');
		
			// Creates the cells which will contain the keys
			var clearGrillTD = document.createElement('td');
			clearGrillTD.setAttribute('align', 'left');
			var store_grillTD = document.createElement('td');
			store_grillTD.setAttribute('colspan', '5');
			store_grillTD.setAttribute('align', 'right');
			var exit_grillTD = document.createElement('td');
			
				// Creates the Reset Grill Key
				var reset_grill = document.createElement('input');
				reset_grill.setAttribute('id', 'reset_grill');
				reset_grill.setAttribute('onClick', 'resetGrill()');
				reset_grill.setAttribute('type', 'button');
				reset_grill.setAttribute('value', 'Reset');
				reset_grill.setAttribute('style', 'font-size: 12px;');
				
					// Creates the Store Grill Key
					var store_modKey = document.createElement('input');
					store_modKey.setAttribute('id', 'store_mod');
					store_modKey.setAttribute('onClick', 'storeGrill()');
					store_modKey.setAttribute('type', 'button');
					store_modKey.setAttribute('value', 'Store Grill');
					store_modKey.setAttribute('style', 'font-size: 12px;');
					
						// Creates the Exit Grill Key
						var exit_grill = document.createElement('input');
						exit_grill.setAttribute('id', 'exit_grill');
						exit_grill.setAttribute('onClick', 'exitGrill()');
						exit_grill.setAttribute('type', 'button');
						exit_grill.setAttribute('value', 'Exit');
						exit_grill.setAttribute('style', 'font-size: 12px;');
							// Appends all the information to the document
							store_grillTD.appendChild(store_modKey);
							clearGrillTD.appendChild(reset_grill);
							exit_grillTD.appendChild(exit_grill);
							inputTR.appendChild(store_grillTD);
							inputTR.appendChild(clearGrillTD);
							inputTR.appendChild(exit_grillTD);
							grillTable.appendChild(inputTR);
	";
} // End pull_grillMenu()

echo "
<html>
<head>
<title></title>
<script type=\"text/javascript\">

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

// Deletes items. Stores the deleted item in a cookie and reloads the page.
function deleteItem(item_ID, mods)
{
var order = \"".clean($_GET['order'])."\";
order = order.split(\";\");
order = order.length;
	// This prevents a user from entirely voiding out an order. When that happens, the order is not voided at all, plus its unrealistic.
	if (order == '1')
	{
	alert(\"Cannot reduce order to 0. Please get a Manager to authorize an overring.\");
	}
		// Deletes the item
		else {
		// Stores the total reductions as a string and not an integer...
		var totalReductions = readCookie('totalReductions') + 1;
		document.cookie=\"totalReductions=\" + totalReductions; // The total number of reductions made to an order.
		document.cookie=\"del_item =\" + item_ID; // Item to be Deleted
		
		if (mods != '')
		{
		document.cookie=\"del_mods=\" + mods; // Any modifications to the given item
		}
		location.reload();
		}
}

/* Single item grills, stores options as they are selected from the grill menu (created by PHP function pull_grillMenu).
This function simply saves the choices made in the grill menu to the store grill key */

var singleMod = new String();  // This string contains the list of modifications made
function addModification(item_ID, newMod, option)
{

// This text changes a given option's display to indicate that an option has been selected
var optionTD = document.getElementById(option);
optionTD.removeChild(optionTD.firstChild);
var text = document.createTextNode(newMod);
optionTD.appendChild(text);

// Changes the Values of the Reset Grill Key
// Turn this into a function later
var reset_grill = document.getElementById('reset_grill');
var options = reset_grill.getAttribute('onClick');
options = options.split('(');
options = options[1].split(')');
options = options[0].split('\"');
options = options[1];
options = options  +\",\" + option; 
reset_grill.removeAttribute('onclick');
reset_grill.setAttribute('onClick', 'resetGrill(\"'+options+'\")');

// This code appends additional modifications of the same item to previous modifications of the same item
if (singleMod != '') { singleMod = singleMod +\"~\"+ newMod; }
else { singleMod = newMod; }

// This value updates the store grill key by changing it's value.
var store_modKey = document.getElementById(\"store_mod\");
store_modKey.setAttribute('onClick', 'storeMod(\''+item_ID+'|'+singleMod+'\')');
}

// This function clears all modifications made within a grill menu
function resetGrill(options)
{
singleMod = ''; // Clears the modifications

// Changes the GUI display of modifications made
options = options.split(',');
var numberOfOptions = options.length;

	// Start at k = 1 because options[0] is null and causes the loop to act up
	for (k = 1; k <= numberOfOptions; k++)
	{
	// This text changes an option's display to indicate that no modifications related to that option are in effect
	var optionTD = document.getElementById(options[k]);
	optionTD.removeChild(optionTD.firstChild);
	var text = document.createTextNode(options[k]);
	optionTD.appendChild(text); 
	}
}

// Pulls up the Grill Menus generated by the PHP function pull_grillMenu.
function grill(item_ID, grill_category, item_Name)
{
if (grill_category == '1')
{";
pull_grillMenu(1);
echo "
} 
	else if (grill_category == '2')
	{";
	pull_grillMenu(2);
	echo "
	} 
		else if (grill_category == '3')
		{";
		pull_grillMenu(3);
		echo "
		}
			else if (grill_category == '4')
			{";
			pull_grillMenu(4);
			echo "
			}
				else if (grill_category == '5')
				{";
				pull_grillMenu(5);
				echo "
				}
					else if (grill_category == '6')
					{";
					pull_grillMenu(6);
					echo "
					} 
						else if (grill_category == '7')
						{";
						pull_grillMenu(7);
						echo "
						} 
}

// Called by the store_mod (Store Grill) Key, stores modifications made to an item
function storeMod(mod)
{
var existingMods = readCookie('mods');
var mods = existingMods+\",\"+mod; 
document.cookie=\"mods =\" + mods;
location.reload();
}

// Exits the Grill Menu
function exitGrill()
{
location.reload();
}

</script>
</head>
<body>";

/* COD Screen Code
   Takes the info submitted by vPOS.php and converts it to an array of items, 
   with each number representing an item. */

$order = clean($_GET['order']); // The list of items currently rung up, submited via Javascript in vPOS.php
$order = explode(";", $order); // Converts the list of items into an array
$modifications = clean($_COOKIE['mods']);
$items_modified = explode(",", $modifications); // Breaks the modifications up into an array of seperate, modified, menu items
$total_price = 0; // The starting subtotal of the order
$i = 0;
$k = 0;

echo "<table width=\"100%\" height=\"100%\" id=\"cod_table\">";

// Displays the list of items ordered in the COD pane.
foreach ($order as $item) // Loops through the items of each order
{
$i++;
// Fetchs the item's name and price from the database
$item_query = mysql_query("SELECT * FROM items WHERE ID='$item'")or die(mysql_error());
$item = mysql_fetch_array($item_query);

		// This Code pulls up any modifications made to an item
		foreach ($items_modified as $modified_item) // Loops through the list of modified items
		{
		$break = explode("|", $modified_item); // Breaks the ID of an item away from the modifications made to the item
		$modifiedItemID = $break[0];
			if ($item['ID'] == $modifiedItemID)
			{
			$k = $i;
			$modifications = explode("~", $break[1]);
				// Loops through individual modifications made to a given item
				foreach ($modifications as $modification)
				{
				$PrintModifications = $PrintModifications."<br />".$modification; 
	
					// This code gets and stores the price of any modifications
					$singlemod = explode(" ", $modification); // Gets the details of a single modification
						// If the User has added anything extra
						if ($singlemod[0] == 'XTRA' OR $singlemod[0] == 'ADD')
						{
						unset($singlemod[0]);
						$singlemod_option = implode(" ", $singlemod);
						$get_charged_items =  mysql_query("SELECT * FROM grillmenus WHERE Name = '$singlemod_option'")or die(mysql_error());
						$charged_items = mysql_fetch_array($get_charged_items);
						$mod_price = $mod_price + $charged_items['price'];
						}					
				}
							// This should be moved in time
			/* This code prevents the same set of modifications from being displayed on multiple items by removing a set of modifications from the array once it is
			displayed. It is worth noting that although values are removed from the array, the numbering of keys isn't changed. */
			if (isset($_COOKIE['mods'])) // This if-statement might not be needed
			{
			$needle = $item['ID']."|".implode("~", $modifications);	
			$del_mod = array_search($needle, $items_modified);
			unset($items_modified[$del_mod]); // I am not sure how needed this bloclk is.
			} 
		break; // This break ensures that one item doesn't show all the sets of modifications made to items with the same ID
			} 
		}

		
# The HTML code used to display items
echo "<tr style=\"height:10px\">
<!-- Void Item Key -->";
if ($i == $k)
{
echo "<td><button OnClick=\"deleteItem(".$item['ID'].",'".$needle."')\">Void</button></td>";
}
else
{
echo "<td><button OnClick=\"deleteItem(".$item['ID'].",'')\">Void</button></td>";
}

	echo "<!-- Item Title & Link to Grill -->
	<td><u style=\"color: #0000FF\" OnClick=\"grill(".$item['ID'].", ".$item['grill_category'].", '".$item['Title']."')\">".$item['Title']."</u>";
	

		echo $PrintModifications;
		unset($PrintModifications);
		unset($needle);
echo "</td>
		<!-- Item Price -->
		<td align=\"right\">$".$item['price']."</td>
</tr>";
$total_price = $total_price + $item['price'];
}

// Displays the order's totals
$total_price = $total_price + $mod_price;
$_SESSION['price'] = $total_price;
$after_tax = round($total_price+($total_price * $SALES_TAX), 2);
echo "<tr valign=\"top\"><td colspan=\"2\"><u>Sub Total:</u><br /><u>Total:</u></td>
	<td align=\"right\">$".number_format($total_price,2)."<br />$".number_format($after_tax,2)."</td></tr>
	</table></body></html>";

// Set of code for deleting items, triggerd by the JS function deleteItem(item_ID). /
// Only ran if a cookie is set by deleteItem(item_ID).
if (isset($_COOKIE['del_item']))
{
$del_item = clean($_COOKIE['del_item']); // The item to be deleted, obtained from a cookie
$del_item = array_search($del_item, $order); // Searches the order for an the item to be deleted
unset($order[$del_item]); // Deletes the item from the order

// Deletes the cookie made by deleteItem(item_ID). This ensures that this block of code is not ran again.
setcookie("del_item", "", time()-3600);
unset($_COOKIE['del_item']);

// Sets a cookie containing the new, modified, order. vPOS.php pulls this information to know what the order is when adding new items.
setcookie("order", implode(";", $order));

	// Deletes any modifications made to the item that has just been deleted
	if (isset($_COOKIE['del_mods']))
	{
	$needle = clean($_COOKIE['del_mods']); // The item to be deleted, obtained from a cookie
	$haystack = clean($_COOKIE['mods']);
	$haystack = explode(",", $haystack);
	$del_mods = array_search($needle, $haystack);
	unset($haystack[$del_mods]); 
	
	// Deletes the cookie made by deleteItem(item_ID).
	setcookie("del_mods", "", time()-3600);
	unset($_COOKIE['del_mods']);
		if (implode(",", $haystack) == 'null')
		{
		setcookie("mods", "", time()-3600);

		}

		else
		{
		// Sets a cookie containing the new, modified, list of modifications.
		setcookie("mods", implode(",", $haystack));
		}
	}
	
// Reloads cod.php in order to display the new, modified, order
$url = "cod.php?order=".implode(";", $order); 
header("Location: $url");
}
ob_end_flush();
?>
