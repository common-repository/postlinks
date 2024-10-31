<?php

class LS_PostLink extends FS_FieldType
{

  public function __construct()
  {
    parent::__construct('postlink', 'Post Link');
    
    $this->html_class = 'postlink-linked';
  }
  
  function show_options($field)
  {
    // edit field options
    if (is_array($field))
    {
      echo
        "<tr style='display: none' class='additional {$this->html_class}'>".
      	'	<th valign="top" scope="row"><label for="tag-postlink">' . __('Post types', 'Links') . '</label></th>'.
      	'	<td valign="middle">';
      	ls_list_post_types($field['postlink_post_types']);
      	echo ' <p class="description">' . __('Post types to which this field type may connect', 'Links') . '</p>'.
      	'</td></tr>';
    }
    // new field form
    else
    {
      echo
      "<div class='form-field form-required additional {$this->html_class}'>".
      '	<label for="tag-postlink">' . __('Post types', 'Myfields') . '</label>';
      ls_list_post_types('');
    	echo ' <p class="description">' . __('Post types to which this field type may connect', 'Links') . '</p>'.
      '</div>';
    }
  }
  
  function save_options($field, $group)
  {
    $field['postlink_post_types'] = ls_collect_post_types();
    return $field;
  }
  
  function show_field($post, $box, $field)
  {
    if ($field['note'])
      $note = '<p class="description">' . $field['note'] . '</p>';

    echo "<th valign='top' scope='row'><label for='{$field['tag_id']}'>{$field['title']}</label></th>" .
         "<td class='fs-sortable' id='{$field['tag_id']}' valign='top'>";
      
    $field['tag_name'] .= '[]';
    $values = $this->get_values($field);
    
    foreach ($values as $value)
    {        
      echo "<div class='fs-postlink-row'>" .
           "<input type='text' class='fs-postlink' aria-required='true' value='{$value['id']}' id='{$field['tag_id']}' name='{$field['tag_name']}' />" .
           "<a href='#' tabindex='-1' class='fs-hidden fs-remove-row'><br /></a>" .
           "</div>";
    }
    echo "<div class='fs-add-more-wrap'>$note<a class='button fs-postlink-add-more' href='#'>" . __('Add more', 'fields') . '</a>'.
         "</div>";
    
    echo "</td>";
  }
  
  function save_field($postID, $field)
  {
    field_delete_meta($postID, $field['key']);
    $ids = array();
    if (is_array($field['meta_value']))
    {
      $index = 0;
      foreach ($field['meta_value'] as $value)
      {
        if (!in_array($value, $ids))
        {
          $arr = array('order' => $index++, 'id' => $value);
          /*if ($post = get_post($value))
          {
            $oarr = array('order' => $index++, 'id' => $value));
            fs_add_meta($value, $field['key'], )
          }*/
          fs_add_meta($postID, $field['key'], serialize($arr));
          $ids[] = $value;
        }
      }      
    }
  }

  function get_values($field)
  {
    $meta = fs_get_meta($field['key'], false);
    $values = array();
    if (is_array($meta))
    {
      foreach ($meta as $value)
      {
        $value = maybe_unserialize($value);
        $values[] = $value;
      }
      sort_by_order(&$values);
    }
    else
    {
      $values[] = maybe_unserialize($meta);
    }
    
    if (sizeof($values) == 0)
    {
      $value = array('order' => 0, 'id' => '');
      $values[] = $value;
    }
    
    return $values;
  }

}

?>