<?php
/**
 * @package wp-content/plugins/google-plus-activity
*/
/*
Plugin Name: Google Plus Activity
Plugin URI: http://bigbluegroup.net/
Description: Thanks for installing Google Plus Activity
Version: 0.1
Author: Big Blue Group
Author URI: http://bigbluegroup.net/
*/

class GooglePlusActivity extends WP_Widget{
    public function __construct() {
	add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles_google_plus_activity' ) );
        $params = array(
            'description' => 'Thanks for installing Google Plus Activity.',
            'name' => 'Google Plus Activity'
        );
        parent::__construct('GooglePlusActivity','',$params);
    }
    public function register_plugin_styles_google_plus_activity() {
        wp_register_style( 'GooglePlusActivityStyle', plugins_url( 'google-plus-activity/assets/gplus.css' ) );
        wp_enqueue_style( 'GooglePlusActivityStyle' );
    }
    
    public function form($instance) {
        extract($instance);
        
?>
<p>
    <label for="<?php echo $this->get_field_id('title');?>">Title : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('title');?>"
	name="<?php echo $this->get_field_name('title');?>"
        value="<?php echo !empty($title) ? $title : "Google Plus Activity"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('userid');?>">Google Plus User ID : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('userid');?>"
	name="<?php echo $this->get_field_name('userid');?>"
        value="<?php echo !empty($userid) ? $userid : "116899029375914044550"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('gplus_api');?>">Google Plus API : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('gplus_api');?>"
	name="<?php echo $this->get_field_name('gplus_api');?>"
        value="<?php echo !empty($gplus_api) ? $gplus_api : "AIzaSyCPtQ1XMWetDkVw5XuzNnVXpf8ezDtH9So"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('count');?>">Max number of Google Plus Posts : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('count');?>"
	name="<?php echo $this->get_field_name('count');?>"
        value="<?php echo !empty($count) ? $count : "5"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('maxwidth');?>">Maximum Width : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('maxwidth');?>"
	name="<?php echo $this->get_field_name('maxwidth');?>"
        value="<?php echo !empty($maxwidth) ? $maxwidth : "500px"; ?>" />
</p>
<?php
        
    }
    /* time ago function start */

        public function timeAgo($timestamp){
            $time = time() - $timestamp;
            if ($time < 60)
                return  ( $time > 1 ) ? $time . ' seconds' : 'a second';
            elseif ($time < 3600) {
                $tmp = floor($time / 60);
                return ($tmp > 1) ? $tmp . ' minutes' : ' a minute';
            }
            elseif ($time < 86400) {
                $tmp = floor($time / 3600);
                return ($tmp > 1) ? $tmp . ' hours' : ' a hour';
            }
            elseif ($time < 2592000) {
                $tmp = floor($time / 86400);
                return ($tmp > 1) ? $tmp . ' days' : ' a day';
            }
            elseif ($time < 946080000) {
                $tmp = floor($time / 2592000);
                return ($tmp > 1) ? $tmp . ' months' : ' a month';
            }
            else {
                $tmp = floor($time / 946080000);
                return ($tmp > 1) ? $tmp . ' years' : ' a year';
            }
            }
			
	/* time ago functon end */
    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        $title = apply_filters('widget_title', $title);
        $description = apply_filters('widget_description', $description);
        if(empty($title)) $title = "Google Plus Activity";
        if(empty($userid)) $userid = "116899029375914044550";
        if(empty($gplus_api)) $gplus_api = "AIzaSyCPtQ1XMWetDkVw5XuzNnVXpf8ezDtH9So";
        if(empty($count)) $count = "5";
        if(empty($maxwidth)) $maxwidth = "500px";
        /* Start Google Plus Information */
        $gprofile_url = "https://www.googleapis.com/plus/v1/people/$userid?key=$gplus_api";
        $gprofile = json_decode(file_get_contents($gprofile_url));
        /* End Google Plus Information */

        /* Start Grabbing Google Plus Activity */
        $gactivity_url = "https://www.googleapis.com/plus/v1/people/$userid/activities/public?maxResults=$count&key=$gplus_api";
        $gactivity = json_decode(file_get_contents($gactivity_url));
        /* End Grabbing Google Plus Activity */

        echo $before_widget;
        echo $before_title . $title . $after_title;
        
        ?>
<div id="gplus-activity" style="max-width: <?php echo $maxwidth; ?>;">
	<div class="gplus-user">
		<a href="<?php echo $gprofile->url; ?>" target="_blank">
                    <img src="<?php echo $gprofile->image->url; ?>" alt="<?php echo $gprofile->displayName; ?>" title="<?php echo $gprofile->displayName; ?>">
		</a>
		<div class="follow-text">Follow <a href="<?php echo $gprofile->url; ?>" target="_blank"><?php echo $gprofile->displayName; ?></a> on Google Plus</div>
		<div class="gplus-tagline"><?php echo $gprofile->tagline; ?></div>
	</div>
	<?php
	foreach($gactivity->items as $item):
	?>
	<div class="gplus-posts">
		<div class="gplus-text">
			<?php echo $item->title; ?>
		</div>
		<div class="gplus-bottom">
			<div class="gplus-time">
				posted <?php echo $this->timeAgo(strtotime($item->published)); ?> ago
			</div>
			<div class="gplus-readmore">
				<a href="<?php echo $item->url; ?>" target="_blank">read more</a>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div style='color:#ccc; font-size: 9px; text-align:right;'><a href='http://www.carriebowman.com' title='carriebowman.com' target='_blank'>life coach dallas</a></div>
</div>
<?php
    echo $after_widget;
    }
}
//start registering the extension
add_action('widgets_init','register_GooglePlusActivity');
function register_GooglePlusActivity(){
    register_widget('GooglePlusActivity');
}