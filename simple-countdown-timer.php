<?php
/*
	Plugin Name: Simple Countdown Timer
	Plugin URI: http://www.tommyleunen.com
	Description: Allows you to create multiple countdown on your blog with a widget, or using the shortcode [sct date="..."] into your post and pages.
	Version: 0.2.3
	Author: Tommy Leunen
	Author URI: http://www.tommyleunen.com
	License: GPLv2
*/

/*  Copyright 2011  Tommy Leunen (t@tommyleunen.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$sct_text_date = array(
		'days'		=>	'days',
		'hours'		=>	'hours',
		'minutes'	=>	'min',
		'seconds'	=>	'sec'
);


add_action( 'admin_init', 'sct_admin_init' );
function sct_admin_init()
{
	wp_register_script( 'sct_jquery_datepicker_slider', plugins_url('jquery-ui-1.8.13.custom.min.js', __FILE__));
	wp_register_script( 'sct_datetimepicker', plugins_url('jquery-ui-timepicker-addon.js', __FILE__));
	
	wp_register_style( 'sct_css_jquery_datepicker_slider', plugins_url('jquery-ui-1.8.13.custom.css', __FILE__) );
	wp_register_style( 'sct_css_datetimepicker', plugins_url('jquery-ui-timepicker-addon.css', __FILE__) );
	
	add_action( 'admin_print_scripts-widgets.php', 'sct_admin_widgets_print_scripts' );
	add_action( 'admin_print_styles-widgets.php', 'sct_admin_widgets_print_styles' );
	
	add_action( 'admin_print_scripts-post.php', 'sct_admin_posts_print_scripts' );
	add_action( 'admin_print_scripts-post-new.php', 'sct_admin_posts_print_scripts' );
}

function sct_admin_widgets_print_scripts()
{
	wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-draggable');	
	
	wp_enqueue_script('sct_jquery_datepicker_slider');
	wp_enqueue_script('sct_datetimepicker');
}

function sct_admin_widgets_print_styles()
{
	wp_enqueue_style('sct_css_jquery_datepicker_slider');
	wp_enqueue_style('sct_css_datetimepicker');
}

function sct_admin_posts_print_scripts()
{
	wp_enqueue_script('sct_quicktag', plugins_url('sct_quicktag.js', __FILE__), array( 'quicktags') );
}

add_action('plugins_loaded', 'sct_plugins_loaded');
function sct_plugins_loaded()
{
	wp_register_script( 'sct_countdown', plugins_url('jquery.countdown.min.js', __FILE__));

	wp_enqueue_script('jquery');
	wp_enqueue_script('sct_countdown');
	
	wp_register_style( 'sct_css_countdown', plugins_url('jquery.countdown.min.css', __FILE__));

	wp_enqueue_style('sct_css_countdown');
}

function sct_calcage($secs, $num1, $num2)
{
	$value = (floor($secs/$num1))%$num2;
	if($value < 10) $value = '0'.$value;
	return $value;
}


// shortcodes
add_shortcode( 'sct', 'sct_code_handler' ); // [sct date="" align="" size=""]
$sct_code_incremental_id = 0;
function sct_code_handler( $atts )
{
	global $sct_code_incremental_id, $sct_text_date;
	extract( shortcode_atts( array(
		'date' => date('m/d/Y H:i'),
		'align' => 'left',
		'size' => 2,
	), $atts ) );
	
	$aligntxt = 'margin: auto auto auto 0;';
	if($align == 'center') $aligntxt = 'margin: auto;';
	else if($align == 'right') $aligntxt = 'margin: auto 0 auto auto;';
	else if($align == 'floatleft') $aligntxt = 'float: left';
	else if($align == 'floatright') $aligntxt = 'float: right;';
	
	// retrieve date
	$gmtOffset = (float) get_option('gmt_offset');
	if(empty($gmtOffset))
		$gmtOffset = wp_timezone_override_offset();
				
	$secs = strtotime($date)-(time()+($gmtOffset*3600));
	if($secs < 0) $secs = 0;
		
	$d = sct_calcage($secs,86400,100000);
	$h = sct_calcage($secs,3600,24);
	$m = sct_calcage($secs,60,60);
	$s = sct_calcage($secs,1,60);
				
	$js = $d.':'.$h.':'.$m.':'.$s;
	$three = ($d > 99);

	switch($size)
	{
		case 1:
			$divWidth = $three ? 145 : 130;
			$imgWidth = 13;
			$imgHeight = 19;
			$classDesc = 'c1';
		break;
		case 2:
			$divWidth = $three ? 215 : 195;
			$imgWidth = 21;
			$imgHeight = 30;
			$classDesc = 'c2';
		break;
		case 3:
			$divWidth = $three ? 260 : 235;
			$imgWidth = 26;
			$imgHeight = 38;
			$classDesc = 'c3';
		break;
		default:
			$divWidth = $three ? 495 : 445;
			$imgWidth = 52;
			$imgHeight = 76;
			$classDesc = 'c4';
	}
	
	$ret = '<div id="sct_id_'.$sct_code_incremental_id.'" class="simple_countdown_timer" style="width: '.$divWidth.'px; '.$aligntxt.'">';

	if (!empty($date))
	{
		$digits = plugins_url('images/digits-'.$imgWidth.'-'.$imgHeight.'.png', __FILE__);
			
		$jsdays = $three ? 'ddd' : 'dd';
		$class = $three ? ' class="three"' : '';
		
		$ret .= '<div class="sct_count" style="height: '.$imgHeight.'px; vertical-align: middle; overflow: hidden;"></div>';
		$ret .= '<div class="desc '.$classDesc.'"><div'.$class.'>'.$sct_text_date['days'].'</div><div>'.$sct_text_date['hours'].'</div><div>'.$sct_text_date['minutes'].'</div><div class="secs">'.$sct_text_date['seconds'].'</div></div>';
		$ret .= '<div style="clear: both;"></div>';
	}
		
	$ret .= <<<COUNTER_JS
<script type="text/javascript">
jQuery(function(){
	jQuery('#sct_id_{$sct_code_incremental_id} .sct_count').countdown({
		image: '{$digits}',
		format: "{$jsdays}:hh:mm:ss",
		startTime: "{$js}",
		digitWidth: {$imgWidth},
		digitHeight: {$imgHeight},
	});
});
</script>		
COUNTER_JS;

	$ret .= '</div>';
	++$sct_code_incremental_id;
	return $ret;
}

// TinyMCE plugin
add_filter('mce_external_plugins', "sct_tinymceplugin_register");
function sct_tinymceplugin_register($plugin_array)
{
    $url = get_bloginfo('url')."/wp-content/plugins/simple-countdown-timer/tinymce/sctplugin.js";
    $plugin_array["sctplugin"] = $url;
    return $plugin_array;
}

add_filter('mce_buttons', 'sct_tinymceplugin_addbutton', 0);
function sct_tinymceplugin_addbutton($buttons)
{
    array_push($buttons, "separator", "sctplugin");
    return $buttons;
}



add_action( 'widgets_init', create_function('', 'return register_widget("SimpleCountdownTimer_Widget");') );
class SimpleCountdownTimer_Widget extends WP_Widget
{
	function SimpleCountdownTimer_Widget()
	{
		parent::WP_Widget(false, $name = 'Simple Countdown Timer');	
	}
	
	function form($instance)
	{
		$defaults = array(
			'title' => 'Example Countdown', 
			'date' => date('m/d/Y H:i'), 
			'align' => 'left', 
			'size' => 2
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'date' ); ?>">Date:</label>
			<input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="<?php echo $instance['date']; ?>" style="width:100%;" onClick="javascript:jQuery('#<?php echo $this->get_field_id( 'date' ); ?>').datetimepicker();" />
			<script>
				jQuery('#<?php echo $this->get_field_id( 'date' ); ?>').datetimepicker();
			</script>
			<span>If you just drag the widget, please <a href="javascript:location.reload(true)">Refresh this page</a>, so you can use the tool to select the right date and time you want.</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'align' ); ?>">Alignment:</label>
			<select id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name('align'); ?>">
				<option value="center"<?php echo $instance['align'] == 'center' ? 'selected="selected"' : ''; ?>>center</option>
				<option value="left"<?php echo $instance['align'] == 'left' ? 'selected="selected"' : ''; ?>>left</option>
				<option value="right"<?php echo $instance['align'] == 'right' ? 'selected="selected"' : ''; ?>>right</option>
				<option value="floatleft"<?php echo $instance['align'] == 'floatleft' ? 'selected="selected"' : ''; ?>>float left</option>
				<option value="floatright"<?php echo $instance['align'] == 'floatright' ? 'selected="selected"' : ''; ?>>float right</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>">Size:</label>
			<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name('size'); ?>">
				<option value="1"<?php echo $instance['size'] == '1' ? 'selected="selected"' : ''; ?>>1</option>
				<option value="2"<?php echo $instance['size'] == '2' ? 'selected="selected"' : ''; ?>>2</option>
				<option value="3"<?php echo $instance['size'] == '3' ? 'selected="selected"' : ''; ?>>3</option>
				<option value="4"<?php echo $instance['size'] == '4' ? 'selected="selected"' : ''; ?>>4</option>
			</select>
		</p>
<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['date'] = strip_tags( $new_instance['date'] );
		$instance['align'] = strip_tags( $new_instance['align'] );
		$instance['size'] = strip_tags( $new_instance['size'] );

		return $instance;
	}

	function widget($args, $instance)
	{
		extract( $args );
		
		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$date = $instance['date'];
		$align = $instance['align'];
		$size = $instance['size'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		if (!empty($title))
		{
			echo $before_title . $title . $after_title;
		}
		
		echo do_shortcode('[sct date="'.$date.'" align="'.$align.'" size="'.$size.'"]');
		
		/* After widget (defined by themes). */
		echo $after_widget;
		
	}
}