<?php
/*
Plugin Name: Peoples Form 
Description: a plugin to create awesome Form
Version: 1.0
Author: Media6 Technologies
Author URI: http://www.media6technologies.com/
License: GPL2
*/ 
	function my_load_scripts() {
		wp_register_script( 'custom_js', plugin_dir_url( __FILE__ ) .'js/custom.js', array( 'jquery' ),'', true );
		wp_enqueue_script ( 'custom_js' );
		wp_enqueue_script( 'jquery' );
		}	
	if(is_admin() ){
		add_action('admin_enqueue_scripts', 'my_load_scripts', 10, 2);
		}	
	global $pf_db_version;
	$pf_db_version = '1.0';
	function pf_install() {
			global $wpdb;
			global $pf_db_version;
			$table_name = $wpdb->prefix . 'people_form';
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				people_id mediumint(11) NOT NULL AUTO_INCREMENT,
				people_name varchar(30) NOT NULL,
				PRIMARY KEY  (people_id )
			) $charset_collate;";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			add_option( 'pf_db_version', $pf_db_version );
		}
	  register_activation_hook( __FILE__, 'pf_install' );
		
	function peoples_form_menu(){
		    add_menu_page( 'a simple Form', 'Peoples Form', 'manage_options', 'peoples-form', 'form_options' );	
		} 	
	function form_options(){
			global $wpdb; 
			
			$sql = "SELECT * FROM wp_people_form";
			if(isset($_REQUEST["tex_name"])){
				$people_name = $_REQUEST["tex_name"];
				$sql.=" WHERE people_name LIKE '".$people_name."%'";
				}
				$result = $wpdb->get_results($sql);
				?>
			<form action="" method="post" name="table_form" id="table_form" />
            <div class="alignleft actions">
            <input type="text" name="tex_name" >
            <input type="submit" value="Filter" name="filter" class="button" />
            </div>
            <table class="wp-list-table widefat fixed striped posts">
            <thead>
	        <tr><th scope="col" id='categories' class='manage-column column-categories'>Name</th><th scope="col" id='author' class='manage-column column-author'>location</th><th scope="col" id='categories' class='manage-column column-categories'>Email</th><th scope="col" id='categories' class='manage-column column-categories'>Date</th></tr>
	        </thead>
            <tbody id="the-list">
            <?php
			foreach ( $result as $print )   {?>
            <tr id="post-671" class="iedit author-self level-0 post-671 type-post status-publish format-standard hentry category-testing">
			<td class="title column-title has-row-actions column-primary page-title" data-colname="Title"><strong><a class="row-title" href="javascript:void(0);" aria-label="&#8220;people form&#8221; (Edit)"><?php echo $print->people_name;?></a></strong>
<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
<div class="hidden">
<div class="row-actions"><span class='edit'><a href="javascript:void(0);" aria-label="Edit &#8220;people form&#8221;">Edit</a> | </span><span class='trash'><a href="javascript:void(0);" class="submitdelete" aria-label="Move &#8220;people form&#8221; to the Trash">Delete</a></span></div></div>
          <!-- <a href="javascript:void(0);" onclick="fnedit(<?php echo $print->people_id; ?>)">Edit</a>
		    <a  href="javascript:void(0);" class="opt_show1" id="opt_show1" onclick="fndelete(<?php echo $print->people_id; ?>)" >Delete</a> -->
		    </td></tr>
		   <?php } ?>
            </form> 
         <?php }
		add_action('admin_menu','peoples_form_menu');
		
			
       	function peoples_form_sub_menu(){
			add_submenu_page( 'peoples-form', 'a simple Form', 'Peoples Form Options', 'manage_options', 'sub-peoples-form', 'sub_form_options'); 
		}
			
	function sub_form_options(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'people_form';
		$id=$_GET["edit_id"];
		if(isset($_GET["edit_id"])){
			$id=$_GET["edit_id"];
			$result = $wpdb->get_results ( "SELECT * FROM wp_people_form WHERE people_id=".$id ); ?>	
			<form action="" method="post" name="people_edit_form" >
            <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $result[0]->people_id; ?>">
		    <input type="text" id="pf_edit_name" name="pf_edit_name" value="<?php echo $result[0]->people_name; ?>" required /><br/>
		    <span class="input_error"> </span>
		    <input id="edit_submit" type="button" name="pf_edit_submit" value="submit" >
		    </form>
			<?php }
	   }	
			add_action('admin_menu','peoples_form_sub_menu');
		
	 function peoples_form() {
		 echo 'Peoples Form';
		 echo'<hr>'.'<br/>';
		 echo '<form action="" method="post" name="people_form" >';
		 echo '<input type="text" id="pf_name" name="pf_name" placeholder="Your Name*" required novalidate/>';
		 echo '<span class="input_error"> </span>'.'<br/>';
		 echo '<input id="submit" type="submit" name="pf_submit" value="submit" >';
		 echo '</form>';
		 echo '<br/>'.'<hr>';	 
	  }  
	
	function pf_func( ) {	
	      return  peoples_form();
      }
		add_shortcode( 'peoples-form', 'pf_func' );	   
  
	function pf_insert_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'people_form';
		if(isset($_POST['pf_submit'])){
			if(isset($_POST['pf_name'])&&!empty($_POST['pf_name'])){
			$pf_name = stripslashes_deep($_POST['pf_name']);
			$wpdb->insert( 
			$table_name, 
			array( 'people_name' => $pf_name ) 
		    );
		  }
		} 
	  }   
	  add_action('init', 'pf_insert_data');
	  
	add_action('wp_ajax_delete_action', 'delete_action');
	add_action('wp_ajax_nopriv_delete_action', 'delete_action');
	function delete_action(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'people_form';
		$delete = $wpdb->delete( $table_name , array( 'people_id' => $_POST['user_id'] ), array( '%d' ) );
		if($delete == 1){
			echo "success";
		}
		wp_die();
	}
	add_action('wp_ajax_edit_action', 'edit_action');
	add_action('wp_ajax_nopriv_edit_action', 'edit_action');
	function edit_action(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'people_form';
		$edit = $wpdb->update( 
			$table_name, 
			array( 
				'people_name' => $_POST['edit_name']		
			), 
			array( 'people_id' => $_POST['edit_id'] ), 
			array( '%s' ), 
			array( '%d' ) 
		);
		if($edit == 1){
			echo "success";
		}
		wp_die();
	}
    
	  
	add_action('wp_head', 'add_ajaxurl_for_theme');
	function add_ajaxurl_for_theme() { ?>
		<script type="text/javascript">
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script><?php	
	}
		
?>