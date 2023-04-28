<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.7.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}


// database connerction

$servername = "localhost"; // Replace with your MySQL server name.
$username = "root"; // Replace with your MySQL username.
$password = "root"; // Replace with your MySQL password.
$dbname = "wp_test"; // Replace with your MySQL database name.

// Create a MySQL connection.
$conn = new mysqli( $servername, $username, $password, $dbname );

// Check connection.
if ( $conn->connect_error ) {
    die( "Connection failed: " . $conn->connect_error );
}






add_filter( 'gform_validation', 'validate_membership_number', 10, 2 );
function validate_membership_number( $validation_result, $form ) {

    
   
    global $wpdb;
    
    $membership_number = rgpost( 'input_9' ); 
    $table_name = "wp_membership";
    $wpdb->query( "SELECT id FROM $table_name WHERE number = '$membership_number' " );
    
    $result = $wpdb->num_rows;
    var_dump($result);

    if ( $wpdb->num_rows > 0 ) {
       // member not exist
        
    }else{
    	
        
        
    	$validation_result['is_valid'] = false;
        $validation_result['form']['fields'][1]['failed_validation'] = true; 
        $validation_result['form']['fields'][1]['validation_message'] = 'Your membership number will be send in your email address.';
        

        

        ?>
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
           <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
           <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
           <script type="text/javascript">
               
                 

           	    let email_id = prompt('Enter Your Email address');
			      

                  $.ajax({
				    type: 'POST',
				    url: '<?php echo $_SERVER['REQUEST_URI'] ?>',
				    data: {email: email_id,action:'get_membership_id'},
				    success: function(response) {
				         var json_msg = JSON.parse(response);
	                     var msg = json_msg.msg;
                        
	                     if(msg == "membership number are successs sent your mail id"){
                             alert(msg)

	                     }else if(msg == "email are invalid"){
	                     	alert(msg);
 							
	                     }
				    }
			    });	
               
              

			   
           </script> 
       
           <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>

        <?php
            
		    
    }
    
   



    return $validation_result;
}






function get_membership_id(){
	
	
	if(isset($_POST['email'])){
         
         
		$email = $_POST['email'];
        
		global $wpdb;
	    $table_name = $wpdb->prefix . "membership";
        
        $query = "SELECT * FROM $table_name WHERE email = '$email'";
        $results = $wpdb->get_results( $query );
	    // $rows = $wpdb->query( "SELECT * FROM $table_name WHERE email = '$email' " );
	     
        if ( $wpdb->num_rows > 0 ) {

			foreach ( $results as $row ) :
               $email = $row->email;
               $number = $row->number;

               $to = $email; 
			   $subject = 'Your Membership Number';
			   $body = 'Your Membership number is&nbsp;&nbsp;:-<b>&nbsp;&nbsp;'. $number.'</b>';
			   $headers = array('Content-Type: text/html; charset=UTF-8');

			   wp_mail( $to, $subject, $body, $headers );
               $msg = array("msg"=>"membership number are successs sent your mail id");
                echo json_encode($msg);
                exit();
			endforeach;	
           

				
                
                
          }else{

                $msg = array("msg"=>"email are invalid");
				echo json_encode($msg);
				exit();
          }

	}
}

get_membership_id();


?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript">
  	 // $(document).ready(function(){
  	 // 	$(document).on('click',"#test",function(){
  	 // 		let email_id = prompt('Enter Your Email address');
			      

     //              $.ajax({
	// 			    type: 'POST',
	// 			    url: '<?php echo $_SERVER['REQUEST_URI'] ?>',
	// 			    data: {email: email_id,action:'get_membership_id'},
	// 			    success: function(response) {
	// 			         var json_msg = JSON.parse(response);
	 //                     var msg = json_msg.msg;

	 //                     if(msg == "membership number are successs sent your mail id"){
	 //                     	alert(msg); 
	                     	
			                  
                            
	 //                     }else if(msg == "email are invalid"){
	 //                     	alert(msg);
                             
	                     
                            
	 //                     }
	// 			    }
	// 		    });	
  	 // 	})
  	 // })
  </script>
<?php





