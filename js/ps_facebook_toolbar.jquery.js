jQuery(document).ready(function ($){
	
	var $newElement;
	
	var topBottomMargin = 0;
	var topBottomMarginSingle = {};
	var topBottomMarginSingleZero = {};
	var topBottomMarginMultiple = {};
	
	function readCookie(name) {
	    var nameEQ = escape(name) + "=";
	    var ca = document.cookie.split(';');
	    for (var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
	        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
	    }
	    return null;
	}
	
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
	
	function checkShow(){
		
		if( readCookie( 'ps_facebook_toolbar_cookie' ) == null ){
			
			if( ps_facebook_autohide > 0 ){
			
				$newElement = $( '<div class="ps_facebook_toolbar"><p class="ps_facebook_toolbar_msg">' + ps_facebook_msgTxt + '</p><p class="ps_facebook_toolbar_facebook">' + ps_facebook_faceID + '</p><span class="ps_facebook_toolbar_facebook_logo"></span><div class="ps_facebook_toolbar_close_container"><span id="ps_facebook_toolbar_close" class="icon-close"></span></div> <div class="ps_facebook_progress"><div class="ps_facebook_progress_bar"></div></div> </div>' );
			
			} else {
				
				$newElement = $( '<div class="ps_facebook_toolbar"><p class="ps_facebook_toolbar_msg">' + ps_facebook_msgTxt + '</p><p class="ps_facebook_toolbar_facebook">' + ps_facebook_faceID + '</p><span class="ps_facebook_toolbar_facebook_logo"></span><div class="ps_facebook_toolbar_close_container"><span id="ps_facebook_toolbar_close" class="icon-close"></span></div></div>' );
			
			}
			$( 'body' ).append( $newElement );
			
			//small height hack
			$( '.icon-close' ).css( {
				
				height: $( '.ps_facebook_toolbar' ).height()
				
			} );
			
			//top or bottom
			topBottomMargin = - $newElement.height() - 7 + 'px';
			if( ps_facebook_position == 'top' ){
				
				topBottomMarginSingle[ps_facebook_position] = topBottomMargin;
				topBottomMarginSingleZero[ps_facebook_position] = '0px';
				topBottomMarginMultiple[ps_facebook_position] = topBottomMargin;
				topBottomMarginMultiple['display'] = 'table';

			} else if( ps_facebook_position == 'bottom' ) {
				
				topBottomMarginSingle[ps_facebook_position] = topBottomMargin;
				topBottomMarginSingleZero[ps_facebook_position] = '0px';
				topBottomMarginMultiple[ps_facebook_position] = topBottomMargin;
				topBottomMarginMultiple['display'] = 'table';
				
			}
			//end top or bottom
			
			$newElement.delay( ps_facebook_showInfoDelay ).css( topBottomMarginMultiple ).animate( topBottomMarginSingleZero, 300, function(){
			
				if( ps_facebook_autohide > 0 )
				animateProgressBar( ps_facebook_autohide );
				
			});
			
		}
		
	}
	
	function animateProgressBar( time ){
		
		$( '.ps_facebook_progress_bar' ).animate( {
			
			width: '100%'
			
		}, time, "linear", function(){
			
			closeToolbar();
			
		} );
		
	}
	
	function closeToolbar(){

		createCookie( 'ps_facebook_toolbar_cookie', true, ps_facebook_daysInfo );

		$newElement.animate( topBottomMarginSingle, 350, function(){

				$( this ).css( { 'display': 'none' } );

		} );

	}
	
	//icon-close
	$( '#ps_facebook_toolbar_close' ).live('click',function() {
		
		closeToolbar();
		
	} );
	
	//stop/start animaton progressbar - hover
	if( ps_facebook_autohide > 0 ){
	
		$( 'body' ).on({
		    mouseenter: function () {
	
				$( '.ps_facebook_progress_bar' ).stop( true, false);
	
		    },
		    mouseleave: function () {
	
				var time = ( ps_facebook_autohide * parseInt( $( '.ps_facebook_progress_bar' ).width() ) / $( '.ps_facebook_progress' ).width() );
				time = ps_facebook_autohide - time;
		        animateProgressBar( time );
	
		    }
		}, '.ps_facebook_toolbar' );
	
	}
	
	checkShow();
	
	
});