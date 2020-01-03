<?php

// ===========================================================================
//  EDITTEAM.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit team member information
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

require_once ('includes/class.ini.php');
require_once ('includes/config.teamcal.php');
include_once ("includes/functions.teamcal.php");

$ini = new myini;
$ini2 = new myini;
$error = false;

// Read Groups
$inifile2 = $ini2->connect("teamcal.ini");
$groups = $ini2->get_keys("Groups", $inifile2);
$ini2->close($inifile2);

$inifile = $ini->connect("team.ini");
if (isset ($_POST['btn_update']))
{
   $new_name = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['name']);
   if ($new_name == $_POST['namehidden'])
   {
      // Overwrite values with the ones of the form
      $ini->write($_POST['namehidden'], "Position", $_POST['pos'], $inifile);
      $ini->write($_POST['namehidden'], "Group", $_POST['group'], $inifile);
      $ini->write($_POST['namehidden'], "Email", $_POST['email'], $inifile);
      $ini->write($_POST['namehidden'], "Phone", $_POST['phone'], $inifile);
   }
   else
   {
      // Read old keys
      $keys = $ini->get_keys($_POST['namehidden'], $inifile);
      foreach ($keys as $key)
      {
         // Read old value
         $value = $ini->read($_POST['namehidden'], $key, $inifile);
         // Write in new section
         $ini->write($new_name, $key, $value, $inifile);
      }
      // Overwrite values with the ones of the form
      $ini->write($new_name, "Position", $_POST['pos'], $inifile);
      $ini->write($new_name, "Group", $_POST['group'], $inifile);
      $ini->write($new_name, "Email", $_POST['email'], $inifile);
      $ini->write($new_name, "Phone", $_POST['phone'], $inifile);
      // Drop old section
      $ini->drop_section($_POST['namehidden'], $inifile);
   }
   // Send notification e-Mails
   SendNotification("teamchange", "", "");
}
elseif (isset ($_POST['btn_delete']))
{
   $ini->drop_section($_POST['namehidden'], $inifile);
   // Send notification e-Mails
   SendNotification("teamchange", "", "");
}
elseif (isset ($_POST['btn_add']))
{
   if (trim($_POST['nameadd']) != '')
   {
      $new_name = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['nameadd']);
      $ini->write($new_name, "Position", $_POST['posadd'], $inifile);
      $ini->write($new_name, "Group", $_POST['groupadd'], $inifile);
      $ini->write($new_name, "Email", $_POST['emailadd'], $inifile);
      $ini->write($new_name, "Phone", $_POST['phoneadd'], $inifile);
      // Send notification e-Mails
      SendNotification("teamchange", "", "");
   }
   else
   {
      $error = true;
      $errmsg = "WARNING\\n\\rYou have to add at least a name in order to add a new group member.";
   }
}

include ("includes/header.html.inc.php");
?>
<body>
<table class="edit">
   <tr>
      <td class="edit-header"><?php echo $tc_language['edit_team']; ?></td>
   </tr>
   <tr>
      <td class="edit-body">
         <table>
            <tr>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_name']; ?></td>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_position']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_group']; ?></td>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_email']; ?></td>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_phone']; ?></td>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_action']; ?></td>
            </tr>
            <tr>
               <td colspan="6">
                  <hr size="1">
               </td>
            </tr>
            <tr>
               <td colspan="6">
                  <?php
                  $sections = $ini->get_sections($inifile);
                  if ($sections)
                  {
                     sort($sections);
                     reset($sections);
                     $i = 1;
                     foreach ($sections as $sect)
                     {
                        if (trim($sect) != '')
                        {
                           echo ("<form name=\"form" . $i . "\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n\r");
                           echo ("   <table>\n\r");
                           echo ("      <tr>\n\r");
                           echo ("         <td>\n\r");
                           echo ("            <input name=\"namehidden\" type=\"hidden\" class=\"text\" value=\"" . trim($sect) . "\">\n\r");
                           echo ("            <input name=\"name\" type=\"text\" class=\"text\" value=\"" . trim($sect) . "\">\n\r");
                           echo ("         </td>\n\r");
                           $pos = $ini->read(trim($sect), "Position", $inifile);
                           echo ("         <td>\n\r");
                           echo ("            <input name=\"pos\" type=\"text\" class=\"text\" value=\"" . $pos . "\">\n\r");
                           echo ("         </td>\n\r");
                           $group = $ini->read(trim($sect), "Group", $inifile);
                           echo ("         <td>\n\r");
                           echo ("            <select name=\"group\" class=\"select\">\n\r");
                           $groupfound = false;
                           foreach ($groups as $groupentry)
                           {
                              if ($group == $groupentry)
                              {
                                 echo ("               <option class=\"option\" value=\"" . $group . "\" selected=\"selected\">" . $group . "</option>\n\r");
                                 $groupfound = true;
                              }
                              else
                              {
                                 echo ("               <option class=\"option\" value=\"" . $groupentry . "\" >" . $groupentry . "</option>\n\r");
                              }
                           }
                           if (!$groupfound)
                           {
                              echo ("                   <option class=\"option\" value=\"" . $tc_language['entry_none'] . "\" selected=\"selected\">" . $tc_language['entry_none'] . "</option>\n\r");
                           }
                           echo ("            </select>\n\r");
                           echo ("         </td>\n\r");
                           $email = $ini->read(trim($sect), "Email", $inifile);
                           echo ("         <td>\n\r");
                           echo ("            <input name=\"email\" type=\"text\" class=\"text\" size=\"40\" value=\"" . $email . "\">\n\r");
                           echo ("         </td>\n\r");
                           $phone = $ini->read(trim($sect), "Phone", $inifile);
                           echo ("         <td>\n\r");
                           echo ("            <input name=\"phone\" type=\"text\" class=\"text\" value=\"" . $phone . "\">\n\r");
                           echo ("         </td>\n\r");
                           echo ("         <td>\n\r");
                           echo ("            <input name=\"btn_update\" type=\"submit\" class=\"button\" value=\"" . $tc_language['btn_update'] . "\" onMouseOver=\"this.className='button-over';\" onMouseOut=\"this.className='button';\">&nbsp;\n\r");
                           echo ("            <input name=\"btn_delete\" type=\"submit\" class=\"button\" value=\"" . $tc_language['btn_delete'] . "\" onMouseOver=\"this.className='button-over';\" onMouseOut=\"this.className='button';\">&nbsp;\n\r");
                           echo ("         </td>\n\r");
                           echo ("      </tr>\n\r");
                           echo ("      <tr><td colspan=\"6\"><hr SIZE=\"1\"></td></tr>\n\r");
                           echo ("   </table>\n\r");
                           echo ("</form>\n\r");
                        }
                        $i += 1;
                     }
                  }
                  $ini->close($inifile);
                  ?>
               </td>
            </tr>
            <tr>
               <td class="edit-caption"><?php echo $tc_language['column_name']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_position']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_group']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_email']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_phone']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_action']; ?></td>
            </tr>
            <tr>
               <td colspan="6">
                  <hr size="1">
               </td>
            </tr>
            <tr>
               <td colspan="6">
                  <form  name="form-add" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                     <table>
                        <tr>
                           <td>
                              <input name="nameadd" type="text" class="text" value="">
                           </td>
                           <td>
                              <input name="posadd" type="text" class="text" value="">
                           </td>
                           <td>
                              <select name="groupadd" class="select">
                              <?php
                              foreach ($groups as $groupentry)
                              {
                                 echo ("<option class=\"option\" value=\"" . $groupentry . "\">" . $groupentry . "</option>\n\r");
                              }
                              ?>
                              </select>
                           </td>
                           <td>
                              <input name="emailadd" type="text" class="text" value="">
                           </td>
                           <td>
                              <input name="phoneadd" type="text" class="text" value="">
                           </td>
                           <td>
                              <input name="btn_add" type="submit" class="button" value="<?php echo $tc_language['btn_add']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
                           </td>
                        </tr>
                     </table>
                  </form>
               </td>
            </tr>
         </table>
         <br>
      </td>
   </tr>
   <tr>
      <td class="bottom-menu">
         <form name="formmenu" method="post" action="javascript:closeme();">
            <input name="btn_done" type="button" class="button" onclick="javascript:closeme();" value="<?php echo $tc_language['btn_done']; ?>" onmouseover="this.className='button-over';" onmouseout="this.className='button';">
         </form>
      </td>
   </tr>
</table>
<br>
<?php
if ($error)
   echo ("<script type=\"text/javascript\">alert(\"" . $errmsg . "\")</script>");
include ('includes/footer.html.inc.php');
?>
