<?php

/***** User List Widget *****/

class cyc_user_list extends WP_Widget {
	function __construct() {
		parent::__construct(
			'mh_newsdesk_lite_user_list', 'MH Lista de Editores',
			array('mh_newsdesk_lite_user_list' => 'mh_affiliate', 'description' => esc_html__('Display a User list.', 'mh-newsdesk-lite'))
		);
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		
                $single_role = FALSE;
                
		if ($instance['administrator']=='yes' && $instance['editor']=='yes' && $instance['author']=='yes' && $instance['contributor'] && $instance['subscriber']=='yes' && $instance['columnista']=='yes') {
			$all_roles = new WP_user_query(array( 'orderby' =>'post_count', 'order' => 'DESC'));
			$merged_roles = $all_roles->results;
		}
		else {
			$administrator_result = $editor_result = $author_result = $contributor_result = $subscriber_result = $columnista_result = array();
			$administrator = new WP_user_query(array( 'role' => 'Administrator', 'orderby' =>'post_count', 'order' => 'DESC'));
			$editor = new WP_user_query(array( 'role' => 'Editor', 'orderby' =>'post_count', 'order' => 'DESC'));
			$author = new WP_user_query(array( 'role' => 'Author', 'orderby' =>'post_count', 'order' => 'DESC'));
			$contributor = new WP_user_query(array( 'role' => 'Contributor', 'orderby' =>'post_count', 'order' => 'DESC'));
			$subscriber = new WP_user_query(array( 'role' => 'Subscriber', 'orderby' =>'post_count', 'order' => 'DESC'));
			$columnista = new WP_user_query(array( 'role' => 'Columnista', 'orderby' =>'post_count', 'order' => 'DESC'));
			
			if($instance['administrator']=='yes'){$administrator_result = $administrator->results; $count = 1; }
			if($instance['editor']=='yes'){$editor_result = $editor->results; $count++; }
			if($instance['author']=='yes'){$author_result = $author->results; $count++; }
			if($instance['contributor']=='yes'){$contributor_result = $contributor->results; $count++; }
			if($instance['subscriber']=='yes'){$subscriber_result = $subscriber->results; $count++; }
			if($instance['columnista']=='yes'){$columnista_result = $columnista->results; $count++; }
                        
                        if ( $count == 1 ) {$single_role = TRUE;}
			
			$merged_roles =  array_merge($administrator_result, $editor_result, $author_result ,$contributor_result, $subscriber_result, $columnista_result);
                        shuffle ( $merged_roles );
                        //$count_roles = array_count_values($administrator_result, $editor_result, $author_result ,$contributor_result, $subscriber_result, $columnista_result);
		}

		echo $before_widget;
		if ( $title) {
			echo $before_title . $title . $after_title;
		}
		echo '<div class="user-lists clearfix">';
			if (!empty( $merged_roles ) ) {
				$i=0;
				foreach ( $merged_roles as $user ) { 
					if($i>=$instance['limit']) break; else $i++; 
					echo '<div id="user-list-'.$user->ID.'" class="user-list-wrap">';
						echo '<div class="user-list">';
							echo '<div class="user-avatar"><a href="'.esc_url(get_author_posts_url($user->ID )).'">'.get_avatar( $user->ID, 110 ).'</a></div>';
							echo '<div class="user-name"><a href="'.esc_url(get_author_posts_url($user->ID )).'">'.$user->display_name.'</a></div>';
							if (!$single_role){
                                                                if ($user->position) {
                                                                        echo '<div class="user-position">'.$user->position.'</div>';
                                                                }
                                                                else {
                                                                        echo '<div class="user-position">'.$user->roles[0].'</div>';
                                                                }
                                                        }
						echo '</div>';
					echo '</div>';
					//if ($i%2==0) {echo '<div class="clear"></div>';}
				}
			}
		echo '</div>';
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		$instance['administrator'] = strip_tags($new_instance['administrator']);
		$instance['editor'] = strip_tags($new_instance['editor']);
		$instance['author'] = strip_tags($new_instance['author']);
		$instance['contributor'] = strip_tags($new_instance['contributor']);
		$instance['subscriber'] = strip_tags($new_instance['subscriber']);
		$instance['columnista'] = strip_tags($new_instance['columnista']);
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$limit = isset($instance['limit']) ? esc_attr($instance['limit']) : '6';
		$instance['administrator'] = isset($instance['administrator']) ? esc_attr($instance['administrator']) : true;
		$instance['editor'] = isset($instance['editor']) ? esc_attr($instance['editor']) : true;
		$instance['author'] = isset($instance['author']) ? esc_attr($instance['author']) : true;
		$instance['contributor'] = isset($instance['contributor']) ? esc_attr($instance['contributor']) : true;
		$instance['subscriber'] = isset($instance['subscriber']) ? esc_attr($instance['subscriber']) : true;
		$instance['columnista'] = isset($instance['columnista']) ? esc_attr($instance['columnista']) : true;
		
?>

		<!-- admin widget starts -->
		<div class="rd-widget-form">
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'abomb_backend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', 'abomb_backend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /></p>
			<p>
				<label for="user-role"><strong><?php _e('User Roles:', 'abomb_backend'); ?></strong></label><br/>
				<input class="widefat" id="<?php echo $this->get_field_id( 'administrator' ); ?>" name="<?php echo $this->get_field_name( 'administrator' ); ?>" value="yes" <?php if( $instance['administrator'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'administrator' ); ?>"><?php _e('Administrator', 'abomb_backend'); ?></label><br/>
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'editor' ); ?>" name="<?php echo $this->get_field_name( 'editor' ); ?>" value="yes" <?php if( $instance['editor'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'editor' ); ?>"><?php _e('Editor', 'abomb_backend'); ?></label><br/>
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" value="yes" <?php if( $instance['author'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e('Author', 'abomb_backend'); ?></label><br/>
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'contributor' ); ?>" name="<?php echo $this->get_field_name( 'contributor' ); ?>" value="yes" <?php if( $instance['contributor'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'contributor' ); ?>"><?php _e('Contributor', 'abomb_backend'); ?></label><br/>
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'subscriber' ); ?>" name="<?php echo $this->get_field_name( 'subscriber' ); ?>" value="yes" <?php if( $instance['subscriber'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'subscriber' ); ?>"><?php _e('Subscriber', 'abomb_backend'); ?></label><br/>
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'columnista' ); ?>" name="<?php echo $this->get_field_name( 'columnista' ); ?>" value="yes" <?php if( $instance['columnista'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'columnista' ); ?>"><?php _e('Columnista', 'abomb_backend'); ?></label>
			</p>
		</div>
		<!-- admin widget ends -->
<?php
	}
}

?>