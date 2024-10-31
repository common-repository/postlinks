<?php
/*
Plugin Name: PostLinks
Version: 0.2
Author URI: http://calce.net/
Plugin URI: http://calce.net/postlinks
Description: Provide the ability to create connections between posts. Links is an extension of Fields, a plugin to manage post custom fields.
Contributors: Khanh Cao

	USAGE:

	See http://calce.net/postlinks

	LICENCE:

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


*/

require('ls-def.php');
require('ls-utils.php');
require('ls-widgets.php');

global $fields_version;
if (!isset($fields_version))
  return;

if (is_admin())
{
  register_activation_hook(__FILE__, 'calce_ls_activate');
}

function calce_ls_activate()
{
}

function ls_widgets_init()
{
  register_widget('LS_SeriesWidget');
  register_widget('LS_AllSeriesWidget');
}
add_action('widgets_init', 'ls_widgets_init');

add_action('init', 'ls_register_taxonomy');
function ls_register_taxonomy()
{
  $args = array('show_ui' => true);
  $labels = array
  (
    'name' => 'Series',
    'singular_name' => 'Series',
    'search_items' => __('Search Series', 'PostLinks'),
    'popular_items' => __('Popular Series', 'PostLinks'),
    'all_items' => __('All Series', 'PostLinks'),
    'parent_item' => __('Parent Series', 'PostLinks'),
    'parent_item_colon' => __('Parent Series:', 'PostLinks'),
    'edit_item' => __('Edit Series', 'PostLinks'),
    'update_item' => __('Update Series', 'PostLinks'),
    'add_new_item' => __('Add New Series', 'PostLinks'),
    'new_item_name' => __('New Series Name', 'PostLinks'),
  );
  
  $post_types = get_post_types($args);
  register_taxonomy(LS_TAX, $post_types, array('hierarchical' => true, 'label' => __('Series'), 'labels' => $labels, 'public' => false, 'show_ui' => false, 'show_in_nav_menus' => true));

}
  
function ls_plugin_action_links($links, $file)
{
  //$links[] = "<a href='" . '#' . "'>" . __('Whoo hoo') . "</a>";
  return $links;
}
add_action('plugin_action_links', 'ls_plugin_action_links', 10, 2);

require('ls-browser.php');

load_plugin_textdomain('PostLinks', TEMPLATEPATH . '/languages');

class LS_Links
{
  private $browser;
  
  function __construct()
  {
    add_action('fs_init', array($this, 'register_field_types'));
    
    if (is_admin())
    {
      add_action('admin_init', array($this, 'admin_init'));
      $this->browser = new LS_Browser();
      add_action('wp_ajax_ls_get_title', array($this->browser, 'ls_get_title'));
      add_action('wp_ajax_ls_get_posts', array($this->browser, 'ls_get_posts'));
    }
    
  }
  
  function register_field_types()
  {
    //require('ls-post-link.php');
    require('ls-series.php');
    
    //register_field_type(new LS_PostLink());
    register_field_type(new LS_Series());
  }
  
  function admin_init()
  {
    $mode = get_admin_mode();
    if ($mode == MODE_EDIT)
    {
      $url = WP_PLUGIN_URL . '/' . ls_get_plugin_name() . '/';
      wp_enqueue_style('ls-links', $url . 'css/links.css');
      wp_enqueue_script('ls-links', $url . 'js/links.js', array(), '1.0', true);
      
    }    
  }
  
  /*function ls_register_shortcode()
  {
    require('my-simple-field-shortcode.php');
    fs_add_shortcode(new MY_ShortCode());
  }
  add_action('fs_viewer_init', 'myfields_register_shortcode');*/
  
}

new LS_Links();

?>