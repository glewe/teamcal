<?php

// ===========================================================================
//  MESSAGE.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Send e-Mail message to team or group
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

require_once ('includes/class.ini.php');
include_once ('includes/config.teamcal.php');

$error = false;
$msgsent = false;

// Read Member and Groups
$ini = new myini;
$inifile = $ini->connect("team.ini");
$members = $ini->get_sections($inifile);

$ini2 = new myini;
$ini2file = $ini2->connect("teamcal.ini");
$groups = $ini2->get_keys("Groups", $ini2file);
$ini2->close($ini2file);

if (isset ($_POST['btn_send']))
{
   $to = "";
   switch ($_POST['sendto'])
   {
      case "all" :
         //echo $sendto . ", Mail to all<br>";
         if ($members)
         {
            foreach ($members as $member)
            {
               $membermail = $ini->read($member, "Email", $inifile);
               $to .= $membermail . ",";
            }
            //echo $to . "<br>";
            //echo $subject . "<br>";
            //echo $msg . "<br>";
            mail($to, $_POST['subject'], $_POST['msg'], "From: " . $tc_config['message_from'] . "\r\n" . "Reply-To: " . $tc_config['message_reply'] . "\r\n");
            $msgsent = true;
         }
         break;
      case "group" :
         //echo $sendto . ", Mail to " . $groupto . "<br>";
         if ($members)
         {
            foreach ($members as $member)
            {
               $membergroup = $ini->read($member, "Group", $inifile);
               if ($membergroup == $_POST['groupto'])
               {
                  $membermail = $ini->read($member, "Email", $inifile);
                  $to .= $membermail . ",";
               }
            }
            //echo $to . "<br>";
            //echo $subject . "<br>";
            //echo $msg . "<br>";
            mail($to, $_POST['subject'], $_POST['msg'], "From: " . $tc_config['message_from'] . "\r\n" . "Reply-To: " . $tc_config['message_reply'] . "\r\n");
            $msgsent = true;
         }
         break;
      case "user" :
         //echo $sendto . ", Mail to " . $userto . "<br>";
         if ($members)
         {
            foreach ($members as $member)
            {
               if ($member == $_POST['userto'])
               {
                  $membermail = $ini->read($member, "Email", $inifile);
                  $to .= $membermail . ",";
               }
            }
            //echo $to . "<br>";
            //echo $subject . "<br>";
            //echo $msg . "<br>";
            mail($to, $_POST['subject'], $_POST['msg'], "From: " . $tc_config['message_from'] . "\r\n" . "Reply-To: " . $tc_config['message_reply'] . "\r\n");
            $msgsent = true;
         }
         break;
   }
   $ini->close($inifile);
}
else
{
   $ini->close($inifile);
}

include ("includes/header.html.inc.php");
?>
<body>
<table class="edit">
   <tr>
      <td class="edit-header"><?php echo $tc_language['teamcal_message']; ?></td>
   </tr>
   <tr>
      <td class="edit-body">
         <form  name="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <table class="dlg-frame">
               <tr>
                  <td class="dlg-body" width="80"><br>
                     <strong><?php echo $tc_language['message_sendto_caption']; ?></strong></td>
                  <td class="dlg-body" valign="middle">
                     <table class="dlg-frame">
                        <tr>
                           <td class="dlg-body">
                              <input type="radio" name="sendto" class="input" value="all" checked>
                              <?php echo $tc_language['message_sendto_all']; ?>&nbsp;
                           </td>
                           <td class="dlg-body">&nbsp;</td>
                        </tr>
                        <tr>
                           <td class="dlg-body">
                              <input type="radio" name="sendto" class="input" value="group">
                              <?php echo $tc_language['message_sendto_group']; ?>&nbsp;
                              </td>
                           <td class="dlg-body">
                              <select name="groupto" class="select">
                                 <?php
                                 sort($groups);
                                 reset($groups);
                                 foreach ($groups as $groupentry)
                                 {
                                    echo ("<option class=\"option\" value=\"" . $groupentry . "\" >" . $groupentry . "</option>\n\r");
                                 }
                                 ?>
                              </select>
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body">
                              <input type="radio" name="sendto" class="input" value="user">
                              <?php echo $tc_language['message_sendto_user']; ?>&nbsp;
                           </td>
                           <td class="dlg-body">
                              <select name="userto" class="select">
                                 <?php
                                 sort($members);
                                 reset($members);
                                 foreach ($members as $memberentry)
                                 {
                                    echo ("<option class=\"option\" value=\"" . $memberentry . "\" >" . $memberentry . "</option>\n\r");
                                 }
                                 ?>
                              </select>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="dlg-body" width="80"><strong><?php echo $tc_language['message_subject_caption']; ?></strong></td>
                  <td class="dlg-body">
                     <input name="subject" size="50" type="text" class="text" value="<?php echo $tc_language['message_subject']; ?>">
                     <br>
                  </td>
               </tr>
               <tr>
                  <td class="dlg-body"><strong><?php echo $tc_language['message_msg_caption']; ?></strong></td>
                  <td class="dlg-body"><textarea name="msg" class="text" rows="10" cols="50"><?php echo $tc_language['message_msg'] . "\r\n"; ?></textarea><br>
                  </td>
               </tr>
               <tr>
                  <td class="dlg-body">&nbsp;</td>
                  <td class="dlg-body">
                     <input name="btn_send" type="submit" class="button" value="<?php echo $tc_language['btn_send']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
                  </td>
               </tr>
            </table>
         </form>
      </td>
   </tr>
   <tr>
      <td class="bottom-menu">
         <?php if ($msgsent) echo $tc_language['message_msgsent'] . "&nbsp;"; ?>
         <form  name="form2" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <input name="btn_done" type="button" class="button" onclick="javascript:window.close();" value="<?php echo $tc_language['btn_done']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
         </form>
      </td>
   </tr>
</table>
<br>
<?php
if ($error)
   echo ("<script type=\"text/javascript\">alert(\"" . $errmsg . "\")</script>");
include_once ('includes/footer.html.inc.php');
?>
