<?php
//EditorTools全自动采集软件开源接口文件
//更多资源请访问软件官网：www.zzcity.net

@$vercode=''; //此处取值请自行修改
if(!empty($vercode)){
        if ($_POST['vercode']!=$vercode){
         echo("[err]invalid vercode[/err]");
         exit();
         //sdkfjsldfjsldfjsldkfjl
        }
}


ob_start();
//require_once('admin.php');
//以下为替换admin.php代码

if ( ! defined( 'WP_ADMIN' ) ) {
    define( 'WP_ADMIN', true );
}

if ( ! defined('WP_NETWORK_ADMIN') )
    define('WP_NETWORK_ADMIN', false);

if ( ! defined('WP_USER_ADMIN') )
    define('WP_USER_ADMIN', false);

if ( ! WP_NETWORK_ADMIN && ! WP_USER_ADMIN ) {
    define('WP_BLOG_ADMIN', true);
}

if ( isset($_GET['import']) && !defined('WP_LOAD_IMPORTERS') )
    define('WP_LOAD_IMPORTERS', true);

require_once(dirname(dirname(__FILE__)) . '/wp-load.php');

//nocache_headers();

//if ( get_option('db_upgraded') ) {
//    flush_rewrite_rules();
// update_option( 'db_upgraded',  false );
//
// /**
//  * Runs on the next page load after successful upgrade
//  *
//  * @since 2.8
//  */
// do_action('after_db_upgrade');
//} else
if ( get_option('db_version') != $wp_db_version && empty($_POST) ) {
    if ( !is_multisite() ) {
        //wp_redirect(admin_url('upgrade.php?_wp_http_referer=' . urlencode(stripslashes($_SERVER['REQUEST_URI']))));
        echo('[err]db_version error[/err]');
        exit;
//    } elseif ( apply_filters( 'do_mu_upgrade', true ) ) {
//        /**
//         * On really small MU installs run the upgrader every time,
//         * else run it less often to reduce load.
//         *
//         * @since 2.8.4b
//         */
//        $c = get_blog_count();
//        // If 50 or fewer sites, run every time. Else, run "about ten percent" of the time. Shh, don't check that math.
//        if ( $c <= 50 || ( $c > 50 && mt_rand( 0, (int)( $c / 50 ) ) == 1 ) ) {
//            require_once( ABSPATH . WPINC . '/http.php' );
//            $response = wp_remote_get( admin_url( 'upgrade.php?step=1' ), array( 'timeout' => 120, 'httpversion' => '1.1' ) );
//            do_action( 'after_mu_upgrade', $response );
//            unset($response);
//        }
//        unset($c);
    }
}

require_once(ABSPATH . 'wp-admin/includes/admin.php');

//ET增加------
ob_end_clean();
$etuser = wp_signon();
if (is_wp_error($etuser))
{
 echo('login error');
 exit;
}
$_POST['user_ID']=$etuser->ID;


/*
auth_redirect();

// Schedule trash collection
if ( !wp_next_scheduled('wp_scheduled_delete') && !defined('WP_INSTALLING') )
    wp_schedule_event(time(), 'daily', 'wp_scheduled_delete');

set_screen_options();

$date_format = get_option('date_format');
$time_format = get_option('time_format');

wp_enqueue_script( 'common' );

$editing = false;

if ( isset($_GET['page']) ) {
    $plugin_page = wp_unslash( $_GET['page'] );
    $plugin_page = plugin_basename($plugin_page);
}

if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) )
    $typenow = $_REQUEST['post_type'];
else
    $typenow = '';

if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) )
    $taxnow = $_REQUEST['taxonomy'];
else
    $taxnow = '';

if ( WP_NETWORK_ADMIN )
    require(ABSPATH . 'wp-admin/network/menu.php');
elseif ( WP_USER_ADMIN )
    require(ABSPATH . 'wp-admin/user/menu.php');
else
    require(ABSPATH . 'wp-admin/menu.php');

if ( current_user_can( 'manage_options' ) )
    @ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

do_action('admin_init');

if ( isset($plugin_page) ) {
    if ( !empty($typenow) )
        $the_parent = $pagenow . '?post_type=' . $typenow;
    else
        $the_parent = $pagenow;
    if ( ! $page_hook = get_plugin_page_hook($plugin_page, $the_parent) ) {
        $page_hook = get_plugin_page_hook($plugin_page, $plugin_page);
        // backwards compatibility for plugins using add_management_page
        if ( empty( $page_hook ) && 'edit.php' == $pagenow && '' != get_plugin_page_hook($plugin_page, 'tools.php') ) {
            // There could be plugin specific params on the URL, so we need the whole query string
            if ( !empty($_SERVER[ 'QUERY_STRING' ]) )
                $query_string = $_SERVER[ 'QUERY_STRING' ];
            else
                $query_string = 'page=' . $plugin_page;
            wp_redirect( admin_url('tools.php?' . $query_string) );
            exit;
        }
    }
    unset($the_parent);
}

$hook_suffix = '';
if ( isset($page_hook) )
    $hook_suffix = $page_hook;
else if ( isset($plugin_page) )
    $hook_suffix = $plugin_page;
else if ( isset($pagenow) )
    $hook_suffix = $pagenow;

set_current_screen();

// Handle plugin admin pages.
if ( isset($plugin_page) ) {
    if ( $page_hook ) {
        do_action('load-' . $page_hook);
        if (! isset($_GET['noheader']))
            require_once(ABSPATH . 'wp-admin/admin-header.php');

        do_action($page_hook);
    } else {
        if ( validate_file($plugin_page) )
            wp_die(__('Invalid plugin page'));

        if ( !( file_exists(WP_PLUGIN_DIR . "/$plugin_page") && is_file(WP_PLUGIN_DIR . "/$plugin_page") ) && !( file_exists(WPMU_PLUGIN_DIR . "/$plugin_page") && is_file(WPMU_PLUGIN_DIR . "/$plugin_page") ) )
            wp_die(sprintf(__('Cannot load %s.'), htmlentities($plugin_page)));

  do_action('load-' . $plugin_page);

  if (! isset($_GET['noheader']))
   require_once(ABSPATH . 'wp-admin/admin-header.php');

        if ( file_exists(WPMU_PLUGIN_DIR . "/$plugin_page") )
            include(WPMU_PLUGIN_DIR . "/$plugin_page");
        else
            include(WP_PLUGIN_DIR . "/$plugin_page");
    }

 include(ABSPATH . 'wp-admin/admin-footer.php');

 exit();
} else if (isset($_GET['import'])) {

 $importer = $_GET['import'];

 if ( ! current_user_can('import') )
  wp_die(__('You are not allowed to import.'));

    if ( validate_file($importer) ) {
        wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );
        exit;
    }

    if ( ! isset($wp_importers[$importer]) || ! is_callable($wp_importers[$importer][2]) ) {
        wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );
        exit;
    }

    do_action( 'load-importer-' . $importer );

    $parent_file = 'tools.php';
    $submenu_file = 'import.php';
    $title = __('Import');

 if (! isset($_GET['noheader']))
  require_once(ABSPATH . 'wp-admin/admin-header.php');

 require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

 define('WP_IMPORTING', true);

    if ( apply_filters( 'force_filtered_html_on_import', false ) ) {
        kses_init_filters();  // Always filter imported data with kses on multisite.
    }

    call_user_func($wp_importers[$importer][2]);

 include(ABSPATH . 'wp-admin/admin-footer.php');

    // Make sure rules are flushed
    flush_rewrite_rules(false);

    exit();
} else {
    do_action( 'load-' . $pagenow );
    // Backwards compatibility with old load-page-new.php, load-page.php,
    // and load-categories.php actions.
    if ( $typenow == 'page' ) {
        if ( $pagenow == 'post-new.php' )
            do_action( 'load-page-new.php' );
        elseif ( $pagenow == 'post.php' )
            do_action( 'load-page.php' );
    }  elseif ( $pagenow == 'edit-tags.php' ) {
        if ( $taxnow == 'category' )
            do_action( 'load-categories.php' );
        elseif ( $taxnow == 'link_category' )
            do_action( 'load-edit-link-categories.php' );
    }
}

if ( !empty($_REQUEST['action']) )
    do_action('admin_action_' . $_REQUEST['action']);

*/
//admin.php结束------------------

function et_wp_translate_postdata( $update = false, $post_data = null ) {

 if ( empty($post_data) )
  $post_data = &$_POST;

 if ( $update )
  $post_data['ID'] = (int) $post_data['post_ID'];
    $ptype = get_post_type_object( $post_data['post_type'] );

/*    if ( $update && ! current_user_can( $ptype->cap->edit_post, $post_data['ID'] ) ) {
        if ( 'page' == $post_data['post_type'] )
            return new WP_Error( 'edit_others_pages', __( 'You are not allowed to edit pages as this user.' ) );
        else
            return new WP_Error( 'edit_others_posts', __( 'You are not allowed to edit posts as this user.' ) );
    } elseif ( ! $update && ! current_user_can( $ptype->cap->create_posts ) ) {
        if ( 'page' == $post_data['post_type'] )
            return new WP_Error( 'edit_others_pages', __( 'You are not allowed to create pages as this user.' ) );
        else
            return new WP_Error( 'edit_others_posts', __( 'You are not allowed to create posts as this user.' ) );
    }
*/
    if ( isset( $post_data['content'] ) )
        $post_data['post_content'] = $post_data['content'];

    if ( isset( $post_data['excerpt'] ) )
        $post_data['post_excerpt'] = $post_data['excerpt'];

    if ( isset( $post_data['parent_id'] ) )
        $post_data['post_parent'] = (int) $post_data['parent_id'];
 if ( isset($post_data['trackback_url']) )
  $post_data['to_ping'] = $post_data['trackback_url'];
/*    if ( !isset($post_data['user_ID']) )
        $post_data['user_ID'] = $GLOBALS['user_ID'];
*/
 if (!empty ( $post_data['post_author_override'] ) ) {
  $post_data['post_author'] = (int) $post_data['post_author_override'];
 } else {
  if (!empty ( $post_data['post_author'] ) ) {
   $post_data['post_author'] = (int) $post_data['post_author'];
  } else {
   $post_data['post_author'] = (int) $post_data['user_ID'];
  }
 }

/*    if ( ! $update && isset( $post_data['user_ID'] ) && ( $post_data['post_author'] != $post_data['user_ID'] )
         && ! current_user_can( $ptype->cap->edit_others_posts ) ) {

        if ( 'page' == $post_data['post_type'] )
            return new WP_Error( 'edit_others_pages', __( 'You are not allowed to create pages as this user.' ) );
        else
            return new WP_Error( 'edit_others_posts', __( 'You are not allowed to create posts as this user.' ) );
    }
*/
    if ( ! empty( $post_data['post_status'] ) ) {
        $post_data['post_status'] = sanitize_key( $post_data['post_status'] );

        // No longer an auto-draft
        if ( 'auto-draft' === $post_data['post_status'] ) {
            $post_data['post_status'] = 'draft';
        }

        if ( ! get_post_status_object( $post_data['post_status'] ) ) {
            unset( $post_data['post_status'] );
        }
    }
 // What to do based on which button they pressed
// if ( isset($post_data['saveasdraft']) && '' != $post_data['saveasdraft'] )
//  $post_data['post_status'] = 'draft';
// if ( isset($post_data['saveasprivate']) && '' != $post_data['saveasprivate'] )
//  $post_data['post_status'] = 'private';
// if ( isset($post_data['publish']) && ( '' != $post_data['publish'] ) && ( !isset($post_data['post_status']) || $post_data['post_status'] != 'private' ) )
//  $post_data['post_status'] = 'publish';
// if ( isset($post_data['advanced']) && '' != $post_data['advanced'] )
//  $post_data['post_status'] = 'draft';
// if ( isset($post_data['pending']) && '' != $post_data['pending'] )
//  $post_data['post_status'] = 'pending';

 if ( isset( $post_data['ID'] ) )
  $post_id = $post_data['ID'];
 else
  $post_id = false;
 $previous_status = $post_id ? get_post_field( 'post_status', $post_id ) : false;
    if ( isset( $post_data['post_status'] ) && 'private' == $post_data['post_status'] && ! current_user_can( $ptype->cap->publish_posts ) ) {
        $post_data['post_status'] = $previous_status ? $previous_status : 'pending';
    }

    $published_statuses = array( 'publish', 'future' );
 // Posts 'submitted for approval' present are submitted to $_POST the same as if they were being published.
 // Change status from 'publish' to 'pending' if user lacks permissions to publish or to resave published posts.
// if ( isset($post_data['post_status']) && ('publish' == $post_data['post_status'] && !current_user_can( $ptype->cap->publish_posts )) )
//  if ( $previous_status != 'publish' || !current_user_can( 'edit_post', $post_id ) )
//   $post_data['post_status'] = 'pending';

    if ( ! isset( $post_data['post_status'] ) ) {
//        $post_data['post_status'] = 'auto-draft' === $previous_status ? 'draft' : $previous_status;
        $post_data['post_status'] = 'publish';
    }

    if ( isset( $post_data['post_password'] ) && ! current_user_can( $ptype->cap->publish_posts ) ) {
        unset( $post_data['post_password'] );
    }

 if (!isset( $post_data['comment_status'] ))
  $post_data['comment_status'] = 'closed';

 if (!isset( $post_data['ping_status'] ))
  $post_data['ping_status'] = 'closed';

 foreach ( array('aa', 'mm', 'jj', 'hh', 'mn') as $timeunit ) {
  if ( !empty( $post_data['hidden_' . $timeunit] ) && $post_data['hidden_' . $timeunit] != $post_data[$timeunit] ) {
   $post_data['edit_date'] = '1';
   break;
  }
 }

 if ( !empty( $post_data['edit_date'] ) ) {
  $aa = $post_data['aa'];
  $mm = $post_data['mm'];
  $jj = $post_data['jj'];
  $hh = $post_data['hh'];
  $mn = $post_data['mn'];
  $ss = $post_data['ss'];
  $aa = ($aa <= 0 ) ? date('Y') : $aa;
  $mm = ($mm <= 0 ) ? date('n') : $mm;
  $jj = ($jj > 31 ) ? 31 : $jj;
  $jj = ($jj <= 0 ) ? date('j') : $jj;
  $hh = ($hh > 23 ) ? $hh -24 : $hh;
  $mn = ($mn > 59 ) ? $mn -60 : $mn;
  $ss = ($ss > 59 ) ? $ss -60 : $ss;
  $post_data['post_date'] = sprintf( "%04d-%02d-%02d %02d:%02d:%02d", $aa, $mm, $jj, $hh, $mn, $ss );
        $valid_date = wp_checkdate( $mm, $jj, $aa, $post_data['post_date'] );
        if ( !$valid_date ) {
            return new WP_Error( 'invalid_date', __( 'Whoops, the provided date is invalid.' ) );
        }
  $post_data['post_date_gmt'] = get_gmt_from_date( $post_data['post_date'] );
 }

 return $post_data;
}

function etwp_insert_post($postarr, $wp_error = false) {
    //global $wpdb, $user_ID;
	global $user_ID;
	$mydb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);//重新建立数据库连接

    $user_id=$user_ID;
    $defaults = array('post_status' => 'draft', 'post_type' => 'post', 'post_author' => $user_id,
        'ping_status' => get_option('default_ping_status'), 'post_parent' => 0,
        'menu_order' => 0, 'to_ping' =>  '', 'pinged' => '', 'post_password' => '',
        'guid' => '', 'post_content_filtered' => '', 'post_excerpt' => '', 'import_id' => 0,
        'post_content' => '', 'post_title' => '', 'context' => '');

    $postarr = wp_parse_args($postarr, $defaults);

    unset( $postarr[ 'filter' ] );

    $postarr = sanitize_post($postarr, 'raw'); //zzcity modi

    // Are we updating or creating?
    $post_ID = 0;
    $update = false;
    $guid = $postarr['guid'];

    if ( ! empty( $postarr['ID'] ) ) {
        $update = true;

        // Get the post ID and GUID.
        $post_ID = $postarr['ID'];
        $post_before = get_post( $post_ID );
        if ( is_null( $post_before ) ) {
            if ( $wp_error ) {
                return new WP_Error( 'invalid_post', __( 'Invalid post ID.' ) );
            }
            return 0;
        }

        $guid = get_post_field( 'guid', $post_ID );
        $previous_status = get_post_field('post_status', $post_ID );
    } else {
        $previous_status = 'new';
    }

    $post_type = empty( $postarr['post_type'] ) ? 'post' : $postarr['post_type'];

    $post_title = $postarr['post_title'];
    $post_content = $postarr['post_content'];
    $post_excerpt = $postarr['post_excerpt'];
    if ( isset( $postarr['post_name'] ) ) {
        $post_name = $postarr['post_name'];
    }

    $maybe_empty = 'attachment' !== $post_type
        && ! $post_content && ! $post_title && ! $post_excerpt
        && post_type_supports( $post_type, 'editor' )
        && post_type_supports( $post_type, 'title' )
        && post_type_supports( $post_type, 'excerpt' );
    if ( apply_filters( 'wp_insert_post_empty_content', $maybe_empty, $postarr ) ) {
        if ( $wp_error ) {
            return new WP_Error( 'empty_content', __( 'Content, title, and excerpt are empty.' ) );
        } else {
            return 0;
        }
    }

    $post_status = empty( $postarr['post_status'] ) ? 'draft' : $postarr['post_status'];
    if ( 'attachment' === $post_type && ! in_array( $post_status, array( 'inherit', 'private', 'trash' ) ) ) {
        $post_status = 'inherit';
    }

    if ( ! empty( $postarr['post_category'] ) ) {
        // Filter out empty terms.
        $post_category = array_filter( $postarr['post_category'] );
    }

    // Make sure we set a valid category.
    if ( empty( $post_category ) || 0 == count( $post_category ) || ! is_array( $post_category ) ) {
        // 'post' requires at least one category.
        if ( 'post' == $post_type && 'auto-draft' != $post_status ) {
            $post_category = array( get_option('default_category') );
        } else {
            $post_category = array();
        }
    }

    // Don't allow contributors to set the post slug for pending review posts.
    if ( 'pending' == $post_status && !current_user_can( 'publish_posts' ) ) {
        $post_name = '';
    }

    /*
     * Create a valid post name. Drafts and pending posts are allowed to have
     * an empty post name.
     */
    if ( empty($post_name) ) {
        if ( !in_array( $post_status, array( 'draft', 'pending', 'auto-draft' ) ) ) {
            $post_name = sanitize_title($post_title);
        } else {
            $post_name = '';
        }
    } else {
        // On updates, we need to check to see if it's using the old, fixed sanitization context.
        $check_name = sanitize_title( $post_name, '', 'old-save' );
        if ( $update && strtolower( urlencode( $post_name ) ) == $check_name && get_post_field( 'post_name', $post_ID ) == $check_name ) {
            $post_name = $check_name;
        } else { // new post, or slug has changed.
            $post_name = sanitize_title($post_name);
        }
    }

    /*
     * If the post date is empty (due to having been new or a draft) and status
     * is not 'draft' or 'pending', set date to now.
     */
    if ( empty( $postarr['post_date'] ) || '0000-00-00 00:00:00' == $postarr['post_date'] ) {
        $post_date = current_time( 'mysql' );
    } else {
        $post_date = $postarr['post_date'];
    }

    // Validate the date.
    $mm = substr( $post_date, 5, 2 );
    $jj = substr( $post_date, 8, 2 );
    $aa = substr( $post_date, 0, 4 );
    $valid_date = wp_checkdate( $mm, $jj, $aa, $post_date );
    if ( ! $valid_date ) {
        if ( $wp_error ) {
            return new WP_Error( 'invalid_date', __( 'Whoops, the provided date is invalid.' ) );
        } else {
            return 0;
        }
    }

    if ( empty( $postarr['post_date_gmt'] ) || '0000-00-00 00:00:00' == $postarr['post_date_gmt'] ) {
        if ( ! in_array( $post_status, array( 'draft', 'pending', 'auto-draft' ) ) ) {
            $post_date_gmt = get_gmt_from_date( $post_date );
        } else {
            $post_date_gmt = '0000-00-00 00:00:00';
        }
    } else {
        $post_date_gmt = $postarr['post_date_gmt'];
    }

    if ( $update || '0000-00-00 00:00:00' == $post_date ) {
        $post_modified     = current_time( 'mysql' );
        $post_modified_gmt = current_time( 'mysql', 1 );
    } else {
        $post_modified     = $post_date;
        $post_modified_gmt = $post_date_gmt;
    }

    if ( 'attachment' !== $post_type ) {
        if ( 'publish' == $post_status ) {
            $now = gmdate('Y-m-d H:i:59');
            if ( mysql2date('U', $post_date_gmt, false) > mysql2date('U', $now, false) ) {
                $post_status = 'future';
            }
        } elseif( 'future' == $post_status ) {
            $now = gmdate('Y-m-d H:i:59');
            if ( mysql2date('U', $post_date_gmt, false) <= mysql2date('U', $now, false) ) {
                $post_status = 'publish';
            }
        }
    }

    if ( empty( $postarr['comment_status'] ) ) {
        if ( $update ) {
            $comment_status = 'closed';
        } else {
            $comment_status = get_option('default_comment_status');
        }
    } else {
        $comment_status = $postarr['comment_status'];
    }

    // These variables are needed by compact() later.
    $post_content_filtered = $postarr['post_content_filtered'];
    $post_author = empty( $postarr['post_author'] ) ? $user_id : $postarr['post_author'];
    $ping_status = empty( $postarr['ping_status'] ) ? get_option( 'default_ping_status' ) : $postarr['ping_status'];
    $to_ping = isset( $postarr['to_ping'] ) ? sanitize_trackback_urls( $postarr['to_ping'] ) : '';
    $pinged = isset( $postarr['pinged'] ) ? $postarr['pinged'] : '';
    $import_id = isset( $postarr['import_id'] ) ? $postarr['import_id'] : 0;

    /*
     * The 'wp_insert_post_parent' filter expects all variables to be present.
     * Previously, these variables would have already been extracted
     */
    if ( isset( $postarr['menu_order'] ) ) {
        $menu_order = (int) $postarr['menu_order'];
    } else {
        $menu_order = 0;
    }

    $post_password = isset( $postarr['post_password'] ) ? $postarr['post_password'] : '';
    if ( 'private' == $post_status ) {
        $post_password = '';
    }

    if ( isset( $postarr['post_parent'] ) ) {
        $post_parent = (int) $postarr['post_parent'];
    } else {
        $post_parent = 0;
    }

    /**
     * Filter the post parent -- used to check for and prevent hierarchy loops.
     *
     * @since 3.1.0
     *
     * @param int   $post_parent Post parent ID.
     * @param int   $post_ID     Post ID.
     * @param array $new_postarr Array of parsed post data.
     * @param array $postarr     Array of sanitized, but otherwise unmodified post data.
     */
    $post_parent = apply_filters( 'wp_insert_post_parent', $post_parent, $post_ID, compact( array_keys( $postarr ) ), $postarr );

    $post_name = wp_unique_post_slug( $post_name, $post_ID, $post_status, $post_type, $post_parent );

    // Don't unslash.
    $post_mime_type = isset( $postarr['post_mime_type'] ) ? $postarr['post_mime_type'] : '';

    // Expected_slashed (everything!).
    $data = compact( 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_content_filtered', 'post_title', 'post_excerpt', 'post_status', 'post_type', 'comment_status', 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'menu_order', 'post_mime_type', 'guid' );

    if ( 'attachment' === $post_type ) {
        /**
         * Filter attachment post data before it is updated in or added to the database.
         *
         * @since 3.9.0
         *
         * @param array $data    An array of sanitized attachment post data.
         * @param array $postarr An array of unsanitized attachment post data.
         */
        $data = apply_filters( 'wp_insert_attachment_data', $data, $postarr );
    } else {
        /**
         * Filter slashed post data just before it is inserted into the database.
         *
         * @since 2.7.0
         *
         * @param array $data    An array of slashed post data.
         * @param array $postarr An array of sanitized, but otherwise unmodified post data.
         */
        $data = apply_filters( 'wp_insert_post_data', $data, $postarr );
    }
    $data = wp_unslash( $data );
    $where = array( 'ID' => $post_ID );

    if ( $update ) {
        do_action( 'pre_post_update', $post_ID, $data );
        if ( false === $wpdb->update( $wpdb->posts, $data, $where ) ) {
            if ( $wp_error ) {
                return new WP_Error('db_update_error', __('Could not update post in the database'), $wpdb->last_error);
            } else {
                return 0;
            }
        }
    } else {
        // If there is a suggested ID, use it if not already present.
        if ( ! empty( $import_id ) ) {
            $import_id = (int) $import_id;
            if ( ! $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d", $import_id) ) ) {
                $data['ID'] = $import_id;
            }
        }
        if ( false === $wpdb->insert( $wpdb->posts, $data ) ) {
            if ( $wp_error ) {
                return new WP_Error('db_insert_error', __('Could not insert post into the database'), $wpdb->last_error);
            } else {
                return 0;
            }
        }
        $post_ID = (int) $wpdb->insert_id;

        // Use the newly generated $post_ID.
        $where = array( 'ID' => $post_ID );
    }

    if ( empty( $data['post_name'] ) && ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) ) {
        $data['post_name'] = sanitize_title( $data['post_title'], $post_ID );
        $wpdb->update( $wpdb->posts, array( 'post_name' => $data['post_name'] ), $where );
    }

    if ( is_object_in_taxonomy( $post_type, 'category' ) ) {
        wp_set_post_categories( $post_ID, $post_category );
    }

    if ( isset( $postarr['tags_input'] ) && is_object_in_taxonomy( $post_type, 'post_tag' ) ) {
        wp_set_post_tags( $post_ID, $postarr['tags_input'] );
    }

    // New-style support for all custom taxonomies.
    if ( ! empty( $postarr['tax_input'] ) ) {
        foreach ( $postarr['tax_input'] as $taxonomy => $tags ) {
            $taxonomy_obj = get_taxonomy($taxonomy);
            // array = hierarchical, string = non-hierarchical.
            if ( is_array( $tags ) ) {
                $tags = array_filter($tags);
            }
            if ( current_user_can( $taxonomy_obj->cap->assign_terms ) ) {
                wp_set_post_terms( $post_ID, $tags, $taxonomy );
            }
        }
    }

    $current_guid = get_post_field( 'guid', $post_ID );

    // Set GUID.
    if ( ! $update && '' == $current_guid ) {
        $wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $post_ID ) ), $where );
    }

    if ( 'attachment' === $postarr['post_type'] ) {
        if ( ! empty( $postarr['file'] ) ) {
            update_attached_file( $post_ID, $postarr['file'] );
        }

        if ( ! empty( $postarr['context'] ) ) {
            add_post_meta( $post_ID, '_wp_attachment_context', $postarr['context'], true );
        }
    }

    clean_post_cache( $post_ID );

    $post = get_post( $post_ID );
    add_post_meta( $post_ID, 'views', rand(200, 500) );

    if ( ! empty( $postarr['page_template'] ) && 'page' == $data['post_type'] ) {
        $post->page_template = $postarr['page_template'];
        $page_templates = wp_get_theme()->get_page_templates( $post );
        if ( 'default' != $postarr['page_template'] && ! isset( $page_templates[ $postarr['page_template'] ] ) ) {
            if ( $wp_error ) {
                return new WP_Error('invalid_page_template', __('The page template is invalid.'));
            } else {
                return 0;
            }
        }
        update_post_meta( $post_ID, '_wp_page_template', $postarr['page_template'] );
    }

    if ( 'attachment' !== $postarr['post_type'] ) {
        wp_transition_post_status( $data['post_status'], $previous_status, $post );
    } else {
        if ( $update ) {
            /**
             * Fires once an existing attachment has been updated.
             *
             * @since 2.0.0
             *
             * @param int $post_ID Attachment ID.
             */
            do_action( 'edit_attachment', $post_ID );
        } else {

            /**
             * Fires once an attachment has been added.
             *
             * @since 2.0.0
             *
             * @param int $post_ID Attachment ID.
             */
            do_action( 'add_attachment', $post_ID );
        }

        return $post_ID;
    }

    if ( $update ) {
        /**
         * Fires once an existing post has been updated.
         *
         * @since 1.2.0
         *
         * @param int     $post_ID Post ID.
         * @param WP_Post $post    Post object.
         */
        do_action( 'edit_post', $post_ID, $post );
        $post_after = get_post($post_ID);

        /**
         * Fires once an existing post has been updated.
         *
         * @since 3.0.0
         *
         * @param int     $post_ID      Post ID.
         * @param WP_Post $post_after   Post object following the update.
         * @param WP_Post $post_before  Post object before the update.
         */
        do_action( 'post_updated', $post_ID, $post_after, $post_before);
    }

    /**
     * Fires once a post has been saved.
     *
     * The dynamic portion of the hook name, $post->post_type, refers to
     * the post type slug.
     *
     * @since 3.7.0
     *
     * @param int     $post_ID Post ID.
     * @param WP_Post $post    Post object.
     * @param bool    $update  Whether this is an existing post being updated or not.
     */
    do_action( "save_post_{$post->post_type}", $post_ID, $post, $update );

    /**
     * Fires once a post has been saved.
     *
     * @since 1.5.0
     *
     * @param int     $post_ID Post ID.
     * @param WP_Post $post    Post object.
     * @param bool    $update  Whether this is an existing post being updated or not.
     */
    do_action( 'save_post', $post_ID, $post, $update );

    /**
     * Fires once a post has been saved.
     *
     * @since 2.0.0
     *
     * @param int     $post_ID Post ID.
     * @param WP_Post $post    Post object.
     * @param bool    $update  Whether this is an existing post being updated or not.
     */
    do_action( 'wp_insert_post', $post_ID, $post, $update );

    return $post_ID;
}


function etwp_write_post() {
 global $user_ID;

/*     if ( isset($_POST['post_type']) )
        $ptype = get_post_type_object($_POST['post_type']);
    else
        $ptype = get_post_type_object('post');

    if ( !current_user_can( $ptype->cap->edit_posts ) ) {
        if ( 'page' == $ptype->name )
            return new WP_Error( 'edit_pages', __( 'You are not allowed to create pages on this site.' ) );
        else
            return new WP_Error( 'edit_posts', __( 'You are not allowed to create posts or drafts on this site.' ) );
    }
*/
    $_POST['post_mime_type'] = '';

    // Clear out any data in internal vars.
    unset( $_POST['filter'] );

/*    // Edit don't write if we have a post id.
    if ( isset( $_POST['post_ID'] ) )
        return edit_post();
*/

 if (!isset( $_POST['publish'] ))
  $_POST['publish'] = 'publish';

 if (!isset( $_POST['visibility'] ))
  $_POST['visibility'] = 'public';

 if (empty( $_POST['post_status'] ))
  $_POST['post_status'] = 'publish';

 if (!isset( $_POST['comment_status'] ))
  $_POST['comment_status'] = 'open';


 if ( isset($_POST['visibility']) ) {
  switch ( $_POST['visibility'] ) {
   case 'public' :
    $_POST['post_password'] = '';
    break;
   case 'password' :
    unset( $_POST['sticky'] );
    break;
   case 'private' :
    $_POST['post_status'] = 'private';
    $_POST['post_password'] = '';
    unset( $_POST['sticky'] );
    break;
  }
 }

    $translated = et_wp_translate_postdata( false );
    if ( is_wp_error($translated) )
        return $translated;

 // Create the post.
 $post_ID = etwp_insert_post( $_POST );
 if ( is_wp_error( $post_ID ) )
  return $post_ID;

 if ( empty($post_ID) )
  return 0;

 //add_meta( $post_ID );
 //zzcity add
 if ( isset($_POST['meta']) && $_POST['meta'] ) {
  foreach ( $_POST['meta'] as $key => $value )
     add_post_meta( $post_ID, $value['key'], $value['value']);
 }
 add_post_meta( $post_ID, '_edit_last', $GLOBALS['current_user']->ID );

 // Now that we have an ID we can fix any attachment anchor hrefs
 _fix_attachment_links( $post_ID );


    wp_set_post_lock( $post_ID );

 return $post_ID;
}

function etwrite_post() {
 $result = etwp_write_post();
 if( is_wp_error( $result ) ){
  wp_die( $result->get_error_message() );
    }
    else
  return $result;
}




//$parent_file = 'edit.php';
//$submenu_file = 'edit.php';

$_POST['action']='post';
$_POST['post_type']='post';
$_POST['advanced_view']=1;
$_POST['post_pingback']=1;

$tz = get_option('timezone_string');
if ( $tz ) {
    date_default_timezone_set( $tz );
}

$zztime=0;
if (isset($_POST['zzdelay']) ) {
    $zzdelay=(int)$_POST['zzdelay'];
    $zztime=time()+$zzdelay*60*60;
}

if (!empty($_POST['zztime']) ) {
    $zztime=strtotime($_POST['zztime']);
}
if($zztime>0){
    $_POST['hidden_aa']=date('Y');
    $_POST['hidden_mm']=date('n');
    $_POST['hidden_jj']=date('j');
    $_POST['hidden_hh']=date('H');
    $_POST['hidden_mn']=date('i');
    $_POST['hidden_ss']=date('s');

//    $zztime=date('Y-m-d H:i:s',$zztime);
//    $zztime=get_gmt_from_date($zztime);
//    $zztime=strtotime($zztime);
    $_POST['aa']=date('Y',$zztime);
    $_POST['mm']=date('n',$zztime);
    $_POST['jj']=date('j',$zztime);
    $_POST['hh']=date('H',$zztime);
    $_POST['mn']=date('i',$zztime);
    $_POST['ss']=date('s',$zztime);
}

wp_reset_vars(array('action', 'safe_mode', 'withcomments', 'posts', 'content', 'edited_post_title', 'comment_error', 'profile', 'trackback_url', 'excerpt', 'showcomments', 'commentstart', 'commentend', 'commentorder'));


$post_ID = etwrite_post() ;

if ( '1' == $_POST['sticky'] )
    stick_post( $post_ID );

// Post Formats
if ( isset( $_POST['post_format'] ) ) {
    if ( $_POST['post_format']!=='0'  ){
        set_post_format( $post_ID, $_POST['post_format'] );
        }
}


if (is_numeric($post_ID)){
 echo('[reply]comment_post_ID='.$post_ID.'[/reply]');}
else{
 echo('[err]POST failure[/err]');
 exit;
}
$attachs=explode(',',$_POST['etattachs']);
$flagthumb=0;
foreach($attachs as $aindex => $afile){
    if(trim($afile)!=''){
        $thumbnail_id = et_media_handle_upload($afile, $post_ID);

        if($_POST['litpic']==$afile){
            set_post_thumbnail( $post_ID, $thumbnail_id );
        }else{
            $aext=zzfileext($afile);
            if((zzis_image_ext($aext))&&($flagthumb==0)){
                set_post_thumbnail( $post_ID, $thumbnail_id );
                $flagthumb=1;
            }
        }
    }
}


//附件

function zzfileext($filename) {
    return strtolower(substr(strrchr($filename, '.'), 1, 10));
}

function zzis_image_ext($ext) {
    static $imgext  = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
    return in_array($ext, $imgext) ? true : false;
}

function mime($file) {
    $mime = '';
    if (!file_exists($file)) {
        return '';
    }
        if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mime = finfo_file($finfo, $file);
                finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
                $mime = mime_content_type($file);
        }    elseif (function_exists('exif_imagetype')){
                $mime = image_type_to_mime_type(exif_imagetype($file));
        }
    return $mime;
}

function et_media_handle_upload($imageurl, $post_id, $post_data = array(), $overrides = array( 'test_form' => false )) {

    $time = current_time('mysql');
/*    if ( $post = get_post($post_id) ) {
        if ( substr( $post->post_date, 0, 4 ) > 0 )
            $time = $post->post_date;
    }

*/
    $temp = explode('/', $imageurl);

    $name =  trim($temp[count($temp)-1]);

/*    $name = $_FILES[$file_id]['name'];
    $file = wp_handle_upload($_FILES[$file_id], $overrides, $time);

    if ( isset($file['error']) )
        return new WP_Error( 'upload_error', $file['error'] );

    $name_parts = pathinfo($name);
    $name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );
*/
    //$url = $file['url'];
    $url = 'http://'.$_SERVER['HTTP_HOST'].$imageurl;
//    $type = $file['type'];
    $path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
    $path=$path.'/..'.$imageurl;
    $type = mime($path);
    if($type=='')$type='image/*';

    //$file = $file['file'];
    $file = $path;
    $title = $name;
    $content = '';

    // use image exif/iptc data for title and caption defaults if possible
    if ( $image_meta = @wp_read_image_metadata($file) ) {
        if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) )
            $title = $image_meta['title'];
        if ( trim( $image_meta['caption'] ) )
            $content = $image_meta['caption'];
    }

    // Construct the attachment array
    $attachment = array_merge( array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => $post_id,
        'post_title' => $title,
        'post_content' => $content,
    ), $post_data );

    // This should never be set as it would then overwrite an existing attachment.
    if ( isset( $attachment['ID'] ) )
        unset( $attachment['ID'] );

    // Save the data
    $id = wp_insert_attachment($attachment, $file, $post_id);
    if ( !is_wp_error($id) ) {
        wp_update_attachment_metadata( $id,wp_generate_attachment_metadata( $id, $file ));
    }

    return $id;

}


?>
