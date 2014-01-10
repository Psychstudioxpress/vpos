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
// ConfigurationVariable.java
// 
// Provides an object that creates and allows customization of
// conf.php

package installAssistant;

/** 
 * Provides an object to represent configuration variables.
 */
public class ConfigurationVariable
{
    private String variableName; // Name of the variable
    private String variableValue; // The variable's value
    private String variableComment; // The comment accompanying the variable

    /**
     * Constructor with only the variable's name
     *
     * @param name the variable's name
     */
    public ConfigurationVariable(String name)
    {
		variableName = name;
    }

    /**
     * Constructor with the variable's name and a value
     *
     * @param name the variable's name
     * @param value the variable's value
     */
    public ConfigurationVariable(String name, String value)
    {
		variableName = name;
		variableValue = value;
    }

    /**
     * Constructor with the variable's name and a comment
     *
     * @param name the variable's name
     * @param comment a comment for the variable
     */
    /*public ConfigurationVariable(String name, String comment)
    {
		variableName = name;
		variableComment = comment;
    }*/

    /**
     * A detailed constructor 
     *
     * @param name the variable's name
     * @param value the variable's value
     * @param comment a comment for the variable
     */
    public ConfigurationVariable(String name, String value, String comment)
    {
		variableName = name;
		variableValue = value;
		variableComment = comment;
    }

    /**
     * Accessor for name
     */
    public String getName()
    {
    	return variableName;
    }

    /**
     * Accessor for value
     */
    public String getValue()
    {
    	return variableValue;
    }

    /**
     * Accessor for comment
     */
    public String getComment()
    {
    	return variableComment;
    }

    /**
     * Mutator for value
     * @param value the variable's new value
     */
    public void setValue(String value)
    {
    	variableValue = value;
    }

    /**
     * Mutator for value
     * @param value the variable's new comment
     */
    public void setComment(String comment)
    {
    	variableComment = comment;
    }

	/**
	 * toString method for configuration variables
	 */
	public String toString()
	{
		return "$" + variableName + " = " + variableValue + "; // " 
			+ variableComment;
	}
}
