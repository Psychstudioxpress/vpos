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
// generateDatabase.php
// This file allows users to initialize the database and create
// their initial user. 
// NOTE: It is recommended that this file is deleted after use

include 'conf.php';
mysql_query("create database vpos")or die(mysql_error());
mysql_select_db("vpos");

if (isset($_POST['submit']))
{
	$username = clean($_POST['username']);
	$password = crypto($_POST['password']);
	$name = clean($_POST['name']);
	mysql_query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'")or die(mysql_error());

	mysql_query("CREATE TABLE IF NOT EXISTS `correct_orders` (
  `oid` int(11) NOT NULL,
  `items` text NOT NULL,
  `modifications` text NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1")or die(mysql_error());

	mysql_query("CREATE TABLE IF NOT EXISTS `grillmenus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Category` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ADD` int(11) NOT NULL,
  `W/O` int(11) NOT NULL,
  `XTRA` int(11) NOT NULL,
  `LIGHT` int(11) NOT NULL,
  `ONLY` int(11) NOT NULL,
  `SUB` int(11) NOT NULL,
  `AMNT` int(11) NOT NULL,
  `Yes/No` int(11) NOT NULL,
  `price` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32")or die(mysql_error());

	mysql_query("INSERT INTO `grillmenus` (`ID`, `Category`, `Name`, `ADD`, `W/O`, `XTRA`, `LIGHT`, `ONLY`, `SUB`, `AMNT`, `Yes/No`, `price`) VALUES
(1, 3, 'Mustard', 1, 1, 1, 0, 1, 0, 0, 0, '0'),
(2, 3, 'Mayo', 1, 1, 1, 1, 1, 1, 0, 0, '0'),
(3, 3, 'Ketchup', 1, 1, 1, 1, 1, 1, 0, 0, '0'),
(4, 3, 'Tartar', 1, 1, 1, 1, 1, 0, 0, 0, '0'),
(5, 3, 'Diced Onions', 1, 1, 1, 1, 1, 1, 0, 0, '0'),
(6, 3, 'Slivered Onions', 1, 1, 1, 1, 1, 1, 0, 0, '0'),
(7, 3, 'Pickles', 1, 1, 1, 1, 1, 0, 0, 0, '0'),
(8, 3, 'Shread Lettuce', 1, 1, 1, 1, 1, 0, 0, 0, '0'),
(9, 3, 'Tomato', 1, 1, 1, 1, 1, 0, 0, 0, '0'),
(10, 3, 'American Cheese', 1, 1, 1, 1, 1, 1, 0, 0, '.40'),
(11, 3, 'Swiss Cheese', 1, 1, 1, 1, 1, 1, 0, 0, '.50'),
(12, 3, 'Small Meat', 1, 1, 1, 0, 0, 1, 0, 0, '.90'),
(13, 3, 'Large Meat', 1, 1, 1, 0, 0, 1, 0, 0, '1.50'),
(14, 3, 'Spicy  Chicken Patty', 1, 1, 1, 0, 0, 0, 0, 0, '1.00'),
(15, 3, 'Crispy Chicken Patty', 1, 1, 1, 0, 0, 0, 0, 0, '1.50'),
(16, 3, 'Grilled Chicken Patty', 1, 1, 1, 0, 0, 0, 0, 0, '1.50'),
(17, 3, 'Bacon', 1, 1, 1, 1, 1, 0, 0, 0, '.75'),
(18, 3, 'Fish Filet', 1, 1, 1, 0, 0, 0, 0, 0, '1.30'),
(19, 3, 'Regular Bun', 0, 0, 0, 0, 0, 1, 0, 0, '0'),
(20, 3, 'Seasme Seed Bun', 0, 0, 0, 0, 0, 1, 0, 0, '0'),
(21, 3, 'Texas Toast', 0, 0, 0, 0, 0, 1, 0, 0, '0'),
(22, 3, 'Well Done', 0, 0, 0, 0, 0, 0, 0, 1, '0'),
(23, 3, 'Plain', 0, 0, 0, 0, 0, 0, 0, 1, '0'),
(24, 2, 'Ice', 0, 1, 1, 1, 1, 0, 0, 0, '0'),
(25, 4, 'Salt', 0, 1, 1, 0, 0, 0, 0, 0, '0'),
(26, 4, 'Fresh', 0, 0, 0, 0, 0, 0, 0, 1, '0'),
(27, 5, 'Fudge', 1, 1, 1, 1, 0, 0, 0, 0, '.35'),
(28, 5, 'Strawberry', 1, 1, 1, 1, 0, 0, 0, 0, '.30'),
(29, 5, 'Nuts', 1, 0, 0, 1, 0, 0, 0, 0, '.20'),
(30, 5, 'Whipcream', 1, 0, 0, 1, 0, 0, 0, 0, '.30'),
(31, 5, 'Chocolate Icecream', 0, 0, 0, 0, 0, 1, 0, 0, '0')")or die(mysql_error());

	mysql_query("CREATE TABLE IF NOT EXISTS `items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `ShortTitle` varchar(20) NOT NULL,
  `Category` int(11) NOT NULL,
  `grill_category` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=163")or die(mysql_error());;

	mysql_query("INSERT INTO `items` (`ID`, `Title`, `ShortTitle`, `Category`, `grill_category`, `price`) VALUES
(1, 'Hot & Spicy', 'HSMC', 0, 3, '1.00'),
(2, 'McDouble', 'McDbl', 0, 3, '1.00'),
(3, 'Quater Pounder w/ Cheese', 'QPC', 0, 3, '3.56'),
(4, 'Filet-O-Fish', 'Filet', 0, 3, '3.45'),
(5, 'Homestyle', 'HMESTYL', 0, 3, '4.00'),
(6, 'McRib', 'McRib', 0, 3, '2.99'),
(7, 'Lg Powerade', 'L PWRADE', 0, 0, '1.00'),
(8, 'Lg Fry', 'L FRY', 0, 0, '2.12'),
(9, 'Senior Coke', 'SR COKE', 2, 2, '.50'),
(10, 'Small Coke', 'SM COKE', 2, 2, '1.00'),
(11, 'Medium Coke', 'M COKE', 2, 2, ''),
(12, 'Large Coke', 'L COKE', 2, 2, '1.00'),
(13, 'Senior DT Coke', 'SR DT COKE', 2, 2, '.50'),
(14, 'Small DT Coke', 'SM DT COKE', 2, 2, '1.00'),
(15, 'Medium DT Coke', 'M DT COKE', 2, 2, '1.00'),
(16, 'Large DT Coke', 'L DT COKE', 2, 2, '1.00'),
(17, 'Senior Dr Pepper', 'SR DR PEP', 2, 2, '.50'),
(18, 'Small Dr Pepper', 'SM DR PEP', 2, 2, '.50'),
(19, 'Medium Dr Pepper', 'M DR PEP', 2, 2, '1.00'),
(32, 'Large Dr Pepper', 'L DR PEP', 2, 2, '.50'),
(33, 'Senior DT DR', 'SR DT DP', 2, 2, '1.00'),
(34, 'Small DT DR', 'SM DT DR', 0, 0, ''),
(35, 'Medium DT DR', 'M DT DR', 2, 2, '1.00'),
(36, 'Large DT DR', 'L DT DR', 2, 2, '1.00'),
(37, 'Senior Sprite', 'SR SPRITE', 2, 2, '.50'),
(38, 'Small Sprite', 'SM SPRITE', 2, 2, '1.00'),
(39, 'Medium Sprite', 'M SPRITE', 2, 2, '1.00'),
(40, 'Large Sprite', 'L SPRITE', 2, 2, '1.00'),
(41, 'Senior Red Flash', 'SR RED', 2, 2, '.50'),
(42, 'Small Red Flash', 'SM RED', 2, 2, '1.00'),
(43, 'Medium Red Flash', 'M RED', 2, 2, '1.00'),
(44, 'Large Red Flash', 'L RED', 2, 2, '1.00'),
(45, 'Senior Hi-C Orange', 'SR HI-C', 2, 2, '1.00'),
(46, 'Small Hi-C Orange', 'SM HI-C', 2, 2, ''),
(47, 'Medium Hi-C Orange', 'M HI-C', 2, 2, '1.00'),
(48, 'Large Hi-C Orange', 'L HI-C', 2, 2, '1.00'),
(49, 'Senior Sweet Tea', 'SR STEA', 2, 2, '.50'),
(50, 'Small Sweet Tea', 'SM STEA', 2, 2, '1.00'),
(51, 'Medium Sweet Tea', 'M STEA', 2, 2, '1.00'),
(52, 'Large Sweet Tea', 'L STEA', 2, 2, '1.00'),
(53, 'Senior Unsweet Tea', 'SR UTEA', 2, 2, '.50'),
(54, 'Small Unsweet Tea', 'SM UTEA', 2, 2, '1.00'),
(55, 'Medium Unsweet Tea', 'M UTEA', 2, 2, '1.00'),
(56, 'Large Unsweet Tea', 'L UTEA', 2, 2, '1.00'),
(57, 'Senior Coffee', 'SR COFFEE', 2, 7, '.50'),
(58, 'Small Coffee', 'SM COFFEE', 2, 7, '1.00'),
(59, 'Medium Coffee', 'M COFFEE', 2, 7, '1.40'),
(60, 'Large Coffee', 'L COFFEE', 2, 7, '1.60'),
(61, 'Senior Decaf', 'SR DCAF', 2, 7, '.50'),
(62, 'Small Decaf', 'SM DCAF', 2, 7, '1.00'),
(63, 'Medium Decaf', 'M DCAF', 2, 7, '1.40'),
(64, 'Large Decaf', 'L DCAF', 2, 7, '1.60'),
(65, 'Small Cherry Lime Smthie', 'SM CRRY LME', 2, 2, '1.80'),
(66, 'Medium Cherry Lime Smthie', 'M CRRY LME', 2, 2, '2.30'),
(67, 'Large Cherry Lime Smthie', 'L CRRY LME', 2, 2, '2.70'),
(68, 'Small RASP LMND Smthie', 'SM RASP LME', 2, 2, '1.80'),
(69, 'Medium RASP LMND Smthie', 'M RASP LME', 2, 2, '2.30'),
(70, 'Large RASP LMND Smthie', 'L RASP LME', 2, 2, '2.70'),
(71, 'Small Choc. Shake', 'SM CHOC SHKE', 2, 2, '1.70'),
(72, 'Medium Choc. Shake', 'M CHOC SHKE', 2, 2, '2.20'),
(73, 'Large Choc. Shake', 'L CHOC SHKE', 2, 2, '2.40'),
(74, 'Small Vanilla Shake', 'SM VAN SHKE', 2, 2, '1.70'),
(75, 'Medium Vanilla Shake', 'M VAN SHKE', 2, 2, '2.20'),
(76, 'Large Vanilla Shake', 'L VAN SHKE', 2, 2, '2.40'),
(77, 'Small Fry', 'S FRY', 4, 4, '1.00'),
(78, 'Medium Fry', 'M FRY', 4, 4, '1.40'),
(79, 'Large Fry', 'L FRY', 4, 4, '2.12'),
(80, 'Side Salad', 'S SALAD', 4, 6, '1.00'),
(81, 'Vanilla Cone', 'V CONE', 5, 6, '1.00'),
(82, 'Chocolate Cone', 'CHOC CONE', 5, 6, '1.00'),
(83, 'Dip Cone', 'DIP CONE', 5, 6, '1.59'),
(84, 'Fudge Sundae', 'FUDGE S', 5, 5, '1.19'),
(85, 'Strawberry Sundae', 'STRAW S', 5, 5, '1.19'),
(86, 'Chocolate Chip', 'CHOC CHIP', 5, 6, '.50'),
(87, 'Sugar Cookie', 'SUGAR COOK', 5, 6, '.50'),
(88, 'Apple Pie', 'APPLE PIE', 5, 6, '.69'),
(89, 'Fruit n Yogurt Par', 'PARAFAIT', 5, 6, '1.00'),
(90, 'Gravy', 'GRAVY', 6, 6, '0.00'),
(91, 'XTRA Gravy', 'XTRA GRVY', 6, 6, '.75'),
(92, 'BBQ Sauce', 'BBQ', 6, 6, '0.00'),
(93, 'Sweet n Sour', 'S&S', 6, 6, '0.00'),
(94, 'Spicy Buffalo', 'BUFFLO', 6, 6, '0.00'),
(95, 'Honey Mustard', 'HNY MUST', 6, 6, '0.00'),
(96, 'Ranch Sauce', 'RANCH', 6, 6, '0.00'),
(97, 'Ranch Dressing', 'RANCH DRES', 6, 6, '0.00'),
(98, 'Balsamic Vinaigrette', 'VINGRTE', 6, 6, '0.00'),
(99, 'Caesar Dressing', 'CAESAR', 6, 6, '0.00'),
(100, 'Thousand Islands', '1k ISLNDS', 6, 6, '0.00'),
(101, 'Salt', 'SALT', 6, 6, '0.00'),
(102, 'Pepper', 'PEPPER', 6, 6, '0.00'),
(103, 'XTRA Sauce', 'XTRA SAUCE', 6, 6, '.25'),
(104, 'XTRA Dressing', 'XTRA DRESS', 6, 6, '.75'),
(105, 'Hamburger', 'HAMBURG', 3, 3, '.90'),
(106, 'Cheeseburger', 'CHZ BURG', 3, 3, '.98'),
(107, 'Double Meat Cheeseburger', 'DBL MEAT CHZ', 3, 3, '1.00'),
(108, 'Double Cheeseburger', 'DBL CHZ', 3, 3, '1.40'),
(109, 'Deluxe', 'DELUXE', 3, 3, '3.75'),
(110, 'Double Deluxe', 'DBL Deluxe', 3, 3, '4.50'),
(111, 'Bacon and Cheese Burger', 'BnC Burger', 3, 3, '4.25'),
(112, 'Patty Melt', 'PTY MELT', 3, 3, '3.25'),
(113, 'Spicy Chicken', 'SPICY', 3, 3, '1.00'),
(114, 'Crispy BLT', 'CR BLT', 3, 3, '4.25'),
(115, 'Grilled BLT', 'G BLT', 3, 3, '4.25'),
(116, 'Crispy Club', 'CR CLUB', 3, 3, '4.75'),
(117, 'Grilled Club', 'GR CLUB', 3, 3, '4.75'),
(118, 'Grilled Cheese', 'GRILL CHZ', 3, 3, '1.25'),
(119, 'Fish Filet Sandwich', 'FILET', 3, 3, '3.30'),
(120, '3 Piece Strip', '3PC STRP', 3, 6, '3.50'),
(121, '5 Piece Strip', '5PC STRP', 3, 6, '5.25'),
(122, '10 Piece Strip', '10PC STRP', 3, 6, '9.95'),
(123, '#1 Medium Value Burger Meal', '#1 M VAL BURG', 1, 3, '2.40'),
(124, '#1 Large Value Burger Meal', '#1 L VAL BURG', 1, 3, '3.10'),
(125, '#2 Medium Deluxe', '#2 M DELX', 1, 3, '5.10'),
(126, '#2 Large Deluxe', '#2 L DELX', 1, 3, '5.80'),
(127, '#3 Med Double Deluxe', '#3 M DBL DLX', 1, 3, '5.80'),
(128, '#3 Lg Double Deluxe', '#3 L DBL DLX', 1, 3, '6.50'),
(129, '#4 Med BnC Meal', '#4 M BnC', 1, 3, '5.60'),
(130, '#4 Lg BnC Meal', '#4 L BnC', 1, 3, '6.30'),
(131, '#5 Med Crisp BLT', '#5 M C BLT', 1, 3, '5.60'),
(132, '#5 Lg Crisp BLT', '#5 L C BLT', 1, 3, '6.30'),
(133, '#5 Med Grill BLT', '#5 M G BLT', 1, 3, '5.60'),
(134, '#5 Lg Grill BLT', '#5 L G BLT', 1, 3, '6.30'),
(135, '#6 Med Grill Chz Meal', '#6 M GRL CHZ ML', 1, 3, '2.60'),
(136, '#6 Lg Grill Chz Meal', '#6 L GRL CHZ ML', 1, 3, '3.30'),
(137, '#7 Med Filet Meal', '#7 M FILET', 1, 3, '4.65'),
(138, '#7 Lg Filet Meal', '#7 L FILET', 1, 3, '5.35'),
(139, '#8 Med Spicy Meal', '#8 M SPCY', 1, 3, '2.25'),
(140, '#8 Lg Spicy Meal', '#8 L SPCY', 1, 3, '2.95'),
(141, '#9 Med 5pc Strip ', '#9 M 5PC DINR', 1, 3, '6.60'),
(142, '#9 Lg 5pc Strip ', '#9 L 5PC DINR', 1, 3, '7.30'),
(143, 'Medium Coke', 'M COKE', 1, 2, '1.00'),
(144, 'Large Coke', 'L COKE', 1, 2, '1.00'),
(145, 'Medium Diet Coke', 'MD DT COKE', 1, 2, '1.00'),
(146, 'Large Diet Coke', 'L DT COKE', 1, 2, '1.00'),
(147, 'Medium Dr Pepper', 'M DR PEP', 1, 2, '1.00'),
(148, 'Large Dr Pepper', 'L DR PEP', 1, 2, '1.00'),
(149, 'Medium Sprite', 'M SPRITE', 1, 2, '1.00'),
(150, 'Large Sprite', 'L SPRITE', 1, 2, '1.00'),
(151, 'Medium Sweet Tea', 'M STEA', 1, 2, '1.00'),
(152, 'Large Sweet Tea', 'L STEA', 1, 2, '1.00'),
(153, 'Small Fry', 'S FRY', 1, 4, '1.00'),
(154, 'Medium Fry', 'M FRY', 1, 4, '1.40'),
(155, 'Large Fry', 'L FRY', 1, 4, '2.12'),
(156, 'Double Meat Cheeseburger', 'DBL MEAT CHZ', 1, 3, '1.00'),
(157, 'Spicy Chicken', 'SPICY', 1, 3, '1.00'),
(158, 'Ketchup', 'KTCHUP', 6, 6, '0.00'),
(159, 'Creamer Pkt', 'CREAM', 6, 6, '0.00'),
(160, 'Sugar Pkt', 'SUGAR', 6, 6, '0.00'),
(161, 'Splenda Pkt', 'SPLENDA', 6, 6, '0.00'),
(162, 'Equal PKT', 'EQUAL', 6, 6, '0.00')")or die(mysql_error());;

	mysql_query("CREATE TABLE IF NOT EXISTS `orders` (
  `oid` int(11) NOT NULL,
  `supervisor_ID` varchar(32) NOT NULL,
  `participant_ID` varchar(32) NOT NULL,
  `workstation` int(11) NOT NULL,
  `start_time` int(15) NOT NULL,
  `stored_time` int(15) NOT NULL,
  `items` text NOT NULL,
  `modifications` text NOT NULL,
  `reductions` int(15) NOT NULL,
  `repeats` int(11) NOT NULL,
  `price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1")or die(mysql_error());
	

	mysql_query("CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `menu` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3")or die(mysql_error());
mysql_query("INSERT INTO `users` (`username`, `password`, `name`, `menu`) VALUES ('$username', '$password', '$name', 'lunch')")or die(mysql_error());

echo "
		<head>
			<title>vPOS Installation Assistant</title>
		</head>
		
		<body>
			<center>
				<h3>vPOS Installation Assistant</h3>
				<h4>Database Generator</h4>
			</center>
			
			<p>Congradulations, vPOS is now ready to use in manual mode!</p>
		</body>
	</html>";

}

else
{
echo "<html>
		<head>
			<title>vPOS Installation Assistant</title>
		</head>
		
		<body>
			<center>
				<h3>vPOS Installation Assistant</h3>
				<h4>Database Generator</h4>
			</center>
			
			<p>Please specify a vPOS user account.</p>
			<form name=\"userCreation\" method=\"post\">
				<table>
					<tr>
						<td>Username:</td><td><input type=\"text\" name=\"username\" /></td>
					</tr>
					<tr>
						<td>Password:</td><td><input type=\"password\" name=\"password\" /></td>
					</tr>
					<tr>
						<td>Name:</td><td><input type=\"text\" name=\"name\" /></td>
					</tr>
					<tr>
						<td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Generate Database\" />
				</table>
			</form>
		</body>
	</html>";
}

