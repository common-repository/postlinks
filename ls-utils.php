<?php

// collect checked post types from the option page
function ls_collect_post_types()
{
  $arr = array();
  $post_types = get_plain_post_value('links-post-types');
  if (is_array($post_types))
  {
    foreach ($post_types as $g)
      $arr[] = esc_attr($g);
  }
  return $arr;
}

function ls_list_post_types($arr)
{
  $args = array(
  );
  $post_types = get_post_types($args, 'objects');
  $i = 1;
  foreach ($post_types as $post_type) {
    $checked = false;
    if (is_array($arr))
    foreach ($arr as $v) {
      if (strcasecmp($v, $post_type->name) == 0) {
        $checked = true;
        break;
      }
    }
    if ($checked)
      $checked = ' checked="checked"';
    else
      $checked = '';
    echo "<div class='check-row'><input type='checkbox' name='links-post-types[]' id='post-type-$i'".
         " value='{$post_type->name}'$checked /><label class='input-label' for='post-type-$i'>".
         "{$post_type->labels->name}</label></div>";
    $i++;
  }
  echo '<div class"cut"></div>';
}

function ls_get_plugin_name()
{
  $path = explode('/', plugin_basename(__FILE__));
  $size = sizeof($path);
  if ($size > 1)
    $page_name = $path[$size-2];
  return $page_name;
}

function ls_get_series($key, $postID = '')
{
  global $wpdb;
  $prefix = $wpdb->prefix;
  $meta = (int)fs_get_meta($key, true, $postID);
  $part_key = get_series_part_key($key);
  $tax = LS_TAX;
  $query = "SELECT DISTINCT wposts.*, wpostmeta.meta_value AS part, wpostmeta.meta_id
    FROM {$prefix}posts wposts
    LEFT JOIN {$prefix}postmeta wpostmeta ON wposts.ID = wpostmeta.post_id
    LEFT JOIN {$prefix}term_relationships ON (wposts.ID = {$prefix}term_relationships.object_id)
    LEFT JOIN {$prefix}term_taxonomy ON ({$prefix}term_relationships.term_taxonomy_id = {$prefix}term_taxonomy.term_taxonomy_id)
  WHERE {$prefix}term_taxonomy.taxonomy = '$tax'
  AND {$prefix}term_taxonomy.term_id = $meta
  AND wpostmeta.meta_key = '_$part_key'
  ORDER BY wpostmeta.meta_value
  ";
  
  return $wpdb->get_results($query, OBJECT);
}

function ls_get_all_series($args)
{
  $args['taxonomy'] = LS_TAX;
  return wp_list_categories($args);
}
  
function get_series_part_key($key)
{
  return $key . '_##_part';
}

?>