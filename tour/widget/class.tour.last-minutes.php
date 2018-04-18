<?php
class UTTTourListLastMinutes extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'utttourTourList',
            'description' => 'Get list Tour Last Minutes',
        );
        parent::__construct( 'UTTTourLastminutes', 'UTTTour List Last Minutes', $widget_ops );
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
			'posts_per_page' => $instance['posts_per_page'],
            'meta_query' => array(

            )
		);

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Last Minutes tour', 'ultimate-travel' );
		 
		 
    	$optionsLayout = array(
    	    'columns' => 1
    	);

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo UTTTourShortcode::loopLayout(__CLASS__ . __FUNCTION__, $array_post, $optionsLayout);

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        ?>
		<p>
            <label><?php _e('Number of result', 'ultimate-travel') ?></label>
            <br/>
			 <input type="text"  name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo @$instance['posts_per_page'] ?>" style="width: 100%;" />
		</p>

    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance = wp_parse_args( $new_instance, array(
            'posts_per_page' => '',
        ) );

        $instance = $old_instance;
        $instance['posts_per_page'] = ( isset($new_instance['posts_per_page']) && !empty($new_instance['posts_per_page']) ? $new_instance['posts_per_page'] : '' );

        return $instance;
    }
}
add_action( 'widgets_init', function(){
    register_widget( 'UTTTourListLastMinutes' );
});