<?php

class LS_Series extends FS_FieldType
{
  public static $delayed_tax;
  
  public function __construct()
  {
    parent::__construct('series', 'PostSeries');
    self::$delayed_tax = array();
    $this->html_class = 'fs-series-linked';
    if (is_admin())
    {
      add_action('admin_menu', array($this, 'add_menu'));
      add_action('fs_meta_save', array($this, 'save_delayed_tax'), 10, 2);
    }
    add_shortcode('series', array($this, 'do_shortcode'));
  }
  
  function do_shortcode($atts, $content=null, $code="")
  {    
    extract(shortcode_atts(array(
  		'key' => '',
  		'before' => '<ul class="ls-series">',
  		'after' => '</ul>',
  		'before_item' => '<li>',
  		'after_item' => '</li>',
  		'show' => 'all',
  		'hide_part' => false,
  		'before_link_text' => '',
  		'after_link_text' => '',
  		'part_separator' => ' - ',
  	), $atts));
  	if ($key != '')
  	{      
      $posts = ls_get_series($key);
      if ($posts)
      {
        $s = $before;
        
        // previous
        if ((strcasecmp($show, 'prev') == 0) || (strcasecmp($show, 'previous') == 0) || (strcasecmp($show, 'p') == 0))
        {
          if (count($posts) > 1)
          {
            $index = 0;
            foreach ($posts as $post)
            {
              if ($post->ID == get_the_ID())
              {
                break;
              }
              $index++;
            }
            
            if ($index > 0)
            {
              $post = $posts[$index-1];
              $url = get_permalink($post->ID);
              if ($hide_part)
                $link_text = "$before_link_text{$post->post_title}$after_link_text";
              else
                $link_text = "$before_link_text{$post->part}$part_separator{$post->post_title}$after_link_text";
              return "<a href='$url'>$link_text</a>";
            }
          }
        }
        // next
        elseif ((strcasecmp($show, 'next') == 0) || (strcasecmp($show, 'n') == 0))
        {
          if (count($posts) > 1)
          {
            $index = 0;
            foreach ($posts as $post)
            {
              if ($post->ID == get_the_ID())
              {
                break;
              }
              $index++;
            }
            
            if ($index < count($posts)-1)
            {
              $post = $posts[$index+1];
              $url = get_permalink($post->ID);
              if ($hide_part)
                $link_text = "$before_link_text{$post->post_title}$after_link_text";
              else
                $link_text = "$before_link_text{$post->part}$part_separator{$post->post_title}$after_link_text";
              return "<a href='$url'>$link_text</a>";
            }
          }
        }
        // all
        else
        {
          foreach ($posts as $post)
          {
            if ($post->ID != get_the_ID())
            {
              $url = get_permalink($post->ID);
              if ($hide_part)
                $link_text = "$before_link_text{$post->post_title}$after_link_text";
              else
                $link_text = "$before_link_text{$post->part}$part_separator{$post->post_title}$after_link_text";
              $link = "<a href='$url'>$link_text</a>";
            }
            else
            {
              if ($hide_part)                
                $link = "$before_link_text{$post->post_title}$after_link_text";
              else
                $link = "$before_link_text{$post->part}$part_separator{$post->post_title}$after_link_text";
            }
            $s .= $before_item . $link . $after_item;
          }
        }          
        
        return $s . $after;
      }
  	}
  	
  	return '';
  }
  
  function add_menu()
  {
    global $submenu;
    add_submenu_page(PAGE_MANAGE, 'Series', 'Series', 'edit_posts', 'series', array($this, 'edit_series'));
    // using this dirty hack until we can figure out the legit way
    if (current_user_can('edit_posts'))
    {
      $index = 0;
      foreach ($submenu['fields'] as $item)
      {
        if ($item[2] == 'series')
        {
          $submenu['fields'][$index][2] = 'edit-tags.php?taxonomy=series';
        }
        $index++;
      }
    }
  }
  
  function edit_series()
  {
    require('ls-series-editor.php');
    new LS_SeriesEditor();
  }
  
  function save_delayed_tax($postID, $fields)
  {
    wp_set_object_terms($postID, self::$delayed_tax, LS_TAX);
  }
  
  function show_options($field)
  {
    // edit field form
    if (is_array($field))
    {
    }
    // add field form
    else
    {
    }  
  }
  
  function save_options($field, $group)
  {
    $field['series_values'] = get_post_value('series-values');
    return $field;
  }
  
  function show_field($post, $box, $field)
  {
    if ($field['group']['layout'] == LABEL_LEFT)
      echo "<th valign='top' scope='row'><label>{$field['title']}</label></th>";
    echo "<td valign='top'>";
    if ($field['group']['layout'] == LABEL_TOP)
      echo "<label class='fs-one-column-label'>{$field['title']}</label>";

    echo "<select class='fs-select' id='{$field['tag_id']}' name='{$field['tag_name']}'>\n" .
         "<option value='-1'>none</option>\n";

    $args = array
    (
      'hide_empty' => false,      
    );
    $series = array_map(array($this, 'extreact_tax'), get_terms(LS_TAX, $args));
    $meta = fs_get_meta($field['key']);
    
    if (is_array($meta))
      $meta = $meta['value'];
    
    foreach ($series as $v)
    {
      if ($meta == $v['id'])
        $selected = ' selected';
      else
        $selected = '';
      echo "<option value='{$v['id']}'$selected>{$v['name']}</option>\n";
    }

    if ($field['note'])
      $note = '<p class="description">' . $field['note'] . '</p>';    
    echo "</select>\n"; 
    
    $part_tag = META_FIELD . '[' . $field['key'] . '-calce-series-part]';
    $part_value = fs_get_meta($field['key'] . '_##_part');
    echo '<div class="ls-part-row"><label>' . __('Part: ', 'PostLinks') . "</label><input size='10' type='text' id='tag-$part_tag' name='$part_tag' value='$part_value' /></div>$note</td>";
  }
  
  function save_field($postID, $field)
  {
    $series = esc_attr($field['meta_value']);
    
    $part = $_POST[META_FIELD][$field['key'] . '-calce-series-part'];
    $part_key = get_series_part_key($field['key']);
    
    fs_delete_meta($postID, $field['key']);
    fs_delete_meta($postID, $part_key);
    fs_update_meta($postID, $field['key'], $series);
    fs_update_meta($postID, $part_key, $part);
    
    if ($series > -1)
    {
      self::$delayed_tax[] = (int)$series;
    }
  }  
  
  // extract IDs and names from a taxonomy term list
  function extreact_tax($n)
  {
    $arr['id'] = $n->term_id;
    $arr['name'] = $n->name;
    return $arr;
  }
}