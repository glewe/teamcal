<?php

// ===========================================================================
//  EDITGROUPS.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit group information
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

require_once('includes/class.ini.php' );
include_once('includes/config.teamcal.php');
include_once("includes/functions.teamcal.php");

$ini = new myini;
$error = false;

$inifile = $ini->connect("teamcal.ini");
    
if (isset($_POST['btn_update'])) 
{
   // Drop old and save new value. Sort keys while you're at it.
   $ini->drop_key("Groups",$_POST['namehidden'],$inifile);
   $ini->write("Groups",$_POST['name'],$_POST['desc'],$inifile);
   // Sort section
   $keys = $ini->get_keys("Groups",$inifile);
   sort($keys);
   reset($keys);
   foreach($keys as $key) 
   {
      if (trim($key)!='')
      {
         $value = $ini->read("Groups",trim($key),$inifile);
         $ini->drop_key("Groups",$key,$inifile);
         $ini->write("Groups",$key,$value,$inifile);
      }
   }
  
   // If the group name changed we need to go through all team members of
   // this group and change it there as well.
   $ini2 = new myini;
   $ini2file = $ini2->connect("team.ini");
   $members = $ini2->get_sections($ini2file);
   if ($members) 
   {
      foreach($members as $member) 
      {
         $membergroup = $ini2->read($member,"Group",$ini2file);
         if ($membergroup == $_POST['namehidden']) 
         {
            $ini2->write($member,"Group",$_POST['name'],$ini2file);
         }
      }
   }
   $ini2->close($ini2file);
   // Send notification e-Mails
   SendNotification("groupchange","","");

}
elseif (isset($_POST['btn_delete'])) 
{
   $ini->drop_key("Groups",$_POST['namehidden'],$inifile);
   // Send notification e-Mails
   SendNotification("groupchange","","");

}
elseif (isset($_POST['btn_add'])) 
{
   if (trim($_POST['nameadd'])!='') 
   {
      $ini->write("Groups",$_POST['nameadd'],$_POST['descadd'],$inifile);
      // Sort section
      $keys = $ini->get_keys("Groups",$inifile);
      sort($keys);
      reset($keys);
      foreach($keys as $key) 
      {
         if (trim($key)!='') 
         {
            $value = $ini->read("Groups",trim($key),$inifile);
            $ini->drop_key("Groups",$key,$inifile);
            $ini->write("Groups",$key,$value,$inifile);
         }
      }
      // Send notification e-Mails
      SendNotification("groupchange","","");
   }
   else
   {
      $error = true;
      $errmsg = "WARNING\\n\\rYou have to add at least a name in order to add a new group.";
   }
}

include("includes/header.html.inc.php");
?>

<body>
<table class="edit">
   <tr>
      <td class="edit-header"><?php echo $tc_language['edit_groups']; ?></td>
   </tr>
   <tr>
      <td class="edit-body">
         <table>
            <tr>
               <td class="edit-caption"><?php echo $tc_language['column_shortname']; ?></td>
               <td class="edit-caption" width="215"><?php echo $tc_language['column_description']; ?></td>
               <td class="edit-caption" width="115"><?php echo $tc_language['column_action']; ?></td>
            </tr>
            <tr>
               <td colspan="3">
                  <hr size="1">
               </td>
            </tr>
            <tr>
               <td colspan="3">
                  <?php
                  $keys = $ini->get_keys("Groups",$inifile);
                  if ($keys) 
                  {
                     $i=1;
                     foreach($keys as $key) 
                     {
                        if (trim($key)!='') 
                        {
                           echo ("<form name=\"form" . $i . "\" method=\"POST\" action=\"". $_SERVER['PHP_SELF'] . "\">\n\r"); 
                           echo ("<table width=\"100%\">\n\r");
                           echo ("   <tr>\n\r");
                           echo ("      <td>\n\r");
                           echo ("         <input name=\"namehidden\" type=\"hidden\" class=\"text\" value=\"" . trim($key) . "\">\n\r");
                           echo ("         <input name=\"name\" size=\"16\" type=\"text\" class=\"text\" value=\"" . trim($key) . "\">\n\r");
                           echo ("      </td>\n\r");
                           $desc = $ini->read("Groups",trim($key),$inifile);
                           echo ("      <td>\n\r");
                           echo ("         <input name=\"desc\" size=\"40\" type=\"text\" class=\"text\" value=\"" . $desc . "\">\n\r");
                           echo ("      </td>\n\r");
                           echo ("      <td>\n\r");
                           echo ("         <input name=\"btn_update\" type=\"submit\" class=\"button\" value=\"" . $tc_language['btn_update'] . "\" onMouseOver=\"this.className='button-over';\" onMouseOut=\"this.className='button';\">&nbsp;\n\r"); 
                           echo ("         <input name=\"btn_delete\" type=\"submit\" class=\"button\" value=\"" . $tc_language['btn_delete'] . "\" onMouseOver=\"this.className='button-over';\" onMouseOut=\"this.className='button';\">&nbsp;\n\r"); 
                           echo ("      </td>\n\r");
                           echo ("   </tr>\n\r"); 
                           echo ("   <tr><td colspan=\"3\"><hr size=\"1\"></td></tr>\n\r"); 
                           echo ("</table>\n\r"); 
                           echo ("</form>\n\r"); 
                        } 
                        $i+=1; 
                     }
                  } 
                  $ini->close($inifile);
                  ?>
               </td>
            </tr>
            <tr>
               <td class="edit-caption"><?php echo $tc_language['column_shortname']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_description']; ?></td>
               <td class="edit-caption"><?php echo $tc_language['column_action']; ?></td>
            </tr>
            <tr>
               <td colspan="3">
                  <hr size="1">
               </td>
            </tr>
            <tr>
               <td colspan="3">
                  <form  name="form-add" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                     <table>
                        <tr>
                           <td>
                              <input name="nameadd" size="16" type="text" class="text" id="nameadd" value="">
                           </td>
                           <td>
                              <input name="descadd" size="40" type="text" class="text" id="descadd" value="">
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
if ($error) echo ("<script type=\"text/javascript\">alert(\"" . $errmsg . "\")</script>"); 
include_once('includes/footer.html.inc.php');
?>

