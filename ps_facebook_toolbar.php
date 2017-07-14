<?php

/*
Plugin Name: Facebook like toolbar
Plugin URI: http://piotrszarmach.com
Description: Add to your site facebook like toolbar with custom message to insert.
Version: 1.2.2
Author: Piotr Szarmach
Author URI: http://piotrszarmach.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once( plugin_dir_path( __FILE__ ) . 'php/ps_facebook_toolbar_views.php' );

class PSFlt extends PSFltViews {
	
	private $version = '1.2.2';
	private $headerTitle = 'Facebook like toolbar';
	private $url;
	private $pluginOptions = array(); //array ps_facebook_options
	private $responseMsg;
	
	
	public function __construct(){
		
		$this->url = plugin_dir_url( __FILE__ );
		
		//load plugin translations
		load_plugin_textdomain('ps_facebook_toolbar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		
		//install plugin
		register_activation_hook( __FILE__,  array( $this, 'ps_facebook_toolbar_install' ) );
			$this->pluginOptions = get_option( 'ps_facebook_options' );
		
		//uninstall plugin
		register_deactivation_hook( __FILE__,  array( $this, 'ps_facebook_toolbar_uninstall' ) );
		
		//load all styles and js PUBLIC
		add_action( 'wp_enqueue_scripts', array( $this, 'registerStylesPublic' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'registerJSPublic' ) );
		add_action( 'wp_head', array( $this, 'ps_facebook_setVariables' ) );
		
		//load all styles and js ADMIN
		add_action( 'admin_enqueue_scripts', array( $this, 'ps_facebook_toolbar_registerJSAdmin' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'ps_facebook_toolbar_registerCSSAdmin' ) );

		//create menu
		add_action( 'admin_menu', array( $this, 'ps_facebook_toolbar_menu' ) );
		
		//check update plugin options
		add_action( 'init', array( $this, 'ps_facebook_toolbar_update_settings' ) );
		
		

	}
	
	public function ps_facebook_toolbar_install(){
		
		$this->pluginOptions['ps_facebook_backgroundColor'] = '#333333';
		$this->pluginOptions['ps_facebook_fontColor'] = '#d4d4d4';
		$this->pluginOptions['ps_facebook_linkColor'] = '#ffffff';
		$this->pluginOptions['ps_facebook_iconColor'] = '#ffffff';
		$this->pluginOptions['ps_facebook_iconBackground'] = '#000000';
		$this->pluginOptions['ps_facebook_daysInfo'] = 7;
		$this->pluginOptions['ps_facebook_showInfoDelay'] = 1000;
		$this->pluginOptions['ps_facebook_msgTxt'] = 'This is sample message where you can put <a href="http://piotrszarmach.com" target="_blank" style="font-weight: bold;">html</a>. You can insert here your privacy policy, contact data, marketing slogan or everything you want ;)';
		$this->pluginOptions['ps_facebook_faceID'] = '<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FWordPress%3Ffref%3Dts&amp;width=200&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=280431872101254" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:62px;" allowTransparency="true"></iframe>';
		$this->pluginOptions['ps_facebook_position'] = 'bottom';
		$this->pluginOptions['ps_facebook_autohide'] = 8000;
		$this->pluginOptions['ps_facebook_fbBackground'] = '#3b5ba4';

		if( add_option( 'ps_facebook_options', $this->pluginOptions ) == FALSE ){
			
			//remove existing values (in future add smart function):
			delete_option( 'ps_facebook_options' );
			
			if( update_option( 'ps_facebook_options', $this->pluginOptions ) == FALSE ){

				wp_die( __( 'Wystąpił błąd aktywacji wtyczki. Spróbuj ponownie za moment.' ) );

			}

		}

	}

	public function ps_facebook_toolbar_uninstall(){

		delete_option( 'ps_facebook_options' );

	}
	
	public function registerStylesPublic(){
         
		wp_register_style( 'handlerPublicStyle', $this->url . 'css/ps_facebook_toolbar.style.css', $this->version );
			wp_enqueue_style( 'handlerPublicStyle' );
		wp_register_style( 'handlerPublicIcons', $this->url . 'img/icons/style.css', $this->version );
			wp_enqueue_style( 'handlerPublicIcons' );

	}
	
	public function registerJSPublic(){
		
		wp_register_script( 'handlerPublicJS', $this->url . 'js/ps_facebook_toolbar.jquery.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'handlerPublicJS' );

	}
	
	public function ps_facebook_toolbar_registerJSAdmin(){
		
		wp_register_script( 'handlerAdminJSPlugin', $this->url . 'js/jquery.minicolors.min.js', $this->version );
			wp_enqueue_script( 'handlerAdminJSPlugin' );
			
		wp_register_script( 'handlerAdminJSProject', $this->url . 'js/ps_facebook_toolbar_admin.jquery.js', $this->version );
			wp_enqueue_script( 'handlerAdminJSProject' );

	}

	public function ps_facebook_toolbar_registerCSSAdmin(){
		
		wp_register_style( 'handlerAdminCSS', $this->url . 'js/jquery.minicolors.css', $this->version );
			wp_enqueue_style( 'handlerAdminCSS' );
		
		wp_register_style( 'handlerAdminCSS2', $this->url . 'js/ps_facebook_toolbar_admin.css', $this->version );
			wp_enqueue_style( 'handlerAdminCSS2' );
		
	}
	
	public function ps_facebook_setVariables(){
		
		echo '<script>';
		
		if( $this->pluginOptions['ps_facebook_daysInfo'] == '' ){
			echo 'var ps_facebook_daysInfo = 7;';
		} else {
			echo 'var ps_facebook_daysInfo = ' . $this->pluginOptions['ps_facebook_daysInfo'] . ';';
		}
		
		if( $this->pluginOptions['ps_facebook_showInfoDelay'] == '' ){
			echo 'var ps_facebook_showInfoDelay = 1000;';
		} else {
			echo 'var ps_facebook_showInfoDelay = ' . $this->pluginOptions['ps_facebook_showInfoDelay'] . ';';
		}
		
		if( $this->pluginOptions['ps_facebook_msgTxt'] == '' ){
			echo 'var ps_facebook_msgTxt = "This is sample message where you can put <a href="http://piotrszarmach.com" target="_blank" style="font-weight: bold;">html</a>. You can insert here your privacy policy, contact data, marketing slogan or everything you want ;)";';
		} else {
			echo 'var ps_facebook_msgTxt = "' . addslashes( $this->pluginOptions['ps_facebook_msgTxt'] ) . '";';
		}

		if( $this->pluginOptions['ps_facebook_faceID'] == '' ){
			echo 'var ps_facebook_faceID = "<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FWordPress%3Ffref%3Dts&amp;width=200&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=280431872101254" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:62px;" allowTransparency="true"></iframe>"';
		} else {
			echo 'var ps_facebook_faceID = "' . addslashes( $this->pluginOptions['ps_facebook_faceID'] ) . '";';
		}
		
		if( $this->pluginOptions['ps_facebook_position'] == '' ){
			echo 'var ps_facebook_position = "bottom"';
		} else {
			echo 'var ps_facebook_position = "' . addslashes( $this->pluginOptions['ps_facebook_position'] ) . '";';
		}
		
		if( $this->pluginOptions['ps_facebook_autohide'] == '' ){
			echo 'var ps_facebook_autohide = 0;';
		} else {
			echo 'var ps_facebook_autohide = ' . $this->pluginOptions['ps_facebook_autohide'] . ';';
		}
		
		echo '
		</script>
		
		<style>
			.ps_facebook_toolbar{
				background: ' . $this->pluginOptions['ps_facebook_backgroundColor'] . ';
				color: ' . $this->pluginOptions['ps_facebook_fontColor'] . ';';
			//position toolbar top/bottom
			if( $this->pluginOptions['ps_facebook_position']  == 'top' ){
				echo '
					top: 0px;
					-webkit-box-shadow: 0px 7px 20px 0px rgba(50, 50, 50, 0.45);
					-moz-box-shadow: 0px 7px 20px 0px rgba(50, 50, 50, 0.45);
					box-shadow: 0px 7px 20px 0px rgba(50, 50, 50, 0.45);
				';
			} else if( $this->pluginOptions['ps_facebook_position'] == 'bottom' ){
				echo '
					bottom: 0px;
					-webkit-box-shadow: 0px -7px 20px 0px rgba(50, 50, 50, 0.45);
					-moz-box-shadow: 0px -7px 20px 0px rgba(50, 50, 50, 0.45);
					box-shadow: 0px -7px 20px 0px rgba(50, 50, 50, 0.45);
				';
			}
			
			echo '
			}';
			
			//autohide true or false
			if( $this->pluginOptions['ps_facebook_autohide'] > 0 ){
				echo '
					.ps_facebook_toolbar > p{
						padding: 6px 12px 16px 12px !important;
					}
				';
			}
		echo '
				.ps_facebook_toolbar a{
					color: ' . $this->pluginOptions['ps_facebook_linkColor'] . ';
				}
	
			.icon-close{
				background: ' . $this->pluginOptions['ps_facebook_iconBackground'] . ';
				color: ' . $this->pluginOptions['ps_facebook_iconColor'] . ';
			}
			
			.ps_facebook_toolbar_facebook{
				background: ' . $this->pluginOptions['ps_facebook_fbBackground'] . ';
			}
		</style>';

	}
	
	public function ps_facebook_toolbar_menu(){
		
		add_menu_page ( $this->headerTitle, $this->headerTitle, 'manage_options', 'ps_facebook_toolbar', array( $this, 'ps_facebook_toolbar_settings' ), $this->url . 'img/logo.png' );

	}
	
	public function ps_facebook_toolbar_settings(){
		
		$this->showHead( $this->headerTitle );
		
		echo $this->responseMsg;
		
		$this->contentBegin();
			$this->addWPNonce();
			$this->addHidden( 'ps_facebook_update', 'update' );
			$this->addBtn( __( 'Wymaż ciastka', 'ps_facebook_toolbar' ), 'ps_facebook_resetBtn', 'button button-primary' );
			$this->addInput( __( 'Kolor tła:', 'ps_facebook_toolbar' ), 'ps_facebook_backgroundColor', null, $this->pluginOptions['ps_facebook_backgroundColor'] );
			$this->addInput( __( 'Kolor czcionki:', 'ps_facebook_toolbar' ), 'ps_facebook_fontColor', null, $this->pluginOptions['ps_facebook_fontColor'] );
			$this->addInput( __( 'Kolor odnośników:', 'ps_facebook_toolbar' ), 'ps_facebook_linkColor', null, $this->pluginOptions['ps_facebook_linkColor'] );
			$this->addInput( __( 'Kolor ikony zamknięcia:', 'ps_facebook_toolbar' ), 'ps_facebook_iconColor', null, $this->pluginOptions['ps_facebook_iconColor'] );
			$this->addInput( __( 'Kolor tła ikony zamknięcia:', 'ps_facebook_toolbar' ), 'ps_facebook_iconBackground', null, $this->pluginOptions['ps_facebook_iconBackground'] );
			$this->addInput( __( 'Kolor tła facebook:', 'ps_facebook_toolbar' ), 'ps_facebook_fbBackground', null, $this->pluginOptions['ps_facebook_fbBackground'] );
			$this->addSelect( __( 'Położenie okna:', 'ps_facebook_toolbar' ), 'ps_facebook_position', null, array( 'top' => __( 'góra', 'ps_facebook_toolbar' ), 'bottom' => __( 'dół', 'ps_facebook_toolbar' ) ), $this->pluginOptions['ps_facebook_position'] );
			$this->addSelect( __( 'Automatycznie ukrycie komunikatu:', 'ps_facebook_toolbar' ), 'ps_facebook_autohide', null, array( 0 => __( 'wyłącz', 'ps_facebook_toolbar' ), 5000 => '5000ms', 6000 => '6000ms', 7000 => '7000ms', 8000 => '8000ms', 9000 => '9000ms', 10000 => '10000ms', 12000 => '12000ms', 15000 => '15000ms', 20000 => '20000ms' ), $this->pluginOptions['ps_facebook_autohide'] );
			$this->addSelect( __( 'Reset ciasteczek:', 'ps_facebook_toolbar' ), 'ps_facebook_daysInfo', null, array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 10 => 10, 14 => 14, 18 => 18, 25 => 25, 31 => 31 ), $this->pluginOptions['ps_facebook_daysInfo'] );
			$this->addSelect( __( 'Opóźnienie w wyświetlaniu okna:', 'ps_facebook_toolbar' ), 'ps_facebook_showInfoDelay', null, array( 0 => '0ms', 100 => '100ms', 250 => '250ms', 500 => '500ms', 1000 => '1000ms', 2000 => '2000ms', 5000 => '5000ms' ), $this->pluginOptions['ps_facebook_showInfoDelay'] );
			$this->addInput( __( 'Tekst wiadomości:', 'ps_facebook_toolbar' ), 'ps_facebook_msgTxt', 'width100p', $this->pluginOptions['ps_facebook_msgTxt'] );
			$this->addTextarea( __( '<u>iframe</u> z treścią facebook', 'ps_facebook_toolbar' ) . ' (' . __( 'kliknij', 'ps_facebook_toolbar' ) . ' <a href="https://developers.facebook.com/docs/plugins/like-button/" target="_blank">' . __( 'tutaj', 'ps_facebook_toolbar' ) . '</a> ' . __( 'lub', 'ps_facebook_toolbar' ) . ' <a href="https://developers.facebook.com/docs/plugins/like-box-for-pages" target="_blank">' . __( 'tutaj', 'ps_facebook_toolbar' ) . '</a> ' . __( 'aby wygenerować', 'ps_facebook_toolbar' ) . ')', 'ps_facebook_faceID', 'width100p', $this->pluginOptions['ps_facebook_faceID'] );
		$this->contentClose();
		
		$this->submitBtn();

	}

	public function ps_facebook_toolbar_update_settings(){
		
		if( isset( $_REQUEST['ps_facebook_update'] ) == 'update' ){
		
			$nonce = $_REQUEST['_wpnonce'];

			if ( !wp_verify_nonce( $nonce, 'ps_facebook_toolbar' ) ){
				
				wp_die( __( 'Wystąpił błąd aplikacji. Nieautoryzowane połączenie.' ) );

			} else if ( wp_verify_nonce( $nonce, 'ps_facebook_toolbar' ) ) {
				
				require_once( plugin_dir_path( __FILE__ ) . 'php/ps_verify.php' );
				$verify = new PSFltVerify();
				
				$ps_facebook_backgroundColor = $_REQUEST['ps_facebook_backgroundColor'];
				$ps_facebook_fontColor = $_REQUEST['ps_facebook_fontColor'];
				$ps_facebook_linkColor = $_REQUEST['ps_facebook_linkColor'];
				$ps_facebook_iconColor = $_REQUEST['ps_facebook_iconColor'];
				$ps_facebook_iconBackground = $_REQUEST['ps_facebook_iconBackground'];
				$ps_facebook_daysInfo = $_REQUEST['ps_facebook_daysInfo'];
				$ps_facebook_showInfoDelay = $_REQUEST['ps_facebook_showInfoDelay'];
				$ps_facebook_msgTxt = stripslashes( $_REQUEST['ps_facebook_msgTxt'] );
				$ps_facebook_faceID = stripslashes( $_REQUEST['ps_facebook_faceID'] );
				$ps_facebook_position = $_REQUEST['ps_facebook_position'];
				$ps_facebook_autohide = $_REQUEST['ps_facebook_autohide'];
				$ps_facebook_fbBackground = $_REQUEST['ps_facebook_fbBackground'];
				
				if( $verify->ps_isHex( $ps_facebook_fbBackground ) == TRUE && $verify->ps_isInt( (int)$ps_facebook_autohide ) == TRUE && $verify->ps_fromValues( $ps_facebook_position, array( 'top', 'bottom' ) ) == true && $verify->ps_isFacebook( $ps_facebook_faceID ) == true && $verify->ps_isHex( $ps_facebook_backgroundColor ) == true && $verify->ps_isHex( $ps_facebook_fontColor ) == true && $verify->ps_isHex( $ps_facebook_linkColor ) == true && $verify->ps_isHex( $ps_facebook_iconColor ) == true && $verify->ps_isHex( $ps_facebook_iconBackground ) == true && $verify->ps_isInt( (int)$ps_facebook_daysInfo ) == true && $verify->ps_isInt( (int)$ps_facebook_showInfoDelay ) == true ){
					
					$this->pluginOptions['ps_facebook_backgroundColor'] = $ps_facebook_backgroundColor;
					$this->pluginOptions['ps_facebook_fontColor'] = $ps_facebook_fontColor;
					$this->pluginOptions['ps_facebook_linkColor'] = $ps_facebook_linkColor;
					$this->pluginOptions['ps_facebook_iconColor'] = $ps_facebook_iconColor;
					$this->pluginOptions['ps_facebook_iconBackground'] = $ps_facebook_iconBackground;
					$this->pluginOptions['ps_facebook_daysInfo'] = $ps_facebook_daysInfo;
					$this->pluginOptions['ps_facebook_showInfoDelay'] = $ps_facebook_showInfoDelay;
					$this->pluginOptions['ps_facebook_msgTxt'] = $ps_facebook_msgTxt;
					$this->pluginOptions['ps_facebook_faceID'] = $ps_facebook_faceID;
					$this->pluginOptions['ps_facebook_position'] = $ps_facebook_position;
					$this->pluginOptions['ps_facebook_autohide'] = $ps_facebook_autohide;
					$this->pluginOptions['ps_facebook_fbBackground'] = $ps_facebook_fbBackground;
					
					update_option( 'ps_facebook_options', $this->pluginOptions );

					$this->responseMsg = '<div id="message" class="updated"><p>'. __( 'Ustawienia zostały zapisane. ', 'ps_facebook_toolbar' ) . '</p></div>';

				} else {
					
					$this->responseMsg = '<div id="message" class="error">'. __( '<p>Wystąpił błąd: <strong>wypełnij poprawnie wszystkie pola.</strong></p><p>Pamiętaj że wtyczka przyjmuje tylko kod facebook w postaci <strong>iframe</strong></p>', 'ps_facebook_toolbar' )  . '</div>';

				}

			}
			
		}
		
	}

}


$PSFlt = new PSFlt;


?>