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
// InstallAssistant.java
// 
// Provides an object that creates and allows customization of
// conf.php

package installAssistant;

import java.util.Scanner;
import java.util.ArrayList;
import java.io.File;

/**
 * Assists users in configuring the configuration file
*/
public class InstallAssistant
{
	/**
	 * The main method
	 */
	public static void main(String[] args)
	{
		char input;
		String rawInput;
		Scanner scan = new Scanner(System.in);
		
		System.out.println("*** Psychstudioxpress ***");
		System.out.println("***        vPOS		  ***");
		System.out.println("This program will configure vPOS for use.\n");
		System.out.print("Use default configuration settings (Y/N)? ");
		rawInput = scan.nextLine();
		input = rawInput.toLowerCase().charAt(0);
		
		while (input != 'y' && input != 'Y' && input != 'n' && input != 'N')
		{
			System.out.println("Invalid input. Use default configuration" 
				+ " settings (Y/N)? ");
			rawInput = scan.nextLine();
			input = rawInput.toLowerCase().charAt(0);
		}

		if (input == 'y' || input == 'Y')
			defaultInstallation();
		else if (input == 'n' || input == 'N')
			customInstallation(); 
	}

	/**
	 * Installs a configuration file with default settings
	 */
	private static void defaultInstallation()
	{
		ConfigurationFile confFile = new ConfigurationFile();
		confFile.generateFile(new File("conf.php"));
		System.out.println("Configuration File Created!");
		System.out.println("Please execute createDatabase.php to initialize "
			+ "database");	
	}

	/**
	 * Prompts user for their configuration settings and creates a 
		configuration file with those settings.
	 */
	private static void customInstallation()
	{
		String value;
		String comment;
		String[] confVarNames = {"PROJECT_TITLE", "USAGE", "BUILD_NO", 
			"SERVER", "MYSQL_USER", "MYSQL_PASSWORD", "MYSQL_DB", 
			"DEFAULT_MENU", "SALES_TAX", "SYSTEM_PASSWORD", "AUTOMATE",
			"SOUND_PATH"};
		ArrayList<ConfigurationVariable> confVars = 
			new ArrayList<ConfigurationVariable>();
		Scanner scan = new Scanner(System.in);
		ConfigurationFile confFile = new ConfigurationFile(confVars);

		for (int i = 0; i < confVarNames.length; i++)
		{
			System.out.print("Please enter the value for " + confVarNames[i]
				+ ": ");
			value = scan.nextLine();
			System.out.print("Please enter any comment for " + confVarNames[i]
				+ ": ");
			comment = scan.nextLine();
			confVars.add(new ConfigurationVariable(confVarNames[i],
				value, comment));
		}						

		confFile.generateFile(new File("conf.php"));
		System.out.println("\nConfiguration File Created!");
		System.out.println("Please execute createDatabase.php to initialize "
			+ "database");	
	}
}

