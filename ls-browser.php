<?php

// browse the posts
class LS_Browser
{
  function __construct()
  {    
  }
  
  // generate a list of posts from a list of post types
  function get_posts()
  {
    $this->permission_check();    
    
    $post_types = $this->get_post_types();
    
    $args = array(
      'post_type' => $post_types,
      'post_status' => 'publish',
      'posts_per_page' => -1
    );
    $arr = array();
    $posts = get_posts($args);
    foreach ($posts as $post)
    {
      $entry = array();
      if (current_user_can('edit_post', $post->ID))
      {
        $entry['id'] = $post->ID;
        $entry['title'] = $post->title;
        $arr[] = $entry;
      }
    }
    
    header("Content-Type: application/json");
    echo json_encode($arr);
    exit;
  }
  
  function permission_check()
  {    
    security_check();
    if (!current_user_can('edit_posts'))
      wp_die('wrong permission');
  }

  // get the post types from $_POST
  function get_post_types()
  {
    $post_types = get_post_types();
    $inputs = get_post_value(LS_POST_TYPES);
    $arr = array();
    if (is_array($inputs))
    {
      foreach ($inputs as $input)
      {
        if (in_array($input, $post_types))
          $arr[] = $input;
      }
      
      return $arr;
    }
    else
      return false;
  }
  
  // ajax: get title of a post from id
  function ls_get_title()
  {
    $this->permission_check();        
    if (current_user_can('edit_post', $post->ID))
    {    
      $postID = get_get_value('id');
      if ($postID)
      {
        $post = get_post($postID);
        if (is_object($post))
        {
          $title = $post->post_title;
          $good = true;
        }
        else
        {
          $title = __("Post [$postID] not available", 'PostLinks');
          $good = false;
        }
      }
        
      header("Content-Type: application/json");
      $arr = array('id' => $postID, 'title' => $title, 'good' => $good);
      echo json_encode($arr);
    }
    exit;
  }
}

?>