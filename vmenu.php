<?php
/**
 * Plugin Name: Vertical menu
 * Plugin URI: http://pctricks.ir/
 * Description: This Plugin Show All Categories In Vertical menu Widget.
 * Version: 1.0.0
 * Author: <a href="http://pctricks.ir/">Mostafa Shiraali</a>
 * Author URI: http://pctricks.ir/
 * License: A "Slug" license name e.g. GPL2
 */
 function vmenu_active()
 {
 add_option('vmw_dir',"rtl","Menu Direction");
 add_option('vmw_theme',"defualt","Menu Theme");
 }
 function vmenu_init()
 {
 register_setting('pctriks_vmw_opt','vmw_dir');
 register_setting('pctriks_vmw_opt','vmw_theme');

 }
  function vmenu_deactivate()
 {
 delete_option('vmw_dir');
 delete_option('vmw_theme');
 }
 if ( ! function_exists ( 'vmenu_lang_init' ) ) {
 function vmenu_lang_init()
 {
   load_plugin_textdomain( 'vmenu', false,dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
 }
 }
 function vmenu_menu() {
	add_options_page(__("Vertical menu","vmenu"), __("Vertical menu","vmenu"), 10, __FILE__,"vmenu_display_options");
}
function vmenu_display_options()
{
?>
	<div class="wrap">
	<h2><?php _e("Vertical menu Option","vmenu")?></h2>        
	<form method="post" action="options.php">
	<?php settings_fields('pctriks_vmw_opt'); ?>
	<table class="form-table">
	    <tr valign="top">
        <th scope="row"><label><?php _e("Menu Direction","vmenu");?></label></th>
		<td><span class="description"><?php _e("Select Menu Direction","vmenu")?></span></td>
		<td>
		<select name="vmw_dir">
		<option value="rtl" <?php if ( get_option('vmw_dir') == "rtl" ) echo 'selected="selected"'; ?>>Right To Left</option>
		<option value="ltr" <?php if ( get_option('vmw_dir') == "ltr" ) echo 'selected="selected"'; ?>>Left To Right</option>
		</select>
		</td>
        </tr>	
		<tr valign="top">
        <th scope="row"><label><?php _e("Menu Theme","vmenu");?></label></th>
		<td><span class="description"><?php _e("Select Menu theme","vmenu")?></span></td>
		<td>
		<select name="vmw_theme">
		<option value="defualt" <?php if ( get_option('vmw_theme') == "defualt" ) echo 'selected="selected"'; ?>>defualt</option>
		<option value="blor" <?php if ( get_option('vmw_theme') == "blor" ) echo 'selected="selected"'; ?>>Black-Orange</option>
		<option value="blgr" <?php if ( get_option('vmw_theme') == "blgr" ) echo 'selected="selected"'; ?>>Black-Green</option>
		<option value="blblu" <?php if ( get_option('vmw_theme') == "blblu" ) echo 'selected="selected"'; ?>>Black-Blue</option>
		<option value="blye" <?php if ( get_option('vmw_theme') == "blye" ) echo 'selected="selected"'; ?>>Black-Yellow</option>
		<option value="blpi" <?php if ( get_option('vmw_theme') == "blpi" ) echo 'selected="selected"'; ?>>Black-Pink</option>
		<option value="cgb" <?php if ( get_option('vmw_theme') == "cgb" ) echo 'selected="selected"'; ?>>CSS3-Gray-Blue</option>
		<option value="cggr" <?php if ( get_option('vmw_theme') == "cggr" ) echo 'selected="selected"'; ?>>CSS3-Gray-green</option>
		<option value="cgye" <?php if ( get_option('vmw_theme') == "cgye" ) echo 'selected="selected"'; ?>>CSS3-Gray-Yellow</option>
		<option value="cgr" <?php if ( get_option('vmw_theme') == "cgr" ) echo 'selected="selected"'; ?>>CSS3-Gray-red</option>
		<option value="cbgr" <?php if ( get_option('vmw_theme') == "cbgr" ) echo 'selected="selected"'; ?>>CSS3-Black-Green</option>
		<option value="turq" <?php if ( get_option('vmw_theme') == "turq" ) echo 'selected="selected"'; ?>>Turquoise</option>
		</select>
		</td>
        </tr>	
	</table>
	<p class="submit">
	<input type="submit" name="Submit" value="Save" />
	</p>
		</form>
	</div>
<?php
}
/************************ SUB MENU *****************************/

function sublevel($catid)
{
global $wpdb;
$subs='';
$cat_tax = $wpdb->get_results("SELECT $wpdb->term_taxonomy.term_id,$wpdb->terms.name
									FROM $wpdb->term_taxonomy
									INNER JOIN $wpdb->terms
									WHERE $wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.parent = $catid
									ORDER BY $wpdb->terms.name ASC");
			foreach($cat_tax as $cat)
			{
			$catlink=esc_url(get_category_link($cat->term_id));
			$parent = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' AND parent = $cat->term_id");
			if($parent)
			{
			$subs .='<li>';
			$subs .='<a href="'.$catlink.'">'.$cat->name.'</a><ul class="sub-menu">';
			$subs .=sublevel($cat->term_id);
			$subs .='</ul></li>';
			$level=$level+1;
			}
			else
			{
			$subs .='<li><a href="'.$catlink.'">'.$cat->name.'</a></li>';
			$level=$level+1;
			}
			}


return $subs;
}

/************************ SUB MENU *****************************/

function pcvmw_widget()
{
global $wpdb;
$menu='';
$menu .='<div id="navigation"><ul>';
 $cat_tax = $wpdb->get_results("SELECT $wpdb->term_taxonomy.term_id,$wpdb->terms.name
										FROM $wpdb->term_taxonomy
										INNER JOIN $wpdb->terms
										WHERE $wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.parent = '0'
										ORDER BY $wpdb->terms.name ASC");	
			foreach ($cat_tax as $cat)
			{
			$catlink=esc_url(get_category_link($cat->term_id));
			$parent = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' AND parent = $cat->term_id");
			if($parent)
			{
			$menu .='<li><a href="'.$catlink.'">'.$cat->name.'</a><ul class="sub-menu">'.sublevel($cat->term_id).'</ul></li>';
			}
			else
			{
			$menu .='<li><a href="'.$catlink.'">'.$cat->name.'</a></li>';
			}
			
			}



echo '<center>'.$menu.'</center>';
}
function widget_pctrick_vmenu_init()
{
	function vmenu_widget($args)
	{
		extract($args);
		$options = get_option('vmenu_widget');
		$title = $options['title'];
		echo $before_widget;
		echo $before_title . $title . $after_title;
		pcvmw_widget();
		echo $after_widget;
	}
	function vmenu_widget_control()
	{
			$options = get_option('vmenu_widget');
		if ( !is_array($options) )
			$options = array('title'=>'');
		if ( $_POST['pctrick_vmenu_title_submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['pctrick_vmenu_title']));
			update_option('vmenu_widget', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		?>
		<p style="text-align:right; direction:rtl">
		<label for="pctrick_vmenu_title"><?php _e("Title :","vmenu");?> <input style="width: 200px;" id="pctrick_vmenu_title" name="pctrick_vmenu_title" type="text" value="<?php echo $title; ?>" /></label>
		</p>
		<input type="hidden" id="pctrick_vmenu_title_submit" name="pctrick_vmenu_title_submit" value="1" />
		<?php
		}
	wp_register_sidebar_widget(20000,__("Vertical Menu Widget","vmenu"),'vmenu_widget');
	wp_register_widget_control(20000,__("Vertical Menu Widget","vmenu"), 'vmenu_widget_control');		
}
function vmenu_script()
{
$vmw_dir=get_option('vmw_dir');
$vmw_theme=get_option('vmw_theme');
	if($vmw_dir=="rtl")
	{
	wp_enqueue_style('vmenu', plugins_url( 'css/'.$vmw_theme.'_rtl.css', __FILE__ ));
	}
	else if($vmw_dir=="ltr")
	{
	wp_enqueue_style('vmenu',plugins_url( 'css/'.$vmw_theme.'.css', __FILE__  ));
	}
}
add_action('admin_init', 'vmenu_init' );
add_action('init', 'vmenu_lang_init');
add_action('admin_init', 'vmenu_lang_init');
add_action('admin_menu', 'vmenu_menu');
add_action('widgets_init', 'widget_pctrick_vmenu_init');
add_action( 'wp_enqueue_scripts', 'vmenu_script' );
register_activation_hook( __FILE__, 'vmenu_active' );
register_deactivation_hook( __FILE__, 'vmenu_deactivate' );

?>