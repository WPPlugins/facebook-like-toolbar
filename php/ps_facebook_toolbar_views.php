<?php

/*
Class name: PS View Class
Version: 0.2
Author: Piotr Szarmach
Author URI: http://piotrszarmach.com
*/

class PSFltViews {
	
	protected function showHead( $title = null ){
		
		echo '<h2>' . $title . '</h2>';

	}
	
	protected function contentBegin(){
		
		echo '<form action="#" method="post">
		
			<table class="form-table">
			<tbody>
		';

	}
	
	protected function contentClose(){
		
		echo '	
		</tbody>
			</table>
		<form>';

	}
	
	protected function addWPNonce(){
		
		wp_nonce_field( 'ps_facebook_toolbar' );

	}
	
	protected function addHidden( $hiddenName = null, $hiddenValue = null ){
		
		echo '<input type="hidden" id="' . $hiddenName . '" name="' . $hiddenName . '" value="' . $hiddenValue . '">';
		
	}
	
	protected function addInput( $msg = null, $inputName = null, $inputClass = 'regular-text', $inputValue = null ){
		
		echo '
		<tr valign="top">
			<th scope="row">
				<label for="' . $inputName. '">' . $msg . ' </label>
			</th>
			<td>
				<input class="' . $inputClass . '" type="text" value=\'' . $inputValue . '\' name="' . $inputName . '" id="' . $inputName . '">
			</td>
		</tr>';

	}
	
	protected function addSelect( $msg = null, $selectName = null, $selectClass = null, $selectValues = array(), $selectSelected = null ){
		
		echo '
		<tr valign="top">
			<th scope="row">
				<label for="' . $selectName. '">' . $msg . ' </label>
			</th>
			<td>
				
				<select class="' . $selectClass . '" id="' . $selectName. '" name="' . $selectName. '">';
					
					foreach ( $selectValues as $selectKey => $selectValue ) {
						
						echo '<option value="' . $selectKey . '"';
						
							echo $selectSelected == $selectKey ? ' selected ' : '';
						
						echo '>' . $selectValue . '</option>';
						
					}
					
				echo '
				</select>
				
			</td>
		</tr>';
		
	}
	
	protected function addTextarea( $msg = null, $textAreaName = null, $textAreaClass = null, $textAreaValue = null ){
		
		echo '
		<tr valign="top">
			<th scope="row">
				<label for="' . $textAreaName. '">' . $msg . ' </label>
			</th>
			<td>
			
				<textarea id="' . $textAreaName . '" class="' . $textAreaClass . '" rows="4" name="' . $textAreaName . '">' . $textAreaValue . '</textarea>
			
			</td>
		</tr>';
			
	}
	
	protected function submitBtn(){
		
		echo '<p class="submit">
				<input id="submit" class="button button-primary" type="submit" value="' . __( 'Zapisz', 'ps_facebook_toolbar' ) . '" name="submit">
			</p>';
		
	}
	
	protected function addBtn( $msg = null, $btnName = null, $btnClass = null ){
		
		echo '<tr valign="top">
			<th scope="row">
				<label for="' . $btnName. '"></label>
			</th>
			<td>
			
				<input type="submit" class="' . $btnClass . '" id="' . $btnName . '" name="' . $btnName . '" value="'. $msg .'" >
			
			</td>
		</tr>';
		
	}
	
}


?>