sct_date = new Date();
sct_date = (sct_date.getMonth()+1)+'/'+sct_date.getDate()+'/'+sct_date.getFullYear()+' '+sct_date.getHours()+':'+sct_date.getMinutes();
edButtons[edButtons.length]=new edButton("sct_code","Countdown","[sct date=\""+sct_date+"\" align=\"none\" size=\"1\"]","","SCT", -1);