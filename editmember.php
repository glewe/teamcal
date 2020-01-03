<?php

// ===========================================================================
//  EDITMEMBER.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit team member presence/absence information for a month
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
$teamini = new myini;
$error = false;

if (isset ($_REQUEST['Month']))
   $Month = $_REQUEST['Month'];
if (isset ($_REQUEST['Year']))
   $Year = $_REQUEST['Year'];
if (isset ($_REQUEST['Member']))
   $Member = $_REQUEST['Member'];
if (isset ($_POST['Month']))
   $Month = $_POST['Month'];
if (isset ($_POST['Year']))
   $Month = $_POST['Year'];
if (isset ($_POST['Member']))
   $Month = $_POST['Member'];

// First create a timestamp
$mytime = $Month . " 1," . $Year;
$myts = strtotime($mytime);
// Get number of days in month
$nofdays = date("t", $myts);
// Get first weekday of the month
$mydate = getdate($myts);
$weekday1 = $mydate['wday'];
$monthno = sprintf("%02d", intval($mydate['mon']));
if ($weekday1 == "0")
   $weekday1 = "7";
$dayofweek = intval($weekday1);

// Read Month Template and Groups
$inifile = $ini->connect("teamcal.ini");
$template = $ini->read("Template", $Year . $monthno, $inifile);
$groups = $ini->get_keys("Groups", $inifile);
$ini->close($inifile);

// Read Member Data
$teaminifile = $teamini->connect("team.ini");
$memberdata = $teamini->read($Member, $Year . $monthno, $teaminifile);
$thisgroup = $teamini->read($Member, "Group", $teaminifile);
$notify = $teamini->read($Member, "Notify", $teaminifile);
$notifygroup = $teamini->read($Member, "NotifyGroup", $teaminifile);

if (isset ($_POST['btn_apply']))
{
   // First create default template
   $memberdata = "";
   for ($i = 1; $i < intval($nofdays); $i++)
   {
      $memberdata = $memberdata . ".,";
   }
   $memberdata = $memberdata . ".";
   // Now clear the notification bit-map
   $notify = 0;
   // Now overwrite the updated check marks
   foreach ($_POST as $key => $value)
   {
      switch (substr($key, 0, 4))
      {
         case "duty" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['duty_trip'];
            break;
         case "dayo" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['day_off'];
            break;
         case "home" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['home_office'];
            break;
         case "notp" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['not_present'];
            break;
         case "sick" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['sick'];
            break;
         case "trai" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['training'];
            break;
         case "vaca" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['vacation'];
            break;
         case "bloc" :
            $day = substr($key, 4, 2);
            $index = (intval($day) * 2) - 2; // to point between the commas
            $memberdata[$index] = $tc_config['blocked'];
            break;
         default :
            switch ($key)
            {
               case "notify_team" :
                  $notify += 1;
                  break;
               case "notify_groups" :
                  $notify += 2;
                  break;
               case "notify_month" :
                  $notify += 4;
                  break;
               case "notify_member" :
                  $notify += 8;
                  break;
               default :
                  break;
            }
      }
   }
   $notifygroup = $_POST['lbxNotifyGroup'];
   // Write the new memberdata
   $teamini->write($Member, "Notify", $notify, $teaminifile);
   $teamini->write($Member, "NotifyGroup", $_POST['lbxNotifyGroup'], $teaminifile);
   $teamini->write($Member, $Year . $monthno, $memberdata, $teaminifile);
   $teamini->close($teaminifile);
   // Send notification e-Mails
   SendNotification("memberchange", $Member, $thisgroup);
}
else
{
   $teamini->close($teaminifile);
}

include ("includes/header.html.inc.php");
?>
<body>
<form name="monthform" method="POST" action="<?php echo ($_SERVER['PHP_SELF'] . "?Year=" . $Year . "&amp;Month=" . $Month . "&amp;Member=" . $Member); ?>">
<table class="edit">
   <tr>
      <td class="edit-header"><?php echo ($tc_language['member_edit'] . " " . $Member); ?></td>
   </tr>
   <tr>
      <td class="edit-body">
         <?php
         // ---------------------------------------------------------------
         // Month frame: Day of month
         echo "<br>\n\r";
         echo "<table class=\"month\" CELLSPACING=\"0\">\n\r";
         echo "<tr>\n\r";
         echo "<td class=\"month\">" . $tc_language['monthnames'][intval($monthno)] . "&nbsp;" . trim($Year) . "</td>\n\r";
         echo "<td class=\"month-button\">&nbsp;</td>\n\r";
         for ($i = 1; $i <= $nofdays; $i = $i +1)
         {
            switch ($template[$i -1])
            {
               case $tc_config['business_day'] :
                  echo "<td class=\"daynum\">" . $i . "</td>\n\r";
                  break;
               case $tc_config['weekend_day'] :
                  echo "<td class=\"daynum-weekend\">" . $i . "</td>\n\r";
                  break;
               case $tc_config['bank_holiday'] :
                  echo "<td class=\"daynum-bank\">" . $i . "</td>\n\r";
                  break;
               case $tc_config['school_holiday'] :
                  echo "<td class=\"daynum-school\">" . $i . "</td>\n\r";
                  break;
            }
         }
         
         // ---------------------------------------------------------------
         // Month frame: Weekday
         $x = intval($weekday1);
         echo "</tr>\n\r";
         echo "<tr>\n\r";
         echo "<td class=\"title\">&nbsp;</td>\n\r";
         echo "<td class=\"title-button\">&nbsp;</td>\n\r";
         for ($i = 1; $i <= $nofdays; $i = $i +1)
         {
            switch ($template[$i -1])
            {
               case $tc_config['business_day'] :
                  echo "<td class=\"weekday\">" . $weekdays[$x] . "</td>\n\r";
                  break;
               case $tc_config['weekend_day'] :
                  echo "<td class=\"weekday-weekend\">" . $weekdays[$x] . "</td>\n\r";
                  break;
               case $tc_config['bank_holiday'] :
                  echo "<td class=\"weekday-bank\">" . $weekdays[$x] . "</td>\n\r";
                  break;
               case $tc_config['school_holiday'] :
                  echo "<td class=\"weekday-school\">" . $weekdays[$x] . "</td>\n\r";
                  break;
            }
            if ($x <= 6)
               $x += 1;
            else
               $x = 1;
         }
         echo "</tr>\n\r";
         
         $days = explode(',', $memberdata);
         // Days of availability
         print_memberline("duty_trip", "Duty Trip", $days, $template);
         print_memberline("training", "Training / Course", $days, $template);
         print_memberline("home_office", "Home Office", $days, $template);
         // Days of non-availability
         print_memberline("day_off", "Day Off", $days, $template);
         print_memberline("vacation", "Vacation", $days, $template);
         print_memberline("sick", "Sick Leave", $days, $template);
         print_memberline("not_present", "Not present", $days, $template);
         print_memberline("blocked", "Blocked", $days, $template);
         
         echo "</table>\n\r";
         ?>
                
         <table class="dlg-frame">
            <tr>
               <td class="dlg-frame-ul1"></td>
               <td class="dlg-frame-ul2"></td>
               <td class="dlg-frame-title" rowspan="2">&nbsp;Mail&nbsp;Notification&nbsp;</td>
               <td class="dlg-frame-uhu" width="340">&nbsp;</td>
               <td class="dlg-frame-ur1"></td>
               <td class="dlg-frame-ur2"></td>
            </tr>
            <tr>
               <td class="dlg-frame-ul3"></td>
               <td class="dlg-frame-ul4"></td>
               <td class="dlg-frame-uhl">&nbsp;</td>
               <td class="dlg-frame-ur3"></td>
               <td class="dlg-frame-ur4"></td>
            </tr>
            <tr>
               <td class="dlg-frame-l1"></td>
               <td class="dlg-frame-l2"></td>
               <td class="dlg-frame-body" colspan="2">
                  <table>
                     <tr>
                        <td class="dlg-frame-body" colspan="3"><b><?php echo $tc_language['notify_caption']; ?></b></td>
                     </tr>
                     <tr>
                        <td class="dlg-frame-body">
                           <input name="notify_team" type="checkbox" value="notify_team" <?php echo ($notify&1)==1?"CHECKED":"" ?> >
                        </td>
                        <td class="dlg-frame-body"><?php echo $tc_language['notify_team']; ?></td>
                        <td class="dlg-frame-body">&nbsp;</td>
                     </tr>
                     <tr>
                        <td class="dlg-frame-body">
                           <input name="notify_groups" type="checkbox" value="notify_groups" <?php echo ($notify&2)==2?"CHECKED":"" ?> >
                        </td>
                        <td class="dlg-frame-body"><?php echo $tc_language['notify_groups']; ?></td>
                        <td class="dlg-frame-body">&nbsp;</td>
                     </tr>
                     <tr>
                        <td class="dlg-frame-body">
                           <input name="notify_month" type="checkbox" value="notify_month" <?php echo ($notify&4)==4?"CHECKED":"" ?> >
                        </td>
                        <td class="dlg-frame-body"><?php echo $tc_language['notify_month']; ?></td>
                        <td class="dlg-frame-body">&nbsp;</td>
                     </tr>
                     <tr>
                        <td class="dlg-frame-body">
                           <input name="notify_member" type="checkbox" value="notify_member" <?php echo ($notify&8)==8?"CHECKED":"" ?> >
                        </td>
                        <td class="dlg-frame-body"><?php echo $tc_language['notify_member']; ?></td>
                        <td class="dlg-frame-body">&nbsp;<?php echo $tc_language['notify_ofgroup']; ?>&nbsp;
                           <select name="lbxNotifyGroup" class="select">
                              <option class="option" value="All">All</option>
                              <?php
                              foreach ($groups as $groupentry)
                              {
                                 if ($notifygroup == $groupentry)
                                 {
                                    echo ("<option class=\"option\" value=\"" . $notifygroup . "\" selected=\"selected\">" . $notifygroup . "</option>\n\r");
                                 }
                                 else
                                 {
                                    echo ("<option class=\"option\" value=\"" . $groupentry . "\" >" . $groupentry . "</option>\n\r");
                                 }
                              }
                              ?>
                           </select>
                        </td>
                     </tr>
                  </table>
               </td>
               <td class="dlg-frame-r1"></td>
               <td class="dlg-frame-r2"></td>
            </tr>
            <tr>
               <td class="dlg-frame-ll1"></td>
               <td class="dlg-frame-ll2"></td>
               <td class="dlg-frame-lhu" colspan="2"></td>
               <td class="dlg-frame-lr1"></td>
               <td class="dlg-frame-lr2"></td>
            </tr>
            <tr>
               <td class="dlg-frame-ll3"></td>
               <td class="dlg-frame-ll4"></td>
               <td class="dlg-frame-lhl" colspan="2"></td>
               <td class="dlg-frame-lr3"></td>
               <td class="dlg-frame-lr4"></td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td class="bottom-menu">
         <input name="btn_apply" type="submit" class="button" value="<?php echo $tc_language['btn_apply']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
         <input name="btn_done"  type="button" class="button" onclick="javascript:closeme();" value="<?php echo $tc_language['btn_done']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
      </td>
   </tr>
</table>
</form>

<?php
if ($error)
   echo ("<script type=\"text/javascript\">alert(\"" . $errmsg . "\")</script>");
include ('includes/footer.html.inc.php');
?>
