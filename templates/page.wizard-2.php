<?php
add_thickbox();
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );

$response = wp_remote_get('http://wptakeoff.com/api/takeoff/wizard/?role=' . $_GET['role']);
$data = json_decode($response['body']);
$groups = array_values((array)$data->data);
$all_plugins = array();
?>
<div class="wrap" id="plugin-filter">
 <?php
 $colI = 1;
 foreach($groups as $group) : ?>
  <div id="group<?php echo $group->term_id; ?>" class="advice-card" style="<?php echo $colI != 1 ? 'display:none;' : ''; ?>">
   <h1><?php echo $group->cat_name ?></h1>
   <h2><?php echo $group->description; ?></h2>

   <?php
   $catI = 1;
   foreach($group->categories as $category) :
    $boxId = 'cat' . $colI . '-' . $catI;
   ?>
    <div class="plugin-box" id="<?php echo $boxId; ?>" style="<?php echo $catI != 1 ? 'display:none;' : ''; ?>">
	<div class="header">
	    <?php echo $category->cat_name; ?>
	    <div class="steps">
		<?php
		$i = 1;
		foreach($group->categories as $indicator) : ?>
		  <i class="fa fa-circle<?php echo $i == $catI ? '' : '-o'; ?>" aria-hidden="true"></i>
		<?php
		$i++;
		endforeach; ?>
	    </div>
	</div>
	<div class="plugin-content">
	    <div class="active" style="display:none">
	     <i class="fa fa-check" aria-hidden="true"></i><br />
	     You have <?php echo $category->cat_name; ?> installed!
	    </div>
	    <?php echo $category->description; ?>
	    <?php
	    //create table object
	      $table  = _get_list_table('WP_Plugin_Install_List_Table');
	      require_once(ABSPATH . '/wp-admin/includes/plugin-install.php');
	      $table->items = array();

	       foreach($category->plugins as $plugin) {
		   $object = $plugin->plugin_data;
		   //convert icons to array
		   $object->icons = (array)$object->icons;
		   //add to list
		   $table->items[] = $object;
		   $all_plugins[] = $plugin->post_name;
	       }

	       //display the rows
	       $table->display_rows();

	       //display suggest table
	       $all_suggest_plugins = get_plugins();
	       ?>
	       <div class="suggest-table">
		 <h2><?php echo  sprintf( __('Help us improve WP Take off', 'wp-takeoff'), $category->cat_name); ?></h2>
		 <p class="center"><?php echo sprintf( __('Select the plugin(s) which you already use for %s', 'wp-takeoff'), $category->cat_name); ?></p>
		 <p>
		 <?php foreach($all_suggest_plugins as $folder => $plugin) :
		   $name = explode('/', $folder);
		 ?>
		  <input class="suggest-input" id="<?php echo $boxId . $name[0]; ?>" type="checkbox" data-category="<?php echo $category->term_id; ?>" name="suggest-cat<?php echo $category->term_id; ?>[]" data-title="<?php echo $plugin['Name']; ?>" value="<?php echo $name[0] ?>" /> <label for="<?php echo $boxId . $name[0]; ?>"><?php echo $plugin['Name']; ?></label><br />
		 <?php endforeach; ?>
		 </p>
	       </div>
	</div>

	<div id="action">
	 <?php if($colI == count($groups) && $catI == count($group->categories)) : ?>
		 <a onclick="return wp_check_active_plugins();" href="?page=wp-takeoff-wizard&step=activate&plugins=<?php echo join($all_plugins,','); ?>" id="activate_plugins" class="btn right"><?php _e('Activate installed plugins', 'wp-takeoff'); ?></a>
	 <?php else :
		if($catI == count($group->categories)) :
		  $nextJS = "jQuery('#group" . $group->term_id. "').hide();jQuery('#group" . $groups[$colI]->term_id. "').show();jQuery('#header-steps').text('" . ($colI + 1) . '/' . count($groups). "');"; ?>
	 <?php else :
		  $nextJS = "jQuery('#cat" . $colI. "-" . $catI. "').hide();jQuery('#cat" . $colI. "-" . ($catI+1) . "').show();"; ?>
	 <?php endif; ?>
	       <a href="#" class="right" onclick="jQuery('#<?php echo $boxId; ?> .plugin-card, #<?php echo $boxId; ?> .suggest-table').toggle('fade');"><?php printf( __( 'I already have plugins installed for %s', 'wp-takeoff' ), strtolower($category->cat_name) ); ?></a>

	       <a href="#" onclick="<?php echo $nextJS; ?>wpto_submitSuggestion('<?php echo $boxId ?>');" class="btn right"><?php _e('Next', 'wp-takeoff'); ?></a>
	 <?php endif; ?>

	</div>
    </div>
    <?php
    $catI++;
    endforeach; ?>
  </div>
  <?php
  $colI++;
  endforeach; ?>
</div>
<script>
jQuery(document).ready(function($) {
 $('#header-steps').text('<?php echo '1/' . count($groups); ?>').show();
 wp_takeoff_update_buttons();
});
</script>