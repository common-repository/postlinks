<?php

class LS_SeriesWidget extends WP_Widget
{
    function LS_SeriesWidget()
    {
      $widget_ops = array('classname' => 'ls-series', 'description' => __( "Series",'PostLinks') );
      $control_ops = array();
      $this->WP_Widget(false, __('Series','PostLinks'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
      if (is_single())
      {
        $posts = ls_get_series($instance['key']);
        
        if ($posts)
        {
          extract($args);
          $id = $args['widget_id'];
          $title = empty($instance['title']) ? __('Series', 'PostLinks') : $instance['title'];
          $title = apply_filters('widget_title', $instance['title'] );
          echo $before_widget;
          
          echo $before_title . $title . $after_title;
          echo "<ul class='ls-series-widget'>";

          foreach ($posts as $post)
          {
            if ($instance['show_part'])
              $part = "{$post->part}{$instance['part_separator']}";
            else
              $part = '';
              
            if ($post->ID != get_the_ID())
            {
              $url = get_permalink($post->ID);
              $link = "<li><a href='$url'>$part{$post->post_title}</a></li>";
            }
            else
            {
              $link = "<li>$part{$post->post_title}</li>";
            }
            echo $link;
          }
                    
          echo "</ul>";
          
          echo $after_widget;
        }
      }
    }

    function update($new_instance, $old_instance)
    {
      $instance = $old_instance;
      $instance['title'] = $new_instance['title'];
      $instance['key'] = $new_instance['key'];
      $instance['show_part'] = $new_instance['show_part'];
      $instance['part_separator'] = $new_instance['part_separator'];
      return $instance;
    }

    function form($instance)
    {
      $instance = wp_parse_args( (array) $instance, array('title' => __('Series', 'PostLinks'), 'part_separator' => ' - '));
      $title = $instance['title'];
      $key = $instance['key'];
      $part_separator = $instance['part_separator'];
      
      ?>
      <p>
      <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:','PostLinks'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>
      <p>
      <label for="<?php echo $this->get_field_name('key'); ?>"><?php _e('Key:','PostLinks'); ?></label>
      <input class="widefat" size="5" id="<?php echo $this->get_field_id('key'); ?>" name="<?php echo $this->get_field_name('key'); ?>" type="text" value="<?php echo $key; ?>" />
      </p>
      <p>
      <input class="checkbox" id="<?php echo $this->get_field_id('show_part'); ?>" name="<?php echo $this->get_field_name('show_part'); ?>" type="checkbox" <?php checked($instance['show_part'], 'on'); ?> />
      <label for="<?php echo $this->get_field_id('show_part'); ?>"><?php _e('Show Series Part','PostLinks'); ?></label>      
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('part_separator'); ?>"><?php _e('Part Separator','PostLinks'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('part_separator'); ?>" name="<?php echo $this->get_field_name('part_separator'); ?>" type="text" value="<?php echo $part_separator; ?>" />
      </p>
      
      <?php
    }
}

class LS_AllSeriesWidget extends WP_Widget
{
    function LS_AllSeriesWidget()
    {
      $widget_ops = array('classname' => 'ls-series', 'description' => __( "All Series",'PostLinks') );
      $control_ops = array();
      $this->WP_Widget(false, __('All Series','PostLinks'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
      extract($args);
      $id = $args['widget_id'];
      $title = empty($instance['title']) ? __('Series', 'PostLinks') : $instance['title'];
      $title = apply_filters('widget_title', $instance['title'] );
      echo $before_widget;
      
      echo $before_title . $title . $after_title;
      echo "<ul class='ls-all-series-widget'>";
      $args = array 
      (
        'title_li' => '',
        'show_count' => $instance['show_count'],
        'hide_empty'=> $instance['hide_empty']
      );
      ls_get_all_series($args);
      echo "</ul>";
      
      echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
      $instance = $old_instance;
      $instance['title'] = $new_instance['title'];
      $instance['show_count'] = $new_instance['show_count'];
      $instance['hide_empty'] = $new_instance['hide_empty'];
      return $instance;
    }

    function form($instance)
    {
      $instace = wp_parse_args((array) $instance, array
        (
          'title' => __('Series', 'PostLinks'
        )));
      $title = $instance['title'];
      $key = $instance['key'];
      ?>
      <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','PostLinks'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>

      <p>
      <input class="checkbox" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" type="checkbox" <?php checked($instance['show_count'], 'on'); ?> />
      <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show Post Count','PostLinks'); ?></label>      
      </p>

      <p>
      <input class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" type="checkbox" <?php checked($instance['hide_empty'], 'on'); ?> />
      <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e('Hide Empty Series','PostLinks'); ?></label>      
      </p>
      
      <?php
    }
}

?>