jQuery(document).ready(function ($){
	
	
	$( '#ps_facebook_fbBackground, #ps_facebook_backgroundColor, #ps_facebook_fontColor, #ps_facebook_linkColor, #ps_facebook_iconColor, #ps_facebook_iconBackground' ).minicolors();
	
	
	$( '#ps_facebook_resetBtn' ).click( function(){
		
		eraseCookie( 'ps_facebook_toolbar_cookie' );
		$( this ).closest( 'td' ).html( '' );
		
		return false;
		
	} );
	
	//reset cookies
	function createCookie(name, value, days) {
	    var expires;
	
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    } else {
	        expires = "";
	    }
	    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
	}
	function eraseCookie( cookie_name ) {
	    
	    createCookie( cookie_name,"",-1);
	    
	}
	
});