<?php

class R3D_Post_Type
{
  
  public static $instance;
  
  public $main;
  
  public function __construct()
  {
    
    $this->main = Real3DFlipbook::get_instance();
    
    $labels = array(
        'name'               => __( 'Real3D Flipbook', 'r3dfb' ),
        'singular_name'      => __( 'Real3D Flipbook', 'r3dfb' ),
        'menu_name'          => __( 'Real3D Flipbook', 'r3dfb' ),
        'name_admin_bar'     => __( 'Real3D Flipbook', 'r3dfb' ),
        'add_new'            => __( 'Add New', 'r3dfb' ),
        'add_new_item'       => __( 'Add New Flipbook', 'r3dfb' ),
        'new_item'           => __( 'New Book', 'r3dfb' ),
        'edit_item'          => __( 'Edit Book', 'r3dfb' ),
        'view_item'          => __( 'View Book', 'r3dfb' ),
        'all_items'          => __( 'Flipbooks', 'r3dfb' ),
        'search_items'       => __( 'Search', 'r3dfb' ),
        'parent_item_colon'  => __( 'Parent Book:', 'r3dfb' ),
        'not_found'          => __( 'Flipbook Not found.', 'r3dfb' ),
        'not_found_in_trash' => __( 'Flipbook Not found in Trash.', 'r3dfb' )
    );
    
    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'r3dfb' ),
        'public'             => true,  //this removes the permalink option
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => false, //array('slug' => $this->base->slug),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 81,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array( 'title', 'thumbnail, author' )
    );
    
    register_post_type( 'r3d', $args );
    
    register_taxonomy( 'r3d_category', 'r3d', array(
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'rewrite'           => array( 'slug' => 'r3d_category' ),
    ) );
    
    if ( is_admin() ) {
      $this->init_admin();
    }

  }

  public function init_admin()
  {
    
    // Remove quick editing from the r3d post type row actions.
    // add_filter( 'post_row_actions', array( $this, 'custom_actions' ), 10, 1 );
    
    add_filter( 'manage_r3d_posts_columns', array( $this, 'r3d_columns' ) );
    add_action( 'manage_r3d_posts_custom_column', array( $this, 'r3d_columns_content' ), 10, 2 );
    
    add_filter( 'manage_edit-r3d_category_columns', array( $this, 'r3d_cat_columns' ) );
    add_filter( 'manage_r3d_category_custom_column', array( $this, 'r3d_cat_columns_content' ), 10, 3 );

    add_filter( 'post_row_actions', array($this, 'duplicate_post_link'), 10, 2 );

    add_action( 'admin_action_r3d_duplicate_post', array($this, 'duplicate_post' ));


    add_action('before_delete_post', array( $this, 'deleted_post'));

  }

  public function deleted_post($post_id){
    $post = get_post( $post_id );
    $current_id = get_post_meta($post_id, 'flipbook_id', true);

    if($current_id){

      $real3dflipbooks_ids = get_option('real3dflipbooks_ids'); 
      delete_option('real3dflipbook_'.(string)$current_id);
      $real3dflipbooks_ids = array_diff($real3dflipbooks_ids, array($current_id));
      update_option('real3dflipbooks_ids', $real3dflipbooks_ids);

    }


  }

  public function custom_actions( $actions )
  {
    if ( isset( get_current_screen()->post_type ) && 'r3d' == get_current_screen()->post_type ) {
      unset( $actions['inline hide-if-no-js'] );

      $actions['duplicate'] = '<a href="">Duplicate</a>';

    }
    
    return $actions;
  }

/*
 * Function for post duplication. 
 */
public function duplicate_post(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'r3d_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }
 
  /*
   * Nonce verification
   */
  if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
    return;
 
  /*
   * get the original post id
   */
  $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
  /*
   * and all the original post data then
   */
  $post = get_post( $post_id );
 
  /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;
 
  /*
   * if post data exists, create the post duplicate
   */
  if (isset( $post ) && $post != null) {

    /*
     * flipbook ID
     */
    $current_id = get_post_meta($post_id, 'flipbook_id', true);

    /* 
     * duplicate flipbook
     */ 

    $current = get_option('real3dflipbook_'.$current_id);

    $real3dflipbooks_ids = get_option('real3dflipbooks_ids'); 

    $new_id = 0;
    $highest_id = 0;

    foreach ($real3dflipbooks_ids as $id) {
      if((int)$id > $highest_id) {
        $highest_id = (int)$id;
      }
    }
    
    $new_id = $highest_id + 1;
    $new = $current;
    $new["id"] = $new_id;
    $new["name"] = $current["name"]." (copy)";
    $new["date"] = current_time( 'mysql' );

    

    array_push($real3dflipbooks_ids,$new_id);
    update_option('real3dflipbooks_ids',$real3dflipbooks_ids);


    /*
     * new post data array
     */
    $args = array(
      'post_title'=> $post->post_title . ' (copy)',
      'post_type'=>'r3d', 
      
      // 'post_content'=>'demo text',
      'post_status'   => 'publish',
      'meta_input' => array(
        'flipbook_id' => $new_id
      )
    );

    $new_post_id = wp_insert_post($args);

    //save post id to book
    $new["post_id"] = $new_post_id;
    update_option('real3dflipbook_'.(string)$new_id,$new);

    /*
     * get all current post terms ad set them to the new post draft
     */
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
 
    /*
     * finally, redirect to flipbooks page
     */
    wp_redirect( admin_url( 'edit.php?post_type=r3d') );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
public function duplicate_post_link( $actions, $post ) {
  if ( current_user_can('edit_posts') && isset( get_current_screen()->post_type ) && 'r3d' == get_current_screen()->post_type ) {
    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=r3d_duplicate_post&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
  }
  return $actions;
}
 


  public function r3d_columns()
  {
    
    $columns = array(
        'cb'        => '<input type="checkbox" />',
        'cover' => __( 'Cover', 'r3dfb' ),
        'title'     => __( 'Title', 'r3dfb' ),
        'shortcode' => __( 'Shortcode', 'r3dfb' ),
        'date'      => __( 'Date', 'r3dfb' ),
        'author'      => __( 'Author', 'r3dfb' )
    );
    
    return $columns;
  }

  public function r3d_cat_columns( $defaults )
  {
    $defaults['shortcode'] = 'Shortcode';
    // $defaults['cover'] = 'Cover';
    
    return $defaults;
  }
  
  public function r3d_columns_content( $column_name, $post_id )
  {

    $post_id = absint( $post_id );

    $id = get_post_meta($post_id, 'flipbook_id', true);
    
    switch ( $column_name ) {
      case 'shortcode':
        echo '<code>[real3dflipbook id="' . $id . '"]</code>  <div id="'. $id . '" class="button-secondary copy-shortcode">Copy</div>';
        break;

      case 'cover':
        $book = get_option('real3dflipbook_' . $id);
        $thumb = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
        if(isset($book['lightboxThumbnailUrl']))
          $thumb = $book['lightboxThumbnailUrl'];          
        echo '<div class="thumb" style=";background-image:url('.$thumb.');"><a href="#" class="edit" name="'.$id.'"></a></div>';
        break;

    }
  }
  
  public function r3d_cat_columns_content( $c, $column_name, $term_id = "" )
  {
    
    return '<code>[real3dflipbook category="' . get_term( $term_id, 'r3d_category' )->slug . '"]</code>   <div id="'. get_term( $term_id, 'r3d_category' )->slug . '" class="button-secondary copy-shortcode">Copy</div>';
    
  }

  public static function get_instance()
  {
    
    if ( !isset( self::$instance ) && !( self::$instance instanceof R3D_Post_Type ) ) {
      self::$instance = new R3D_Post_Type();
    }
    
    return self::$instance;
    
  }
}

// Load the post-type class.
$r3d_post_type = R3D_Post_Type::get_instance();

