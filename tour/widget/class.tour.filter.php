<?php
class UTTTourWidgetFilter extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'utttourfilter',
            'description' => 'UTT Travel Tour filter',
        );
        parent::__construct( 'UTTTourWidgetFilter', 'UTT Travel Tour filter', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        if(is_singular(UTTTravelTour::$postType) || is_post_type_archive(UTTTravelTour::$postType) || is_tax( get_object_taxonomies( UTTTravelTour::$postType ))) {

            $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Tour Filter', 'ultimate-travel' );
            echo $args['before_widget'];

            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            require dirname(__FILE__) . '/views/form-filter.php';

            echo $args['after_widget'];
        }
    }

    public function form( $instance ) {
        ?>
        <p>
            <label for="filter_price">
                <input
                    id="filter_price"
                    <?php echo isset($instance['price']) && $instance['price'] == 'on' ? 'checked' : '' ?>
                    type="checkbox"
                    name="<?php echo $this->get_field_name( 'price' ); ?>"
                    value="on"> <?php _e('Enable PRICE filter'); ?>
            </label>
        </p>
        <div class="valuePrice">
            <p>
                <label for="">
                    <input value="<?php echo @$instance['price_min'] ?>" type="text" name="<?php echo $this->get_field_name( 'price_min' ); ?>"> Min slide range
                </label>
            </p>
            <p>
                <label for="">
                    <input value="<?php echo @$instance['price_max'] ?>" type="text" name="<?php echo $this->get_field_name( 'price_max' ); ?>"> Max slide range
                </label>
            </p>
            <p>
                <label for="">
                    <input value="<?php echo @$instance['price_step'] ?>" type="text" name="<?php echo $this->get_field_name( 'price_step' ); ?>"> Step slide range
                </label>
            </p>
        </div>
        <p>
            <label for="filter_rating">
                <input
                    id="filter_rating"
                    <?php echo isset($instance['rating']) && $instance['rating'] == 'on' ? 'checked' : '' ?>
                    type="checkbox"
                    name="<?php echo $this->get_field_name( 'rating' ); ?>"
                    value="on"> <?php _e('Enable RATING filter'); ?>
            </label>
        </p>
        <p>
            <label for="filter_departure">
                <input
                    id="filter_departure"
                    <?php echo isset($instance['departure']) && $instance['departure'] == 'on' ? 'checked' : '' ?>
                    type="checkbox"
                    name="<?php echo $this->get_field_name( 'departure' ); ?>"
                    value="on"> <?php _e('Enable DEPARTURE filter'); ?>
            </label>
        </p>
        <p>
            <label for="filter_journey">
                <input
                    id="filter_journey"
                    <?php echo isset($instance['journey']) && $instance['journey'] == 'on' ? 'checked' : '' ?>
                    type="checkbox"
                    name="<?php echo $this->get_field_name( 'journey' ); ?>"
                    value="on"> <?php _e('Enable JOURNEY filter'); ?>
            </label>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance = wp_parse_args( $new_instance, array(
            'price' => '',
            'time' => '',
            'price_min' => '',
            'price_max' => '',
            'price_step' => '',
            'rating' => '',
            'departure' => '',
            'journey' => '',
        ) );

        $instance = $old_instance;
        $instance['price'] = ( isset($new_instance['price']) && $new_instance['price'] == 'on' ? $new_instance['price'] : '' );
        $instance['price_min'] = ( isset($new_instance['price_min']) && $new_instance['price_min'] > 0  ? $new_instance['price_min'] : 0 );
        $instance['price_max'] = ( isset($new_instance['price_max']) && $new_instance['price_max'] > 0  ? $new_instance['price_max'] : 1000 );
        $instance['price_step'] = ( isset($new_instance['price_step']) && $new_instance['price_step'] > 0  ? $new_instance['price_step'] : 10 );
        $instance['time'] = ( isset($new_instance['time']) && $new_instance['time'] == 'on' ? $new_instance['time'] : '' );
        $instance['rating'] = ( isset($new_instance['rating']) && $new_instance['rating'] == 'on' ? $new_instance['rating'] : '' );
        $instance['departure'] = ( isset($new_instance['departure']) && $new_instance['departure'] == 'on' ? $new_instance['departure'] : '' );
        $instance['journey'] = ( isset($new_instance['journey']) && $new_instance['journey'] == 'on' ? $new_instance['journey'] : '' );

        $xxx = $instance['price_max'] - $instance['price_min'];
        if($xxx <= 0) {
            $instance['price_max'] = $instance['price_min'] * 2;
        }

        if ($instance['price_step'] >= $xxx) {
            $instance['price_step'] = $instance['price_step'] / 2;
        }

        return $instance;
    }
}
add_action( 'widgets_init', function(){
    register_widget( 'UTTTourWidgetFilter' );
});