<?php

// ===========================================================================
//  INDEX.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit team member information
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

// ---------------------------------------------------------------------------
//  Includes
require_once ('includes/config.teamcal.php');
require_once ('includes/functions.teamcal.php');

$monthnames = $tc_config['monthnames'];
$today = getdate();
$curryear = $today['year']; // numeric value, 4 digits
$currmonth = $today['mon']; // numeric value

//------------------------------------------------------------------------
// Show HTML header
// Use this file to adjust your meta tags and such
include ("includes/header.html.inc.php");
echo ("<body>\n\r");

//------------------------------------------------------------------------
// Show application header
// This is the file to change in order to put different images at the top
// of the main page.
include ("includes/header.application.inc.php");

//------------------------------------------------------------------------
// Show menu header
// This is the file containing the TeamCal menu
include ("includes/header.menu.inc.php");

//------------------------------------------------------------------------
// Show Month
if (sizeof($_POST) > 0)
{
   $currmonth = intval($_POST['month_id']);
   $curryear = intval($_POST['year_id']);
   $show_id = $_POST['show_id'];
   $groupfilter = $_POST['groupfilter'];

   switch (intval($show_id))
   {
      case 0 :
         $count = 3;
         break;
      case 1 :
         $count = 6;
         break;
      case 2 :
         $count = 12;
         break;
   }

   for ($i = 1; $i <= $count; $i++)
   {
      // echo ("POST " . strval($curryear) . " " . $monthnames[$currmonth]);
      ShowMonth(strval($curryear), $monthnames[$currmonth], $groupfilter);
      if ($currmonth == 12)
      {
         $curryear += 1;
         $currmonth = 1;
      }
      else
      {
         $currmonth += 1;
      }
   }
}
else
{
   for ($i = 1; $i <= 3; $i++)
   {
      // echo ("else " . strval($curryear) . " " . $monthnames[$currmonth]);
      ShowMonth(strval($curryear), $monthnames[$currmonth], "All");
      if ($currmonth == 12)
      {
         $curryear += 1;
         $currmonth = 1;
      }
      else
      {
         $currmonth += 1;
      }
   }
}

//------------------------------------------------------------------------
// Show HTML page footer
include ("includes/footer.html.inc.php");
?>
