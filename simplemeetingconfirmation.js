
   function checkFields(){
   
   /*
   if (typeof document.forms['frmSMCRegistration'].txtNbGuests  == "undefined") 
		return false;
   */
   
   // check that name has been filled
   if(trim(document.forms['frmSMCRegistration'].txtUser.value) == "")	{	
		document.forms['frmSMCRegistration'].hidError.value = "txtUser";
	}
   
	// check if there is a number is the field
	if(isNaN(document.forms['frmSMCRegistration'].txtNbGuests.value)){
		document.forms['frmSMCRegistration'].hidError.value = "txtNbGuests";
	}
	
	// submit the form
	document.forms["frmSMCRegistration"].submit();	
	return true;
	
   }
   
   function trim(str) {
	chars = ' ';
	return ltrim(rtrim(str, chars), chars);
	}
	 
	function ltrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}
	 
	function rtrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	}