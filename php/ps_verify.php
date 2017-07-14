<?php

/*
Class name: PS Verify Class
Version: 0.2
Author: Piotr Szarmach
Author URI: http://piotrszarmach.com
*/

class PSFltVerify {
	
	
	public function ps_isHex( $var = null ){
		
		if( preg_match( '/^#[0-9A-F]{6}$/', strtoupper( $var ) ) ){
			
			return true;
			
		} else if( $var == '' ) {
			
			return true;
			
		} else {
			
			return false;

		}
		
	}
	
	public function ps_isInt( $var = null ){
		
		if( is_int( $var ) ){
		
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	public function ps_isFacebook( $var = null ){
	
		if( preg_match( '/^\<iframe(.*?)\<\/iframe\>$/', $var ) ){
	
			return true;
			
		} else {	
		
			return false;
			
		}
		
	}
	
	public function ps_fromValues( $var = null, $array = array() ){
		
		foreach ( $array as $key => $value ) {
			
			if( $value == $var )
				return true;

		}
		
		return false;
		
	}

}


?>