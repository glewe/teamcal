<?php

// ===========================================================================
//  EDITMONTH.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit month day template
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

require_once ("includes/class.ini.php");
include_once ("includes/config.teamcal.php");
include_once ("includes/functions.teamcal.php");

$weekdays = $tc_config['weekdays'];
$ini = new myini;
$error = false;

if (isset ($_REQUEST['Month']))
   $Month = $_REQUEST['Month'];
if (isset ($_REQUEST['Year']))
   $Year = $_REQUEST['Year'];
if (isset ($_POST['Month']))
   $Month = $_POST['Month'];
if (isset ($_POST['Year']))
   $Month = $_POST['Year'];

// First create a timestamp
$mytime = $Month . " 1," . $Year;
$myts = strtotime($mytime);
// Get number of days in month
$nofdays = date("t", $myts);
// Get first weekday of the month
$mydate = getdate($myts);
$monthno = sprintf("%02d", intval($mydate['mon']));
$weekday1 = $mydate['wday'];
if ($weekday1 == "0")
   $weekday1 = "7";
$dayofweek = intval($weekday1);

$inifile = $ini->connect("teamcal.ini");
$template = $ini->read("Template", $Year . $monthno, $inifile);

if (!$template)
{
   // Seems there is no template yet. Let's create a default one
   $template = create_month_template($year, $month);
   $ini->write("Template", $year . $monthno, $template, $inifile);
}
if (isset ($_POST['btn_update']))
{
   // First clear out the template
   $template = "";
   $dayofweek = intval($weekday1);
   for ($i = 1; $i <= $nofdays; $i++)
   {
      switch ($dayofweek)
      {
         case 1 : // Monday
            $template = $template . $tc_config['business_day'];
            break;
         case 2 : // Tuesday
            $template = $template . $tc_config['business_day'];
            break;
         case 3 : // Wednesday
            $template = $template . $tc_config['business_day'];
            break;
         case 4 : // Thursday
            $template = $template . $tc_config['business_day'];
            break;
         case 5 : // Friday
            $template = $template . $tc_config['business_day'];
            break;
         case 6 : // Saturday
            $template = $template . $tc_config['weekend_day'];
            break;
         case 7 : // Sunday
            $template = $template . $tc_config['weekend_day'];
            break;
      }
      $dayofweek += 1;
      if ($dayofweek == 8)
      {
         $dayofweek = 1;
      }
   }

   // Now add the check marks
   foreach ($_POST as $key => $value)
   {
      switch (substr($key, 0, 4))
      {
         case "bank" :
            $day = substr($key, 7, 2);
            $index = intval($day) - 1;
            $template[$index] = $tc_config['bank_holiday'];
            break;
         case "scho" :
            $day = substr($key, 7, 2);
            $index = intval($day) - 1;
            if ($template[$index] != $tc_config['bank_holiday'])
               $template[$index] = $tc_config['school_holiday'];
            break;
      }
   }
   // Write the new template
   $ini->write("Template", $Year . $monthno, $template, $inifile);
   // Send notification e-Mails
   $subject = $tc_language['monthnames'][intval($monthno)] . " " . trim($Year);
   SendNotification("monthchange", $subject, "");
}
$ini->close($inifile);

include ("includes/header.html.inc.php");
?>
<body>
   <form  name="monthform" method="POST" action="<?php echo ($_SERVER['PHP_SELF'] . "?Year=" . $Year . "&amp;Month=" . $Month); ?>">
   <table class="edit">
      <tr>
         <td class="edit-header"><?php echo ($tc_language['month_edit'] . " " . $tc_language['monthnames'][intval($monthno)] . " " . $Year); ?></td>
      </tr>
      <tr>
         <td class="edit-body">
             <?php
            // ---------------------------------------------------------------
            // Print the frame of the month
            echo "<br>\n\r";
            echo "<table class=\"month\" cellspacing=\"0\">\n\r";
            echo "<tr>\n\r";
            echo "<td class=\"month\">" . $tc_language['monthnames'][intval($monthno)] . " " . trim($Year) . "</td>\n\r";
            echo "<td class=\"month-button\">&nbsp;</td>\n\r";
            for ($i = 1; $i <= $nofdays; $i = $i +1)
            {
               switch ($template[$i -1])
               {
                  case 0 :
                     echo "<td class=\"daynum\">" . $i . "</td>\n\r";
                     break;
                  case 1 :
                     echo "<td class=\"daynum-weekend\">" . $i . "</td>\n\r";
                     break;
                  case 2 :
                     echo "<td class=\"daynum-bank\">" . $i . "</td>\n\r";
                     break;
                  case 4 :
                     echo "<td class=\"daynum-school\">" . $i . "</td>\n\r";
                     break;
               }
            }
            
            $x = intval($weekday1);
            echo "</tr>\n\r";
            echo "<tr>\n\r";
            echo "<td class=\"title\">&nbsp;</td>\n\r";
            echo "<td class=\"title-button\">&nbsp;</td>\n\r";
            for ($i = 1; $i <= $nofdays; $i = $i +1)
            {
               switch ($template[$i -1])
               {
                  case 0 :
                     echo "<td class=\"weekday\">" . $weekdays[$x] . "</td>\n\r";
                     break;
                  case 1 :
                     echo "<td class=\"weekday-weekend\">" . $weekdays[$x] . "</td>\n\r";
                     break;
                  case 2 :
                     echo "<td class=\"weekday-bank\">" . $weekdays[$x] . "</td>\n\r";
                     break;
                  case 4 :
                     echo "<td class=\"weekday-school\">" . $weekdays[$x] . "</td>\n\r";
                     break;
               }
               if ($x <= 6)
                  $x += 1;
               else
                  $x = 1;
            }
            echo "</tr>\n\r";
            
            // ---------------------------------------------------------------
            // This line is for Bank Holiday
            echo "<tr>\n\r";
            echo "<td class=\"name\">" . $tc_language['day_bank_holiday'] . "</td>\n\r";
            echo "<td class=\"name-button\">&nbsp;</td>\n\r";
            for ($count = 0; $count < strlen($template); $count++)
            {
               $character = substr($template, $count, 1);
               switch (trim($character))
               {
                  case $tc_config['business_day'] :
                     echo ("<td class=\"day\">\n\r");
                     echo ("<input name=\"bankday" . strval($count +1) . "\" type=\"checkbox\" ID=\"bankday" . strval($count +1) . "\" value=\"bankday" . strval($count +1) . "\">\n\r");
                     break;
                  case $tc_config['weekend_day'] :
                     echo ("<td class=\"day-weekend\">\n\r");
                     echo ("<input name=\"bankday" . strval($count +1) . "\" type=\"checkbox\" ID=\"bankday" . strval($count +1) . "\" value=\"bankday" . strval($count +1) . "\">\n\r");
                     break;
                  case $tc_config['bank_holiday'] :
                     echo ("<td class=\"day-bank\">\n\r");
                     echo ("<input name=\"bankday" . strval($count +1) . "\" type=\"checkbox\" ID=\"bankday" . strval($count +1) . "\" value=\"bankday" . strval($count +1) . "\" CHECKED>\n\r");
                     break;
                  case $tc_config['school_holiday'] :
                     echo ("<td class=\"day-school\">\n\r");
                     echo ("<input name=\"bankday" . strval($count +1) . "\" type=\"checkbox\" ID=\"bankday" . strval($count +1) . "\" value=\"bankday" . strval($count +1) . "\">\n\r");
                     break;
                  default :
                     echo ("<td class=\"day\">\n\r");
                     echo ("<input name=\"bankday" . strval($count +1) . "\" type=\"checkbox\" ID=\"bankday" . strval($count +1) . "\" value=\"bankday" . strval($count +1) . "\">\n\r");
                     break;
               }
               echo ("</td>\n\r");
            }
            echo "</tr>\n\r";
            
            // ---------------------------------------------------------------
            // This line is for School Holiday
            echo "<tr>\n\r";
            echo "<td class=\"name\">" . $tc_language['day_school_holiday'] . "</td>\n\r";
            echo "<td class=\"name-button\">&nbsp;</td>\n\r";
            for ($count = 0; $count < strlen($template); $count++)
            {
               $character = substr($template, $count, 1);
               switch (trim($character))
               {
                  case $tc_config['business_day'] :
                     echo ("<td class=\"day\">\n\r");
                     echo ("<input name=\"schoday" . strval($count +1) . "\" type=\"checkbox\" ID=\"schoday" . strval($count +1) . "\" value=\"schoday" . strval($count +1) . "\">\n\r");
                     break;
                  case $tc_config['weekend_day'] :
                     echo ("<td class=\"day-weekend\">\n\r");
                     echo ("<input name=\"schoday" . strval($count +1) . "\" type=\"checkbox\" ID=\"schoday" . strval($count +1) . "\" value=\"schoday" . strval($count +1) . "\">\n\r");
                     break;
                  case $tc_config['bank_holiday'] :
                     echo ("<td class=\"day-bank\">\n\r");
                     echo ("<input name=\"schoday" . strval($count +1) . "\" type=\"checkbox\" ID=\"schoday" . strval($count +1) . "\" value=\"schoday" . strval($count +1) . "\">\n\r");
                     break;
                  case $tc_config['school_holiday'] :
                     echo ("<td class=\"day-school\">\n\r");
                     echo ("<input name=\"schoday" . strval($count +1) . "\" type=\"checkbox\" ID=\"schoday" . strval($count +1) . "\" value=\"schoday" . strval($count +1) . "\" CHECKED>\n\r");
                     break;
                  default :
                     echo ("<td class=\"day\">\n\r");
                     echo ("<input name=\"schoday" . strval($count +1) . "\" type=\"checkbox\" ID=\"schoday" . strval($count +1) . "\" value=\"schoday" . strval($count +1) . "\">\n\r");
                     break;
               }
               echo ("</td>\n\r");
            }
            echo "</tr>\n\r";
            echo "</table>\n\r";
            ?>
            <br>
         </td>
      </tr>
      <tr>
         <td class="bottom-menu">
            <input name="btn_update" type="submit" class="button" value="<?php echo $tc_language['btn_apply']; ?>" onMouseOver="this.className='button-over';" onMouseOut="this.className='button';">
            <input name="btn_done"   type="button" class="button" onclick="javascript:closeme();" value="<?php echo $tc_language['btn_done']; ?>" onMouseOver="this.className='button-over';" onMouseOut="this.className='button';">
         </td>
      </tr>
   </table>
   </form>
<?php
if ($error)
   echo ("<script type=\"text/javascript\">alert(\"" . $errmsg . "\")</script>");
include ('includes/footer.html.inc.php');
?>

