<?php
$all_plugins = get_plugins();
$plugin_list = array();
foreach($all_plugins as $plugin => $data) {
 $name = explode('/', $plugin);
 $plugin_list[$name[0]] = array(
			'slug' => $name[0],
			'id' => $plugin
		       );
}
?>
<div class="wrap">
  <h3>Activating plugins...</h3>
</div>
<form method="post" id="bulk-action-form" action="<?php echo admin_url() . 'plugins.php'; ?>" style="display: none;">
 <?php wp_nonce_field('bulk-plugins') ?>
  <input type="hidden" name="_wp_http_referer" value="admin.php?page=wp-takeoff-wizard" />

  <?php
  foreach(explode(',',$_GET['plugins']) as $plugin) :
   if(isset($plugin_list[$plugin])) :
  ?>
   <input type="hidden" name="checked[]" value="<?php echo $plugin_list[$plugin]['id']; ?>">
  <?php
   endif;
  endforeach; ?>

  <input type="hidden" name="action" value="activate-selected" />
</form>
<script>
  document.getElementById('bulk-action-form').submit();
</script>