// ===========================================================================
//  TEAMCAL.JS
//  --------------------------------------------------------------------------
//  Application: TeamCal
//  Purpose:     JavaScript Routines for TeamCal
//  Author:      George Lewe
//  Copyright:   (c) 2004 by Lewe dataVisual, Germany
//               All rights reserved.
//
// ===========================================================================
 

//  --------------------------------------------------------------------------
//  Opens a popup browser window
//
function openPopup(page,winname,param)
{
    myPopup = window.open(page,winname,param);
}

//  --------------------------------------------------------------------------
//  Closes a popup browser window after reloading the opener window
//
function closeme()
{
    opener.location.reload(true);
    self.close();
}
