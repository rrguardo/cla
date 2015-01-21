<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Ocean Free
  * Description: Clean free theme that includes full admin. It has publicity. Do not delete this theme, all the views depend in this theme.
  * Tags: HTML5, Admin, Free
  * Version: 2.1.4
  * Author: Chema <chema@open-classifieds.com> , <slobodan@open-classifieds.com>
  * License: GPL v3
  */


/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders	= array('footer', 'sidebar');


/**
 * styles and themes, loaded in this order
 */

Theme::$styles = array( 'http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css' => 'screen',
                        'css/styles.css?v=2.1.4' => 'screen',
                        'http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css' => 'screen', 
                        'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen',
                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.css' => 'screen',
                        'css/slider.css' => 'screen',
        				);


Theme::$scripts['footer']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
                                    'http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
                                    'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.jquery.min.js',
                                    'http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js',
                                    'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                    'js/bootstrap-slider.js',
                                    'js/theme.init.js?v=2.1.4',
                                    );


/**
 * custom options for the theme
 * @var array
 */
Theme::$options = array();


/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';

Form::$error_tpl 	= '<div class="alert "><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl 	= 	'<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
					</div>';