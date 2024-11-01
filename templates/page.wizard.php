<div class="wrap" id="plugin-filter">
    <div id="step1">
	<h1><?php _e('Improve your wordpress website with WP Take off', 'wp-takeoff'); ?></h1>
	<h2><?php _e('Based on your skills and website purpose WP Take off recommends and helps you install the best WordPress plugins.', 'wp-takeoff'); ?></h2>
	<div class="column-1">
	    <div class="header">
		<span class="header-text">
		    <?php _e('Wordpress skill level', 'wp-takeoff'); ?>
		</span>
	    </div>
	    <p><?php _e(' Configuring and installing a WordPress website is a time consuming job. That\'s why we created an easy step by step wizard which helps you selecting the best plugins for your WordPress website. Please select your WordPress level.', 'wp-takeoff'); ?></p>
	    <?php foreach(array(15 => 'Newbie',17 => 'Talented', 18 => 'Skilled', 19 => 'Expert') as $t => $skill) : ?>
		<p><label><input type="radio" class="check-role"  value="<?php echo $t; ?>"  name="role"  onclick="checkStep1Button();jQuery('.skills').hide();jQuery('#skill<?php echo $t; ?>').show();" value="<?php echo $skill; ?>"> <?php _e($skill, 'wp-takeoff'); ?></label> <a href="#" onclick="alert(jQuery('#skill<?php echo $t; ?>').text());return false;"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></p>
	    <?php endforeach; ?>

	    <div class="skill-container">
		<?php
		foreach(array(15 => 'Newbie',17 => 'Talented', 18 => 'Skilled', 19 => 'Expert') as $t => $skill) :
		    ?>
		<?php switch($t) {
		    case 15: ?>
			<div id="skill<?php echo $t; ?>" style="display: none;" class="skills"><span class="text">This is the first time you are working with a wordpress website!</span></div>
			<?php $t++; ?>
		    <?php break; case 17: ?>
			<div id="skill<?php echo $t; ?>" style="display: none;" class="skills"><span class="text">You know how to install a plugin and change some settings. But that's about it.</span></div>
		    <?php break; case 18: ?>
			<div id="skill<?php echo $t; ?>" style="display: none;" class="skills"><span class="text">You are familiar with wordpress and have a good understanding of it's possibilities. You know how to install themes and plugins, and are not afraid to get your hands dirty.</span></div>
		    <?php break; case 19: ?>
			<div id="skill<?php echo $t; ?>" style="display: none;" class="skills"><span class="text">Wordpress has very little secrets for you. You can also read and write code. You're a real expert!</span></div>
		    <?php break; ?>
		<?php } ?>
	    <?php endforeach; ?>
	    </div>

	</div>
<?php /*
	<div class="column-2">
	    <div class="header">
		<?php _e('Website purpose', 'wp-takeoff'); ?>
	    </div>
	    <p><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sodales turpis pellentesque blandit cursus. ', 'wp-takeoff'); ?></p>
	    <?php
	    $t = 20;
	    foreach(array('Blogging','E-commerce','Corperate / Campaign') as $skill) :
		    $t++; ?>
		<p><label><input type="checkbox" class="check-scenario" name="scenario[]" value="<?php echo $t; ?>" onclick="checkStep1Button();" value="<?php echo $skill; ?>"> <?php _e($skill, 'wp-takeoff'); ?></label> <a href="#" onclick="alert(jQuery('#scenario<?php echo $t; ?>').text());return false;"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></p>
		<?php switch($t) {
		    case 21: ?>
			<div id="scenario<?php echo $t; ?>" style="display: none;">The main purpose of your Wordpress website is a weblog or magazine.</div>
		    <?php break; case 22: ?>
			<div id="scenario<?php echo $t; ?>" style="display: none;">The main purpose of your Wordpress website is a webshop or other ecommerce driven website.</div>
		    <?php break; case 23: ?>
			<div id="scenario<?php echo $t; ?>" style="display: none;">The main purpose of your Wordpress website is a corporate website or a one-pager.</div>
		    <?php break; ?>
		<?php } ?>
	    <?php endforeach; ?>
	</div>
*/ ?>
	<div id="action">
	    <h2><?php _e('Fasten your seatbelts and get ready to Take off!', 'wp-takeoff'); ?></h2>
	    <a href="#" onclick="goToStep2();" class="btn disabled">Start my advice</a>
	</div>
    </div>
</div>
