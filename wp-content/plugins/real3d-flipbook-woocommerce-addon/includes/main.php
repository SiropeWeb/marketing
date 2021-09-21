<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/*plugin class*/
class R3D_Woo {

	public $version;
	public $path;
    public $plugin_dir_path;
    public $plugin_dir_url;

	const MINIMUM_REAL3D_FLIPBOOK_VERSION = '3.17.1';

	// Singleton
	private static $instance = null;
	
	public static function get_instance() {

		if (null == self::$instance) {
            self::$instance = new self();
        }

		return self::$instance;
	}
	
	protected function __construct() {

        $this->version = R3D_WOO_VERSION;
        $this->path = R3D_WOO_FILE;
        $this->plugin_dir_path = plugin_dir_path($this->path);
        $this->plugin_dir_url = plugin_dir_url($this->path);

        add_action( 'plugins_loaded', array($this, 'plugins_loaded') );
		add_action( 'init', array($this, 'init') );

        register_activation_hook( $this->path, array( $this, "plugin_activated" ));

    }
        
    /*

    */
    public function plugin_activated(){

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            error_log( "real3d flipbook woocommerce addon activated");

        }
    }
	

	public function init() {

        load_plugin_textdomain( 'real3d-flipbook-woocommerce-addon', false, plugin_basename( dirname( R3D_WOO_FILE ) ) . '/languages' );
		
	}

    /**
	 * Check requirements and add actions
	 */
	public function plugins_loaded() {

		// Check if Real3D Flipbook is installed
        if (!defined('REAL3D_FLIPBOOK_VERSION')) {
            // Display notice that Real3D Flipbook is required
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_real3d_flipbook' ));
            return;
        }

		// Check for required Real3D Flipbook version
		if ( ! version_compare( REAL3D_FLIPBOOK_VERSION, self::MINIMUM_REAL3D_FLIPBOOK_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_real3d_flipbook_version' ) );
			return;
		}

		if (! function_exists( 'WC' )) {
			add_action( 'admin_notices', array($this, 'admin_notice_missing_woocommerce') );
			return;
		}

        add_action( 'add_meta_boxes', array($this, 'register_meta_boxes' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'save_post', array($this, 'save_meta_box' ) );
        add_action( 'woocommerce_after_single_product_summary', array($this, 'add_flipbook_shortcode_single_product_page'), 25 );

	}

    /**
	 * Include admin scripts on WooCommerce single product page
	 */
	public function admin_scripts() {
        global $wp_query, $post;

        $screen       = get_current_screen();
        $screen_id    = $screen ? $screen->id : '';
        $version      = $this->version;

        // Register scripts.
        wp_register_script( 'r3dwc-admin', $this->plugin_dir_url . 'js/admin.js', array( 'jquery' ), $version );
        wp_register_style( 'r3dwc-admin', $this->plugin_dir_url . 'css/admin.css', array(), $version );

        // WooCommerce admin pages.
        if (  $screen_id == "product" ) {
            wp_enqueue_script( 'r3dwc-admin' );
            wp_enqueue_style( 'r3dwc-admin' );
        }

    }

    /**
	 * Add full flipbook or preview flipbook to WooCommerce single product page
	 */
    public function add_flipbook_shortcode_single_product_page() {

        global $product;
        $product_id = $product->get_id();
        $flipbook_id = get_post_meta( $product_id, "r3d_flipbook_id", true );
        $preview_flipbook_id = get_post_meta( $product_id, "r3d_preview_flipbook_id", true );

        if(!empty($flipbook_id) && ($this->isPurchased() || $this->has_active_subscription()))
            echo do_shortcode('[real3dflipbook id="'.esc_attr($flipbook_id).'" mode="lightbox" class="woocommerce-product-gallery" thumb=""]');
        else if(!empty($preview_flipbook_id))
            echo do_shortcode('[real3dflipbook id="'. esc_attr($preview_flipbook_id).'" mode="lightbox" class="woocommerce-product-gallery" thumb=""]');

    }


    /**
	 * If current user has purchased current WooCommerce product
	 */
    public function isPurchased($product_id = null){
        global $product;
        if ( ! is_user_logged_in() ) return false;
        $current_user = wp_get_current_user();
        if(!$product_id) $product_id = $product->get_id(); 
        return ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id ) ) ;
    }

     /**
	 * If current user has active WooCommerce subscription
	 */
    public function has_active_subscription() {
        if ( ! is_user_logged_in() ) return false;
        $user_id = get_current_user_id();
        if( $user_id == 0 ) return false;
        return wcs_user_has_subscription( $user_id, '', 'active' );
    }

    /**
	 * Content of WooCommerce tab in Edit Flipbook
	 */
    public function editBookTab(){

        $args = array(
            'orderby'  => 'name'
        );
        $products = wc_get_products( $args );

        ?> 
        <div id="tab-wc"  style="display:none;">
            <table class="form-table" id="flipbook-wc-options">
                <tbody>
                    <tr>
                        <th scope="row"><label for="woo_product_id"><?php esc_html_e("Product", "real3d-flipbook-woocommerce-addon")?></label></th>
                        <td>
                            <select name="woo_product_id" id="woo_product_id">
                                <?php
                                foreach($products as $product){
                                    ?>
                                     <option value="<?php esc_attr($product->get_id()); ?>"><?php esc_html($product->get_name());?></option>
                                     <?php
                                }
                                ?>
                            </select>
                            <p class="description"><?php esc_html_e("WooCommerce product linked to this flipbook", "real3d-flipbook-woocommerce-addon")?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="woo_not_purchased_action"><?php esc_html_e("Not purchased action", "real3d-flipbook-woocommerce-addon")?></label></th>
                        <td>
                            <select name="woo_not_purchased_action" id="woo_not_purchased_action">
                                <option value="no_flipbook"><?php esc_html_e("No flipbook", "real3d-flipbook-woocommerce-addon")?></option>
                                <option value="preview_flipbook"><?php esc_html_e("Preview flipbook", "real3d-flipbook-woocommerce-addon")?></option>
                                <option value="preview_flipbook_gallery"><?php esc_html_e("Flipbook from product gallery images", "real3d-flipbook-woocommerce-addon")?></option>
                            </select>
                            <input style="display:none;" name="woo_not_purchased_url" id="woo_not_purchased_url" type="text" placeholder="http://">
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <?php
    }

	/**
	 * Register meta box(es).
	 */
	public function register_meta_boxes() {

		add_meta_box( 'fliobook-pdf', esc_html__( 'Real3D Flipbook', 'real3d-flipbook-woocommerce-addon' ), array(
			$this,
			'r3d_meta_box'
		), 'product', 'normal', 'high' );

	}

	public function r3d_meta_box( $post ){

		$r3d_flipbook_id = get_post_meta($post->ID, 'r3d_flipbook_id', true);
        $r3d_preview_flipbook_id = get_post_meta($post->ID, 'r3d_preview_flipbook_id', true);

        wp_nonce_field('r3d_save', 'r3d_nonce'); 

		?>

        <input type="hidden" id="r3d_flipbook_id" name="r3d_flipbook_id" value="<?php echo esc_attr($r3d_flipbook_id); ?>">

        <input type="hidden" id="r3d_preview_flipbook_id" name="r3d_preview_flipbook_id" value="<?php echo esc_attr($r3d_preview_flipbook_id); ?>">

        <?php
        
        $real3dflipbooks_ids = get_option('real3dflipbooks_ids');

        if(!$real3dflipbooks_ids){
            $real3dflipbooks_ids = array();
        }

        if(count($real3dflipbooks_ids)){

        ?>

        <h4><?php esc_html_e("Select flipbook", "real3d-flipbook-woocommerce-addon")?></h4>
        <span><?php esc_html_e("Flipbook for purchased product", "real3d-flipbook-woocommerce-addon")?></span>

        <div class="r3d-thumbs-wrapper full-flipbook">

            <div class="r3d-thumbs">

            <?php	

                foreach ($real3dflipbooks_ids as $id) {
                    $book = get_option('real3dflipbook_'.$id);
                    if($book){
                        $id = $book['id'];
                        $name = $book['name'];
                        ?>
    
                        <div class="r3d-thumb <?php if($r3d_flipbook_id == $id) echo (' r3d-thumb-selected')?>" data-id="<?php echo esc_attr($id);?>">
    
                            <div class="r3d-thumb-img" <?php if(isset($book['lightboxThumbnailUrl'])) ?> style="background-image: url('<?php echo esc_url($book['lightboxThumbnailUrl']); ?>'); " ></div>
    
                            <p class="r3d-thumb-name"><?php echo esc_attr($name);?></p>
    
                        </div>
    
                        <?php
                    }
                }
            
            ?>

            </div>

        </div>

        <h4><?php esc_html_e("Select flipbook", "real3d-flipbook-woocommerce-addon")?></h4>
        <span><?php esc_html_e("Flipbook for non purchased product", "real3d-flipbook-woocommerce-addon")?></span>


        <div class="r3d-thumbs-wrapper preview-flipbook">

            <div class="r3d-thumbs">
                
            <?php	

                foreach ($real3dflipbooks_ids as $id) {
                    $book = get_option('real3dflipbook_'.$id);
                    if($book){
                        $id = $book['id'];
                        $name = $book['name'];
                        ?>

                        <div class="r3d-thumb <?php if($r3d_preview_flipbook_id == $id) echo (' r3d-thumb-selected')?>" data-id="<?php echo esc_attr($id);?>">

                            <div class="r3d-thumb-img" <?php if(isset($book['lightboxThumbnailUrl'])) ?> style="background-image: url('<?php echo esc_url($book['lightboxThumbnailUrl']); ?>'); " ></div>

                            <p class="r3d-thumb-name"><?php echo esc_attr($name);?></p>

                        </div>

                        <?php
                    }
                }

            ?>

            </div>

        </div>

        <?php

        }else{
            ?>
            <p>No flipbooks found. <a href="<?php echo esc_url(get_admin_url() . 'admin.php?page=real3d_flipbook_add_new'); ?>"><?php esc_html_e("Create new flipbook", "real3d-flipbook-woocommerce-addon")?></a></p>
            <?php
        }

	}


    /**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	public function save_meta_box( $post_id ) {

		// Check if nonce is valid.
        if (!isset($_POST['r3d_nonce']) || !wp_verify_nonce($_POST['r3d_nonce'], 'r3d_save')) {
            return;
        }

        // Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

        // Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'product' ) {

			if ( isset($_POST['r3d_flipbook_id']) && !empty($_POST['r3d_flipbook_id']) ) {

                update_post_meta( $post_id, 'r3d_flipbook_id', sanitize_text_field($_POST['r3d_flipbook_id']) );

            }

            if ( isset($_POST['r3d_preview_flipbook_id']) && !empty($_POST['r3d_preview_flipbook_id']) ) {

                update_post_meta( $post_id, 'r3d_preview_flipbook_id', sanitize_text_field($_POST['r3d_preview_flipbook_id']) );

            }
        }
    }

	public function admin_notice_minimum_real3d_flipbook_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'real3d-flipbook-woocommerce-addon' ),
			'<strong>' . esc_html__( 'Real3D Flipbook WooCommerce Addon', 'real3d-flipbook-woocommerce-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Real3D Flipbook', 'real3d-flipbook-woocommerce-addon' ) . '</strong>',
			self::MINIMUM_REAL3D_FLIPBOOK_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_missing_real3d_flipbook() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'real3d-flipbook-woocommerce-addon' ),
			'<strong>' . esc_html__( 'Real3D Flipbook WooCommerce Addon', 'real3d-flipbook-woocommerce-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Real3D Flipbook', 'real3d-flipbook-woocommerce-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_missing_woocommerce() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'real3d-flipbook-woocommerce-addon' ),
			'<strong>' . esc_html__( 'Real3D Flipbook WooCommerce Addon', 'real3d-flipbook-woocommerce-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'real3d-flipbook-woocommerce-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

}

if(!function_exists("trace")){
    function trace($var){
        echo('<script type="text/javascript">console.log(' .json_encode($var). ')</script>');
    }
}