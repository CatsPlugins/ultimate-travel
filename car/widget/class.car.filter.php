<?php
class UTTCarWidgetFilter extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'uttcarfilter',
            'description' => 'UTT Travel Car filter',
        );
        parent::__construct( 'UTTCarWidgetFilter', 'UTT Travel Car filter', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        require dirname(__FILE__) . '/views/filter_car.php';
    }

    public function form( $instance ) {
        ?>
        <p>
            <label for="filter_<?php echo UTTTravelCar::trademark ?>">
                <input
                        id="filter_<?php echo UTTTravelCar::trademark ?>"
                    <?php echo isset($instance[UTTTravelCar::trademark]) && $instance[UTTTravelCar::trademark] == 'on' ? 'checked' : '' ?>
                        type="checkbox"
                        name="<?php echo $this->get_field_name( UTTTravelCar::trademark ); ?>"
                        value="on"> <?php _e('Enable '. strtoupper(UTTTravelCar::trademark) .' filter'); ?>
            </label>
        </p>

        <p>
            <label for="filter_<?php echo UTTTravelCar::date ?>">
                <input
                        id="filter_<?php echo UTTTravelCar::date ?>"
                    <?php echo isset($instance[UTTTravelCar::date]) && $instance[UTTTravelCar::date] == 'on' ? 'checked' : '' ?>
                        type="checkbox"
                        name="<?php echo $this->get_field_name( UTTTravelCar::date ); ?>"
                        value="on"> <?php _e('Enable '. strtoupper(UTTTravelCar::date) .' filter'); ?>
            </label>
        </p>

        <p>
            <label for="filter_<?php echo UTTTravelCar::seats ?>">
                <input
                        id="filter_<?php echo UTTTravelCar::seats ?>"
                    <?php echo isset($instance[UTTTravelCar::seats]) && $instance[UTTTravelCar::seats] == 'on' ? 'checked' : '' ?>
                        type="checkbox"
                        name="<?php echo $this->get_field_name( UTTTravelCar::seats ); ?>"
                        value="on"> <?php _e('Enable '. strtoupper(UTTTravelCar::seats) .' filter'); ?>
            </label>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance = wp_parse_args( $new_instance, array(
            UTTTravelCar::trademark => '',
            UTTTravelCar::date => '',
            UTTTravelCar::seats => '',
        ) );

        $instance = $old_instance;
        $instance[UTTTravelCar::trademark] = ( isset($new_instance[UTTTravelCar::trademark]) && $new_instance[UTTTravelCar::trademark] == 'on' ? $new_instance[UTTTravelCar::trademark] : '' );
        $instance[UTTTravelCar::date] = ( isset($new_instance[UTTTravelCar::date]) && $new_instance[UTTTravelCar::date] == 'on' ? $new_instance[UTTTravelCar::date] : '' );
        $instance[UTTTravelCar::seats] = ( isset($new_instance[UTTTravelCar::seats]) && $new_instance[UTTTravelCar::seats] == 'on' ? $new_instance[UTTTravelCar::seats] : '' );

        return $instance;
    }
}
add_action( 'widgets_init', function(){
    register_widget( 'UTTCarWidgetFilter' );
});