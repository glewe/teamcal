<?php

// ===========================================================================
//  LEGEND.PHP
//  --------------------------------------------------------------------------
//  Application:  TeamCal
//  Purpose:      Edit group information
//  Author:       George Lewe
//  Copyright:    (c) 2004-2009 by Lewe.com (www.lewe.com)
//                All rights reserved.
//
// ===========================================================================

require_once( 'includes/config.teamcal.php' );
include("includes/header.html.inc.php");
?>
<body>
   <table class="edit">
      <tr>
         <td class="edit-header"><?php echo $tc_language['teamcal_legend']; ?></td>
      </tr>
      <tr>
         <td class="edit-body">
            <table width="98%">
               <tr>
                  <td width="50%" valign="top">
                     <div align="center" >
                        <table width="100%">
                           <tr>
                              <td class="edit-caption" colspan="2"><?php echo $tc_language['col_month_header']; ?></td>
                           </tr>
                           <tr>
                              <td class="daynum" width="20">15</td>
                              <td class="legend"><?php echo $tc_language['dom_business_day']; ?></td>
                           </tr>
                           <tr>
                              <td class="daynum-weekend">16</td>
                              <td class="legend"><?php echo $tc_language['dom_weekend_day']; ?></td>
                           </tr>
                           <tr>
                              <td class="daynum-school">17</td>
                              <td class="legend"><?php echo $tc_language['dom_school_holiday']; ?></td>
                           </tr>
                           <tr>
                              <td class="daynum-bank">18</td>
                              <td class="legend"><?php echo $tc_language['dom_bank_holiday']; ?></td>
                           </tr>
                           <tr>
                              <td colspan="2">
                                 <hr size="1">
                              </td>
                           </tr>
                           <tr>
                              <td class="weekday"><?php echo $tc_config['weekdays'][3]; ?></td>
                              <td class="legend"><?php echo $tc_language['dow_business_day']; ?></td>
                           </tr>
                           <tr>
                              <td class="weekday-weekend"><?php echo $tc_config['weekdays'][6]; ?></td>
                              <td class="legend"><?php echo $tc_language['dow_weekend_day']; ?></td>
                           </tr>
                           <tr>
                              <td class="weekday-school"><?php echo $tc_config['weekdays'][1]; ?></td>
                              <td class="legend"><?php echo $tc_language['dow_school_holiday']; ?></td>
                           </tr>
                           <tr>
                              <td class="weekday-bank"><?php echo $tc_config['weekdays'][5]; ?></td>
                              <td class="legend"><?php echo $tc_language['dow_bank_holiday']; ?></td>
                           </tr>
                           <tr>
                              <td colspan="2">
                                 <hr size="1">
                              </td>
                           </tr>
                           <tr>
                              <td class="month-button"><img src="img/btn_edit_month.gif" width="16" height="16" border="0" alt=""></td>
                              <td class="legend"><?php echo $tc_language['btn_edit_month']; ?></td>
                           </tr>
                           <tr>
                              <td class="name-button"><img src="img/btn_edit.gif" width="16" height="16" border="0" alt=""></td>
                              <td class="legend"><?php echo $tc_language['btn_edit_member']; ?></td>
                           </tr>
                        </table>
                     </div>
                  </td>
                  <td width="50%" Valign="top">
                     <div align="center">
                        <table width="100%">
                           <tr>
                               <td class="edit-caption" colspan="2"><?php echo $tc_language['col_day_symbols']; ?></td>
                           </tr>
                           <tr>
                               <td class="day" width="20">&nbsp;</td>
                               <td class="legend"><?php echo $tc_language['day_business_day']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-weekend">&nbsp;</td>
                               <td class="legend"><?php echo $tc_language['day_weekend_day']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-school">&nbsp;</td>
                               <td class="legend"><?php echo $tc_language['day_school_holiday']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-bank">&nbsp;</td>
                               <td class="legend"><?php echo $tc_language['day_bank_holiday']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-duty"><?php echo $tc_config['duty_trip']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_duty_trip']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-off"><?php echo $tc_config['day_off']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_day_off']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-home"><?php echo $tc_config['home_office']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_home_office']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-not"><?php echo $tc_config['not_present']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_not_present']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-sick"><?php echo $tc_config['sick']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_sick_leave']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-train"><?php echo $tc_config['training']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_training']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-vac"><?php echo $tc_config['vacation']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_vacation']; ?></td>
                           </tr>
                           <tr>
                               <td class="day-block"><?php echo $tc_config['blocked']; ?></td>
                               <td class="legend"><?php echo $tc_language['day_blocked']; ?></td>
                           </tr>
                        </table>
                     </div>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td class="bottom-menu">
            <form name="formmenu" method="post" action="javascript:window.close();">
               <input class="button" type="submit" name="cancel" value="<?php echo $tc_language['btn_close']; ?>" onMouseOver="this.className='button-over';" onMouseOut="this.className='button';">
            </form>
         </td>
      </tr>
   </table>
   <br>
   <hr size="1">
   <div align="center">
      <span class="copyright">
         <?php echo $tc_config['app_footer_cpy']; ?><br>
         <?php echo $tc_config['app_footer_pwd']; ?><br>
      </span>
   </div>
</body>
</html>
