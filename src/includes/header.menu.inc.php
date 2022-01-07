   <!-- includes/header.menu.inc.php -->
   <table class="menubar">
      <tr>
         <td class="menubar-left">
            <table class="menu">
               <tr>
                  <td class="menu" onMouseOver="this.classname='menuover';" onMouseOut="this.classname='menu';"><a href="index.php" class="menu"><?php echo $tc_language['menu_home']; ?></a></td>
                  <td class="menu" onMouseOver="this.classname='menuover';" onMouseOut="this.classname='menu';"><a href="javascript:this.blur();openPopup('editteam.php?','edteam','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=no,dependent=1,width=880,height=600');" class="menu"><?php echo $tc_language['menu_edit_team']; ?></a></td>
                  <td class="menu" onMouseOver="this.classname='menuover';" onMouseOut="this.classname='menu';"><a href="javascript:this.blur();openPopup('editgroups.php?','edgroups','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=600,height=600');" class="menu"><?php echo $tc_language['menu_edit_groups']; ?></a></td>
                  <td class="menu" onMouseOver="this.classname='menuover';" onMouseOut="this.classname='menu';"><a href="javascript:this.blur();openPopup('legend.php?','legend','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=500,height=500');" class="menu"><?php echo $tc_language['menu_legend']; ?></a></td>
                  <td class="menu" onMouseOver="this.classname='menuover';" onMouseOut="this.classname='menu';"><a href="javascript:this.blur();openPopup('message.php?','message','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=500,height=500');" class="menu"><?php echo $tc_language['menu_message']; ?></a></td>
               </tr>
            </table>
         </td>
         <td class="menubar-right">
            <form method="post" name="form_teamcal" action="<?php echo $_SERVER['PHP_SELF'] ?>">
               <?php
               // Read Groups
               $teamcalini = new myini;
               $teamcalfile = $teamcalini->connect("teamcal.ini");
               $groups = $teamcalini->get_keys("Groups",$teamcalfile);
               $teamcalini->close($teamcalfile);
               ?>
               <span class="nav-right">
                  <?php echo $tc_language['nav_groupfilter']; ?>&nbsp;
               </span>
               <select name="groupfilter" class="select" onchange="document.forms.form_teamcal.submit();">
                  <option class="option" value="All">All</option>
                  <?php 
                  if (sizeof($_POST)>0) { $groupfilter = $_POST['groupfilter']; } else { $groupfilter="All"; }
                  foreach($groups as $groupentry) 
                  {
                     if ($groupfilter==$groupentry) 
                     {
                        echo ("<option class=\"option\" value=\"" . $groupfilter . "\" SELECTED=\"selected\">" . $groupfilter . "</option>\n\r");
                     }else{
                        echo ("<option class=\"option\" value=\"" . $groupentry . "\" >" . $groupentry . "</option>\n\r");
                     }
                  }
                  ?>
               </select>
               <span class="nav-right">
                  &nbsp;&nbsp;<?php echo $tc_language['nav_start_with']; ?>&nbsp;
               </span>
               <select name="month_id" ID="month_id" class="select" onchange="document.forms.form_teamcal.submit();">
                  <option value="1" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="1"?'SELECTED="selected"':''; }else{ echo $currmonth==1?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][1]; ?></option>
                  <option value="2" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="2"?'SELECTED="selected"':''; }else{ echo $currmonth==2?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][2]; ?></option>
                  <option value="3" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="3"?'SELECTED="selected"':''; }else{ echo $currmonth==3?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][3]; ?></option>
                  <option value="4" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="4"?'SELECTED="selected"':''; }else{ echo $currmonth==4?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][4]; ?></option>
                  <option value="5" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="5"?'SELECTED="selected"':''; }else{ echo $currmonth==5?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][5]; ?></option>
                  <option value="6" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="6"?'SELECTED="selected"':''; }else{ echo $currmonth==6?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][6]; ?></option>
                  <option value="7" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="7"?'SELECTED="selected"':''; }else{ echo $currmonth==7?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][7]; ?></option>
                  <option value="8" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="8"?'SELECTED="selected"':''; }else{ echo $currmonth==8?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][8]; ?></option>
                  <option value="9" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="9"?'SELECTED="selected"':''; }else{ echo $currmonth==9?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][9]; ?></option>
                  <option value="10" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="10"?'SELECTED="selected"':''; }else{ echo $currmonth==10?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][10]; ?></option>
                  <option value="11" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="11"?'SELECTED="selected"':''; }else{ echo $currmonth==11?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][11]; ?></option>
                  <option value="12" <?php if (sizeof($_POST)>0) { echo $_POST['month_id']=="12"?'SELECTED="selected"':''; }else{ echo $currmonth==12?'SELECTED="selected"':'';echo "";}?> ><?php echo $tc_config['monthnames'][12]; ?></option>
               </select>
               <select name="year_id" ID="year_id" class="select" onchange="document.forms.form_teamcal.submit();">
                  <option value="2009" <?php if (sizeof($_POST)>0) { echo $_POST['year_id']=="2009"?'SELECTED':''; }else{ echo $curryear=="2009"?'SELECTED':''; }?> >2009</option>
                  <option value="2010" <?php if (sizeof($_POST)>0) { echo $_POST['year_id']=="2010"?'SELECTED':''; }else{ echo $curryear=="2010"?'SELECTED':''; }?> >2010</option>
                  <option value="2011" <?php if (sizeof($_POST)>0) { echo $_POST['year_id']=="2011"?'SELECTED':''; }else{ echo $curryear=="2011"?'SELECTED':''; }?> >2011</option>
                  <option value="2012" <?php if (sizeof($_POST)>0) { echo $_POST['year_id']=="2012"?'SELECTED':''; }else{ echo $curryear=="2012"?'SELECTED':''; }?> >2012</option>
               </select>
               <select name="show_id" ID="show_id" class="select" onchange="document.forms.form_teamcal.submit();">
                  <option value="0" <?php if (sizeof($_POST)>0) { echo $_POST['show_id']=="0"?'SELECTED="selected"':''; }else { echo ""; }?> ><?php echo $tc_language['drop_show_3_months']; ?></option>
                  <option value="1" <?php if (sizeof($_POST)>0) { echo $_POST['show_id']=="1"?'SELECTED="selected"':''; }else { echo ""; }?> ><?php echo $tc_language['drop_show_6_months']; ?></option>
                  <option value="2" <?php if (sizeof($_POST)>0) { echo $_POST['show_id']=="2"?'SELECTED="selected"':''; }else { echo ""; }?> ><?php echo $tc_language['drop_show_12_months']; ?></option>
               </select>
               <input TYPE="submit" value="<?php echo $tc_language['btn_switch']; ?>" class="button"  onMouseOver="this.classname='button-over';" onMouseOut="this.classname='button';">
            </form>
         </td>
      </tr>
   </table>
   <br>
