<?php
class UTTTourList extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'utttourTourList',
            'description' => 'Get list Tour',
        );
        parent::__construct( 'UTTTour', 'UTTTour List', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
    	$array_post = array(
            'post_type' => UTTTravelTour::$postType,
    		'order' =>  $instance['order'],   
    		'orderby' => $instance['orderby'],
			'posts_per_page' => $instance['posts_per_page'],
			'tax_query' => array(                     
			      array(
			        'taxonomy' => 'region',               
			        'field' => 'term_id',                      
			        'terms' => array( $instance['region']),    
			      ),
			      array(
			        'taxonomy' => 'tag-tour',
			        'field' => 'term_id',
			        'terms' => array( $instance['tag_tour']),
			      )
			    )
		);
		 
		 
    	$optionsLayout = array(
    	    'columns' => 1
    	);

       	echo UTTTourShortcode::loopLayout(__CLASS__ . __FUNCTION__, $array_post, $optionsLayout);
    }

    public function form( $instance ) {
        ?>
		<?php
			$terms_region = get_terms( array(
			    'taxonomy' => 'region',
			    'hide_empty' => false,
			) );

			$terms_tag_tour = get_terms( array(
			    'taxonomy' => 'tag-tour',
			    'hide_empty' => false,
			) );
		?>

		<p>
            <label for="">Title</label><br>
            <input class="widefat" type="text" name="<?php echo $this->get_field_name( 'tag_tour' ); ?>" value="<?php echo @$instance['title'] ?>">
        </p>
		<p>
            <label>Tag Tour</label><br>
			<select name="<?php echo $this->get_field_name( 'tag_tour' ); ?>" style="width: 100%;">
                <option value="">Select a Tag</option>
				<?php   foreach ($terms_tag_tour as $item):
    						if($item->term_id == @$instance['tag_tour']){ ?>
								<option selected="selected" value='<?php echo $item->term_id ?>'><?php echo $item->name  ?></option>
    			<?php		}else{ ?>
								<option value='<?php echo $item->term_id ?>'><?php echo $item->name  ?></option>
				<?php 		} ?>
				<?php 	endforeach; ?>
			</select>
		</p>


		<p>
            <label>Region</label>
            <br/>
			<select name="<?php echo $this->get_field_name( 'region' ); ?>" style="width: 100%;">
                <option value="">Select a Region</option>
				<?php   foreach ($terms_region as $item):
							if($item->term_id == @$instance['region']){ ?>
								<option selected="selected" value='<?php echo $item->term_id ?>'><?php echo $item->name  ?></option>
				<?php		}else{ ?>
								<option value='<?php echo $item->term_id ?>'><?php echo $item->name  ?></option>
				<?php 		} ?>
				<?php 	endforeach; ?>
			</select>
		</p>


		<p>
            <label>Order</label>
            <br/>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" style="width: 100%;">
                <option <?php echo (@$instance['order'] == 'DESC' ? 'selected' : '') ?> value='<?php _e('DESC') ?>'><?php _e('DESC') ?></option>
                <option <?php echo (@$instance['order'] == 'ASC' ? 'selected' : '') ?> value='<?php _e('ASC') ?>'><?php _e('ASC') ?></option>
			</select>
		</p>


		<p>
            <label>Order by</label>
            <br/>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" style="width: 100%;">
                <option <?php echo (@$instance['orderby'] == 'title' ? 'selected' : '') ?> value='title'><?php _e('Title') ?></option>
                <option <?php echo (@$instance['orderby'] == 'ID' ? 'selected' : '') ?> value='ID'><?php _e('ID') ?></option>
                <option <?php echo (@$instance['orderby'] == 'Date' ? 'selected' : '') ?> value='Date'><?php _e('Date') ?></option>
            </select>
		</p>


		<p>
            <label>Posts per page</label>
            <br/>
			 <input type="text"  name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo @$instance['posts_per_page'] ?>" style="width: 100%;" />
		</p>

    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance = wp_parse_args( $new_instance, array(
            'tag_tour' => '',
            'region' => '',
            'order' => '',
            'orderby' => '',
            'posts_per_page' => '',
        ) );

        $instance = $old_instance;
        $instance['tag_tour'] = ( isset($new_instance['tag_tour']) && !empty($new_instance['tag_tour']) ? $new_instance['tag_tour'] : '' );
        $instance['region'] = ( isset($new_instance['region']) && !empty($new_instance['region']) ? $new_instance['region'] : '' );
        $instance['order'] = ( isset($new_instance['order']) && !empty($new_instance['order']) ? $new_instance['order'] : '' );
        $instance['orderby'] = ( isset($new_instance['orderby']) && !empty($new_instance['orderby']) ? $new_instance['orderby'] : '' );
        $instance['posts_per_page'] = ( isset($new_instance['posts_per_page']) && !empty($new_instance['posts_per_page']) ? $new_instance['posts_per_page'] : '' );

        return $instance;
    }
}
add_action( 'widgets_init', function(){
    register_widget( 'UTTTourList' );
});