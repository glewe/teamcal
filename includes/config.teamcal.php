<?php// ===========================================================================//  CONFIG.TEAMCAL.PHP//  --------------------------------------------------------------------------//  Application: TeamCal//  Purpose:     Set basic configuration for TeamCal//  Author:      George Lewe//  Copyright:   (c) 2004-2009 by Lewe.com (www.lewe.com)//               All rights reserved.//// ===========================================================================// __________________________________________________________________________// Languagerequire("lang/english.teamcal.php");//require("lang/deutsch.teamcal.php");// __________________________________________________________________________// Your Information// *** You can change these settings! ***// The footer copyright note is meant to refer to the entity hosting/providing// the TeamCal application to its users. The powered-by statement however must // always be visible and may not be changed.$tc_config['app_subtitle'] = "Your Subtitle Here";$tc_config['app_footer_cpy'] = "Copyright &copy; 2004-2009 by <a href=\"http://www.lewe.com\" class=\"copyright\">Lewe.com</a>.";// __________________________________________________________________________// Application Information// *** Do not change! ***$tc_config['monthnames'] = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");$tc_config['app_name'] = "TeamCal";$tc_config['app_version'] = "1.6.006";$tc_config['app_author'] = "George Lewe";$tc_config['app_copyright'] = "(c) Copyright 2004-2009 by George Lewe";$tc_config['app_footer_pwd'] = "Powered by " . $tc_config['app_name'] . " " . $tc_config['app_version'] . " &copy; 2004-2009 by <a href=\"mailto:george@lewe.com?subject=TeamCal%201.0\" CLASS=\"copyright\">George Lewe</a>.";// __________________________________________________________________________// Notification e-Mail$tc_config['notification_from'] = "webmaster@{$_SERVER['SERVER_NAME']}";$tc_config['notification_reply'] = "yourmailbox@yourserver.com";// __________________________________________________________________________// Message e-Mail$tc_config['message_from'] = "webmaster@{$_SERVER['SERVER_NAME']}";$tc_config['message_reply'] = "yourmailbox@yourserver.com";// __________________________________________________________________________// Day Type// Describes the type of a given day in a month:// 0 = business day    [0000]// 1 = weekend day     [0001]// 2 = bank holiday    [0010]// 4 = school holiday  [0100]// 8 = not assigned    [1000]$tc_config['monthnames'] = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");$tc_config['business_day']   = 0;$tc_config['weekend_day']    = 1;$tc_config['bank_holiday']   = 2;$tc_config['school_holiday'] = 4;$tc_config['not_assigned']   = 8;// __________________________________________________________________________// Absence Type// Describes the type of absence (or presence) of a team member for a given// day and assigns a symbol to be used in the INI file. Check the language// file for displaying different symbols in the calendar.$tc_config['present']     = '.';$tc_config['duty_trip']   = 'D';$tc_config['day_off']     = 'F';$tc_config['home_office'] = 'H';$tc_config['not_present'] = 'N';$tc_config['sick']        = 'S';$tc_config['training']    = 'T';$tc_config['vacation']    = 'V';$tc_config['blocked']     = 'B';?>