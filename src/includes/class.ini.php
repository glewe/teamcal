<?php
// ===========================================================================
//  CLASS.INI.PHP
//  --------------------------------------------------------------------------
//  Version:   3.0
//  Purpose:   Read and write from/to INI files
//  Author:    Jorgen Horstink
//  Copyright: (c) 2002 by Next Avenue, The Netherlands
//             All rights reserved.
//  --------------------------------------------------------------------------
//  Sample:
//
//  $ini = new ini;
//  $inilink = $ini->connect("sample.ini");
//  $ini->get_keys("section",$inilink);
//  $ini->get_sections($inilink);
//  $ini->drop_key("section","key",$inilink);
//  $ini->drop_section("section",$inilink);
//  $ini->key_exists("section","key",$inilink);
//  $ini->section_exists("section",$inilink);
//  $ini->read("section","key",$inilink);
//  $ini->write("section","key","value",$inilink);
//  $ini->close($inilink);
//
// ===========================================================================

error_reporting(E_ALL);

class myini {
    var $ini_data = array (); // Array to store all ini data
    var $ini_data_files = array (); // Array to store all file names
    var $crlf = "\n"; // "Carriage return", SEE function connect
    var $f_temp = "tempA9B4E3F4C9_"; // The temporary file, SEE function connect
    var $set_waiting_limit = 10; // The maximum waiting time in seconds, SEE function connect

    // Return: Link_Identifier (integer)
    function connect($file)
    {
        $temp = $this->f_temp;
        $pos = strrpos($file,"/");
        if ($pos === false) {
            // Not found
            $this->f_temp .= $file;
        }else{
            $path = substr($file,0,$pos+1);
            $filename = substr($file,$pos+1);
            $this->f_temp .= $filename;
            $this->f_temp = $path . $this->f_temp;
        }

        $t = 0; // Set the counter var

        // While another file is busy... wait wait wait
        while (@is_array(file($this->f_temp))) {
            $t++;

            //  IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT
            //
            //  If someone does not use the function Close, the temporary file will not be deleted.
            //  But if you open the file for the second time, the function Connect notices that
            //  the temporary file already exists. So the functions 'thinks' some other file is busy. But
            //  that is not true, the file has not been deleted.
            //  To solve this problem, the function writes the timestamp in the temporary file. When the
            //  function notices that the file already exists, it checks whether or not the last opened
            //  timestamp is too old. If the timestamp is too old, the function notices that the function
            //  Close probably is not used. So the function deletes the temporary file and goes on...
            //
            //  IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT

            // Get the time out of the file, when the last time the script is parsed
            $time = file($this->f_temp);
            // If the time is too old, delete the file and break the while-statement
            if ((time() - $this->set_waiting_limit) > $time[0]) {
                unlink($this->f_temp);
                break;
            }

            sleep(1); // Check every second...
            if ($t > $this->set_waiting_limit) break;
        }

        // Create the temporary file
        $fp = fopen($this->f_temp, "w+");
        fwrite($fp, time());
        fclose($fp);

        $this->f_temp = $temp;

        // If the file does not exist, return -1
        if (!file_exists($file))
            return(-1);

        // Check whether or not there already is a connection with the selected file
        for ($i = 0; $i < sizeof($this->ini_data_files); $i++)
            if ($this->ini_data_files[$i] == $file)
                return($i);

        // Get all ini data and store it in an Array
        $ini_array = parse_ini_file($file, TRUE);
        // Add the Array to ini_data[]
        $this->ini_data[] = $ini_array;
        // Store the filename in $ini_data_files[]
        $this->ini_data_files[] = $file;

        // Return the new Link_Identifier
        return(sizeof($this->ini_data) - 1);
    }

    // Return: Boolean
    function close($link = "")
    {
        $data_string = "";

        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // Get all ini data corresponding to the Link_Identifier
        $get_data_from_array = $this->ini_data[$link];
        $array_keys = array_keys($get_data_from_array);

        // Build the new String...
        for ($i = 0; $i < sizeof($get_data_from_array); $i++) {
            $get_data_from_key = $get_data_from_array[$array_keys[$i]];
            $key_array_keys = array_keys($get_data_from_key);
            $data_string .= "[" . $array_keys[$i] . "]" . $this->crlf;
            for ($j = 0; $j < sizeof($key_array_keys); $j++)
                $data_string .= $key_array_keys[$j] . "=" . $get_data_from_key[$key_array_keys[$j]] . $this->crlf;
        }

        // Get the filename
        $filename = $this->ini_data_files[$link];
        // If the file does not exist, return FALSE
        if (!file_exists($filename))
            return(FALSE);

        @unlink($this->f_temp . $filename);

        // Try to write all data into the file
        $fp = fopen($filename, "w+");
        fwrite($fp, $data_string);
        fclose($fp);

        // Delete the old data
        unset($this->ini_data[$link]);

        // Delete the old filename
        unset($this->ini_data_files[$link]);

        return(TRUE);
    }

    // Return: Boolean
    function drop_key($section, $key, $link)
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // If the section does not exist, return FALSE
        if (!$this->section_exists($section))
            return(FALSE);

        // If the key does not exist, return FALSE
        if (!$this->key_exists($section, $key))
            return(FALSE);

        // Unset the key in the chosen section
        unset($this->ini_data[$link][$section][$key]);
        return(TRUE);
    }

    // Return: Boolean
    function drop_section($section, $link)
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // If the section does not exist, return FALSE
        if (!$this->section_exists($section))
            return(FALSE);

        // Unset the section
        unset($this->ini_data[$link][$section]);
        return(TRUE);
    }

    // Return: Array
    function get_keys($section, $link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // If the section does not exist, return FALSE
        if (!$this->section_exists($section, $link))
            return(FALSE);

        // Get all keys and return them
        $get_data = $this->ini_data[$link][$section];

        return(array_keys($get_data));
    }

    // Return: Array
    function get_sections($link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        $get_data = $this->ini_data[$link];
        return(array_keys($get_data));
    }

    // Return: Boolean
    function key_exists($section, $key, $link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // If the section does not exist, return FALSE
        if (!$this->section_exists($section, $link))
            return(FALSE);

        // Get all keys
        $keys = $this->get_keys($section, $link);

        for ($i = 0; $i < sizeof($keys); $i++)
            if ($keys[$i] == $key)
                return(TRUE);

        // If no section is found, return FALSE
        return(FALSE);
    }

    // Return: Boolean
    function section_exists($section, $link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // Get all sections
        $sections = $this->get_sections($link);

        // Check whether or not the section exists
        for ($i = 0; $i < sizeof($sections); $i++)
            if ($sections[$i] == $section)
                return(TRUE);

            // If no section is found, return FALSE
        return(FALSE);
    }

    // Return: String
    function read($section, $key, $link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        // If the section does not exist, return FALSE
        if (!$this->section_exists($section, $link))
            return(FALSE);

        // If the key does not exist, return FALSE
        if (!$this->key_exists($section, $key, $link))
            return(FALSE);

        return($this->ini_data[$link][$section][$key]);
    }

    // Return: Boolean
    function write($section, $key = "", $value = "", $link = "")
    {
        // If the parameter $link is empty, get the last Link_Identifier
        if (empty($link))
            $link = sizeof($this->ini_data) - 1;

        // If there is no connection to any INI file, return FALSE
        if (sizeof($this->ini_data) == 0)
            return(FALSE);

        if (empty($section))
            return(FALSE);

        // If the section does not exist, make a new section
        if (!$this->section_exists($section))
            $this->ini_data[$link][$section] = array ();

        // If the key is not empty...
        if (!empty($key))
            $this->ini_data[$link][$section][$key] = $value;

        return(TRUE);
    }

}

?>

