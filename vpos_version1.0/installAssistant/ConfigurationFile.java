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
// ConfigurationFile.java
// 
// Provides an object that creates and allows customization of
// conf.php

package installAssistant;
import java.io.*;
import java.util.ArrayList;
import java.util.Iterator;

/** 
 * An object that creates and allows customization of conf.php.
 */
public class ConfigurationFile
{
    private ArrayList<ConfigurationVariable> configurationVariables;	

    /**
     * Constructor set to default values.
     */
    public ConfigurationFile()
    {
		configurationVariables = new ArrayList<ConfigurationVariable>();
		configurationVariables.add(new ConfigurationVariable("PROJECT_TITLE", 
			"Psych", "The title of the project using vPOS"));
		configurationVariables.add(new ConfigurationVariable("USAGE", "vPOS", 
			"What the application is doing, leave default if unsure"));
		configurationVariables.add(new ConfigurationVariable("BUILD_NO", 
			"Version 1.0", "Version number of vPOS, leave default if unsure"));
		configurationVariables.add(new ConfigurationVariable("SERVER", 
			"localhost", "Location of the MySQL database, "
				+ "leave default if unsure"));
		configurationVariables.add(new ConfigurationVariable("MYSQL_USER", 
			"root", "MySQL username"));
		configurationVariables.add(new ConfigurationVariable("MYSQL_PASSWORD", 
			"", "MySQL password"));
		configurationVariables.add(new ConfigurationVariable("MYSQL_DB", 
			"vpos", "The name of the database storing the vPOS data"));
		configurationVariables.add(new ConfigurationVariable("DEFAULT_MENU", 
			"lunch", "The name of the default \"menu\""));
		configurationVariables.add(new ConfigurationVariable("SALES_TAX", 
			"0.0825", "Sales tax in Decimal format, leave default if unsure"));
		configurationVariables.add(new ConfigurationVariable("SYSTEM_PASSWORD", 
			"admin", "The password for the manager menu"));
		configurationVariables.add(new ConfigurationVariable("AUTOMATE", "yes", 
			"Determines if the vPOS will automatically play orders, "
				+ "to disable, change to no"));
		configurationVariables.add(new ConfigurationVariable("SOUND_PATH", 
			"order_clips", "Path to sound files for automatic mode, "
				+ "leave blank if unsure"));
    }

    /**
     * Constructor taking an ArrayList of configuration variables.
     * @param configurationVars ArrayList of configuration variables
     */
    public ConfigurationFile(ArrayList<ConfigurationVariable> 
		configurationVars)
    {
		configurationVariables = configurationVars;
    }

    /**
     * Returns a list of all configuration variables 
     */
    public ArrayList<ConfigurationVariable> getVariables()
    {
		return configurationVariables;
    }

    /**
     * Outputs the contents of a conf.php to a file
     * @param outFile the file to be conf.php
     */
    public void generateFile(File outFile)
    {
		try
		{
			BufferedWriter output = new BufferedWriter(
				new FileWriter(outFile));
	   
			// Write header comment
			output.write("<?php");
			output.newLine();
			output.write("// vPOS");
            output.newLine();
			output.write("// Version 1.0");
			output.newLine();
			output.write("// conf.php");
            output.newLine();
			output.write("// The following are the only php variables that");
            output.write(" must be edited to configure vPOS.");
            output.newLine();
			output.write("// This file is loaded into each vPOS file.");
            output.newLine();
            output.newLine();

            // Write configuration variables
            Iterator<ConfigurationVariable> confVars = 
				configurationVariables.iterator();
			
			while (confVars.hasNext())
			{
				ConfigurationVariable current = confVars.next();
				output.write("$" + current.getName() + " = \"");
                output.write(current.getValue() + "\"; ");
                output.write("// " + current.getComment());
                output.newLine();
            }
	    
            // Output MySQL connection and session start
			output.newLine();
            output.write("session_start();"); 
            output.newLine();
            output.newLine();
			output.write("// Make a MySQL Connection");
            output.newLine();
			output.write("mysql_connect(\"$SERVER\", \"$MYSQL_USER\", " 
				+ "\"$MYSQL_PASSWORD\") or die(mysql_error());"); 
            output.newLine();
			output.write("$database = mysql_select_db(\"$MYSQL_DB\");"); 
            output.newLine();
            output.newLine();

			// Output global functions
 
			output.write("// Global Functions");
			output.newLine();
			output.newLine();
			output.write("// Clean Input Function");
			output.newLine();
			output.write("function clean($input)");  
			output.newLine();
			output.write("{"); 
			output.newLine();
			output.write("\t$whattostrp = array(\"'\", \")\", \"(\", \"*\","
				+ "\">\",\"<\");"); 
			output.newLine();
			output.write("\t$input = str_replace($whattostrp, \"\", "
				+ "\"$input\");"); 
			output.newLine();
			output.write("\t$input=stripslashes($input);"); 
			output.newLine();
			output.write("\t$input=strip_tags($input);"); 
			output.newLine();
			output.write("\t$input=mysql_real_escape_string($input);"); 
			output.newLine();
			output.write("\treturn $input;"); 
			output.newLine();
			output.write("}"); 
			output.newLine();
			output.newLine();

			output.write("// Encryption Function"); 
			output.newLine();
			output.write("function crypto($input)"); 
			output.newLine();
			output.write("{"); 
			output.newLine();
			output.write("\t$salt[0] =  \"aBdsajASD243Hasd\";"); 
			output.newLine();
			output.write("\t$salt[1] = \"aazcdkfs\";"); 
			output.newLine();
			output.write("\t$crypt[0] = crc32($input);"); 
			output.newLine();
			output.write("\t$crypt[1] = crypt($input, $salt[0]);"); 
			output.newLine();
			output.write("\t$crypt[2] = md5($input);"); 
			output.newLine();
			output.write("\t$crypt = implode($salt[1], $crypt);"); 
			output.newLine();
			output.write("\treturn sha1($input.$crypt);"); 
			output.newLine();
			output.write("}"); 
			output.newLine();
	    
			output.close();
		}
	
		catch (IOException e)
		{
			// Fix this later
			e.printStackTrace();
		}
    }
}

