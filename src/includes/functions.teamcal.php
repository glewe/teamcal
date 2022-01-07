<?php

// ===========================================================================
//  FUNCTIONS.TEAMCAL.PHP
//  --------------------------------------------------------------------------
//  Application: TeamCal
//  Purpose:     Basic functions for TeamCal
//  Author:      George Lewe
//  Copyright:   (c) 2004-2009 by Lewe.com (www.lewe.com)
//               All rights reserved.
//
// ===========================================================================

// ---------------------------------------------------------------------------
//  Includes
//
require_once ("class.ini.php");

// ---------------------------------------------------------------------------
//  Create Month Template
//
function create_month_template($yr, $mt)
{
   include ("config.teamcal.php");

   // Create a timestamp for the given year and month (using day 1 of the 
   // month) and use it to get some relevant information using date() and
   // getdate()
   $mytime = $mt . " 1," . $yr;
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
   $template = "";
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
   // Return the template
   return $template;
}

// ---------------------------------------------------------------------------
//  ShowMonth
//
function ShowMonth($year, $month, $groupfilter)
{

   include ("config.teamcal.php");

   $weekdays = $tc_config['weekdays'];

   // Create a timestamp for the given year and month (using day 1 of the 
   // month) and use it to get some relevant information using date() and
   // getdate()
   $mytime = $month . " 1," . $year;
   $myts = strtotime($mytime);
   // Get number of days in month
   $nofdays = date("t", $myts);
   // Get first weekday of the month
   $mydate = getdate($myts);
   $weekday1 = $mydate['wday'];
   if ($weekday1 == "0")
      $weekday1 = "7";
   $monthno = sprintf("%02d", intval($mydate['mon']));
   // Set the friendly name of the month
   // $monthname = $month . " " . $year;
   $monthname = $tc_language['monthnames'][intval($monthno)] . " " . $year;
   // Get the holiday template for this month
   $ini = new myini;
   $inifile = $ini->connect("teamcal.ini");
   $template = $ini->read("Template", $year . $monthno, $inifile);
   if (!$template)
   {
      // Seems there is no template yet. Let's create a default one
      $template = create_month_template($year, $month);
      $ini->write("Template", $year . $monthno, $template, $inifile);
   }

   // echo $monthname . " " . $nofdays . " " . $template . " " . $weekday1 . " " . $mydate['weekday'] . "<br>";
   if ($monthname && $nofdays && $template && $weekday1)
   {
      // ---------------------------------------------------------------
      // Print the frame of the month
      echo "<br>\n\r";
      echo "<table class=\"month\" cellspacing=\"0\">\n\r";
      echo "<tr>\n\r";
      echo "<td class=\"month\">" . trim($monthname) . "</td>\n\r";
      echo "<td class=\"month-button\"><a href=\"javascript:openPopup('editmonth.php?Year=" . $year . "&amp;Month=" . $month . "','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=920,height=400');\"><img src=\"img/btn_edit_month.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" title=\"Edit holidays for this month...\"></a></td>\n\r";
      for ($i = 1; $i <= $nofdays; $i = $i +1)
      {
         switch ($template[$i -1])
         {
            case $tc_config['weekend_day'] :
               echo "<td class=\"daynum-weekend\">" . $i . "</td>\n\r";
               break;
            case $tc_config['bank_holiday'] :
               echo "<td class=\"daynum-bank\">" . $i . "</td>\n\r";
               break;
            case $tc_config['school_holiday'] :
               echo "<td class=\"daynum-school\">" . $i . "</td>\n\r";
               break;
            default : // must be a business day
               echo "<td class=\"daynum\">" . $i . "</td>\n\r";
               break;
         }
      }

      $x = intval($weekday1);
      echo "</tr>\n\r";
      echo "<tr>\n\r";
      echo "<td class=\"title\">Name</td>\n\r";
      echo "<td class=\"title-button\">&nbsp;</td>\n\r";
      for ($i = 1; $i <= $nofdays; $i = $i +1)
      {
         switch ($template[$i -1])
         {
            case $tc_config['weekend_day'] :
               echo "<td class=\"weekday-weekend\">" . $weekdays[$x] . "</td>\n\r";
               break;
            case $tc_config['bank_holiday'] :
               echo "<td class=\"weekday-bank\">" . $weekdays[$x] . "</td>\n\r";
               break;
            case $tc_config['school_holiday'] :
               echo "<td class=\"weekday-school\">" . $weekdays[$x] . "</td>\n\r";
               break;
            default : // must be a business day
               echo "<td class=\"weekday\">" . $weekdays[$x] . "</td>\n\r";
               break;
         }
         if ($x <= 6)
            $x += 1;
         else
            $x = 1;
      }
      echo "</tr>\n\r";
      // ---------------------------------------------------------------
      // Now print a line for each team member
      $teamini = new myini;
      $teaminifile = $teamini->connect("team.ini");
      $members = $teamini->get_sections($teaminifile);
      if ($members)
      {
         sort($members);
         reset($members);
         foreach ($members as $member)
         {
            if (trim($member) != '')
            {
               $membergroup = $teamini->read($member, "Group", $teaminifile);
               if ($membergroup == $groupfilter || $groupfilter == "All")
               {
                  echo "<tr>\n\r";
                  echo "<td class=\"name\">" . $member . "</td>\n\r";
                  echo "<td class=\"name-button\"><a href=\"javascript:openPopup('editmember.php?Year=" . $year . "&amp;Month=" . $month . "&amp;Member=" . $member . "','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=940,height=600');\"><img src=\"img/btn_edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" title=\"Edit absences for this person for this month...\"></a></td>\n\r";
                  $memberdata = $teamini->read($member, $year . $monthno, $teaminifile);
                  if (!$memberdata)
                  {
                     // Member exists but no data for this month for this member yet.
                     // Use default (always present) and write to team.ini
                     for ($i = 1; $i < intval($nofdays); $i++)
                     {
                        $memberdata = $memberdata . $tc_config['present'] . ",";
                     }
                     $memberdata = $memberdata . $tc_config['present'];
                     $teamini->write($member, $year . $monthno, $memberdata, $teaminifile);
                  }
                  $days = explode(',', $memberdata);
                  if ($days)
                  {
                     for (reset($days); !is_null($day = key($days)); next($days))
                     {
                        switch (trim($days[$day]))
                        {
                           case $tc_config['present'] :
                              switch ($template[$day])
                              {
                                 case $tc_config['weekend_day'] :
                                    echo "<td class=\"day-weekend\">&nbsp;</td>\n\r";
                                    break;
                                 case $tc_config['bank_holiday'] :
                                    echo "<td class=\"day-bank\">&nbsp;</td>\n\r";
                                    break;
                                 case $tc_config['school_holiday'] :
                                    echo "<td class=\"day-school\">&nbsp;</td>\n\r";
                                    break;
                                 default : // must be a business day
                                    echo "<td class=\"day\">&nbsp;</td>\n\r";
                                    break;
                              }
                              break;
                           case $tc_config['duty_trip'] :
                              echo "<td class=\"day-duty\">" . $tc_language['duty_trip'] . "</td>\n\r";
                              break;
                           case $tc_config['day_off'] :
                              echo "<td class=\"day-off\">" . $tc_language['day_off'] . "</td>\n\r";
                              break;
                           case $tc_config['home_office'] :
                              echo "<td class=\"day-home\">" . $tc_language['home_office'] . "</td>\n\r";
                              break;
                           case $tc_config['not_present'] :
                              echo "<td class=\"day-not\">" . $tc_language['not_present'] . "</td>\n\r";
                              break;
                           case $tc_config['sick'] :
                              echo "<td class=\"day-sick\">" . $tc_language['sick'] . "</td>\n\r";
                              break;
                           case $tc_config['training'] :
                              echo "<td class=\"day-train\">" . $tc_language['training'] . "</td>\n\r";
                              break;
                           case $tc_config['vacation'] :
                              echo "<td class=\"day-vac\">" . $tc_language['vacation'] . "</td>\n\r";
                              break;
                           case $tc_config['blocked'] :
                              echo "<td class=\"day-block\">" . $tc_language['blocked'] . "</td>\n\r";
                              break;
                           default : // make it a business day
                              echo "<td class=\"day\">&nbsp;</td>\n\r";
                              break;
                        }
                     }
                  }
                  echo "</tr>\n\r";
               } // end filtergroup
            }
         }
      } // End if ($section)
   } // End if ($monthname && $nofdays && $template && $weekday1)
   echo "</table>\n\r";
   $teamini->close($teaminifile);
   $ini->close($inifile);
} // End function ShowMonth

// ---------------------------------------------------------------------------
//  Print Memberline
//
function print_memberline($config, $title, $dayrow, $tpl)
{
   include ("config.teamcal.php");
   switch ($config)
   {
      case "day_off" :
         $compare = $tc_config['day_off'];
         $checkid = "dayo";
         $csscolor = "day-off";
         $title = $tc_language['day_day_off'];
         break;
      case "duty_trip" :
         $compare = $tc_config['duty_trip'];
         $checkid = "duty";
         $csscolor = "day-duty";
         $title = $tc_language['day_duty_trip'];
         break;
      case "home_office" :
         $compare = $tc_config['home_office'];
         $checkid = "home";
         $csscolor = "day-home";
         $title = $tc_language['day_home_office'];
         break;
      case "not_present" :
         $compare = $tc_config['not_present'];
         $checkid = "notp";
         $csscolor = "day-not";
         $title = $tc_language['day_not_present'];
         break;
      case "sick" :
         $compare = $tc_config['sick'];
         $checkid = "sick";
         $csscolor = "day-sick";
         $title = $tc_language['day_sick_leave'];
         break;
      case "training" :
         $compare = $tc_config['training'];
         $checkid = "trai";
         $csscolor = "day-train";
         $title = $tc_language['day_training'];
         break;
      case "vacation" :
         $compare = $tc_config['vacation'];
         $checkid = "vaca";
         $csscolor = "day-vac";
         $title = $tc_language['day_vacation'];
         break;
      case "blocked" :
         $compare = $tc_config['blocked'];
         $checkid = "bloc";
         $csscolor = "day-block";
         $title = $tc_language['day_blocked'];
         break;
   }

   echo "<tr>\n\r";
   echo "<td class=\"name\">" . $title . "</td>\n\r";
   echo "<td class=\"name-button\">&nbsp;</td>\n\r";
   $count = 0;
   for (reset($dayrow); !is_null($idx = key($dayrow)); next($dayrow))
   {
      if (trim($dayrow[$idx]) == $compare)
      {
         echo "<td class=\"" . $csscolor . "\">\n\r";
         echo ("<input name=\"" . $checkid . strval($count +1) . "\" type=\"checkbox\" id=\"" . $checkid . strval($count +1) . "\" value=\"" . $checkid . strval($count +1) . "\" CHECKED>\n\r");
      }
      else
      {
         switch ($tpl[$idx])
         {
            case $tc_config['weekend_day'] :
               echo "<td class=\"day-weekend\">\n\r";
               echo ("<input name=\"" . $checkid . strval($count +1) . "\" type=\"checkbox\" id=\"" . $checkid . strval($count +1) . "\" value=\"" . $checkid . strval($count +1) . "\">\n\r");
               break;
            case $tc_config['bank_holiday'] :
               echo "<td class=\"day-bank\">\n\r";
               echo ("<input name=\"" . $checkid . strval($count +1) . "\" type=\"checkbox\" id=\"" . $checkid . strval($count +1) . "\" value=\"" . $checkid . strval($count +1) . "\">\n\r");
               break;
            case $tc_config['school_holiday'] :
               echo "<td class=\"day-school\">\n\r";
               echo ("<input name=\"" . $checkid . strval($count +1) . "\" type=\"checkbox\" id=\"" . $checkid . strval($count +1) . "\" value=\"" . $checkid . strval($count +1) . "\">\n\r");
               break;
            default : // must be a business day
               echo ("<td class=\"day\">\n\r");
               echo ("<input name=\"" . $checkid . strval($count +1) . "\" type=\"checkbox\" id=\"" . $checkid . strval($count +1) . "\" value=\"" . $checkid . strval($count +1) . "\">\n\r");
               break;
         }
      }
      $count += 1;
      echo ("</td>\n\r");
   }
   echo "</tr>\n\r";
}

// ---------------------------------------------------------------------------
//  Send Notification
//
function SendNotification($type, $subject, $groupchanged)
{
   include ("config.teamcal.php");
   $teamini = new myini;
   $teaminifile = $teamini->connect("team.ini");
   $members = $teamini->get_sections($teaminifile);
   $message = '';
   if ($members)
   {
      foreach ($members as $member)
      {
         if (trim($member) != '')
         {
            $notify = $teamini->read($member, "Notify", $teaminifile);
            $notifygroup = $teamini->read($member, "NotifyGroup", $teaminifile);
            $email = $teamini->read($member, "Email", $teaminifile);
            $sendmail = false;
            switch (strtolower($type))
            {
               case "teamchange" :
                  if (($notify & 1) == 1)
                  {
                     $message = $tc_language['notification_greeting'];
                     $message .= $tc_language['notification_team_msg'];
                     $sendmail = true;
                  }
                  break;
               case "groupchange" :
                  if (($notify & 2) == 2)
                  {
                     $message = $tc_language['notification_greeting'];
                     $message .= $tc_language['notification_group_msg'];
                     $sendmail = true;
                  }
                  break;
               case "monthchange" :
                  if (($notify & 4) == 4)
                  {
                     $message = $tc_language['notification_greeting'];
                     $message .= $tc_language['notification_month_msg'];
                     $message .= $subject . ".\r\n\r\n";
                     $sendmail = true;
                  }
                  break;
               case "memberchange" :
                  if (($notify & 8) == 8 && $notifygroup == $groupchanged)
                  {
                     $message = $tc_language['notification_greeting'];
                     $message .= $tc_language['notification_member_msg'];
                     $message .= $subject . ".\r\n\r\n";
                     $sendmail = true;
                  }
                  break;
               default :
                  break;
            }
         }
         if ($sendmail)
         {
            $message .= $tc_language['notification_signature'];
            //echo "email: " . trim($email) . "<br>\r\n";
            //echo "subject: " . $tc_language['notification_subject'] . "<br>\r\n";
            //echo "message: " . $message . "<br>\r\n";
            //echo "from: " . $tc_config['notification_from'] . "<br>\r\n";
            //echo "reply: " . $tc_config['notification_reply'] . "<br>\r\n";
            mail(trim($email), $tc_language['notification_subject'], $message, "From: " . $tc_config['notification_from'] . "\r\n" .
            "Reply-To: " . $tc_config['notification_reply'] . "\r\n");
         }
      }
   }
   $teamini->close($teaminifile);
}
?>
