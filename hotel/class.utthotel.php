<?php
class utthotel
{
    public $hotel;
    public $postType;

    function __construct($id)
    {
        global $utthotel;

        $this->tour = get_post($id);
        $this->postType = UTTTravelHotel::postType;
        $this->metaData = get_post_meta($id, UTTTravelHotel::keyMeta, true);

        $utthotel = $this;
    }

    function getGalleries()
    {
        $galleries = get_post_meta(get_the_ID(), UTTTravelHotel::keyGallery, true);
        $galleries = explode(',', $galleries);
        $output = array();
        foreach ($galleries as $key => $value) {
            $output[] = wp_get_attachment_image_src($value, 'full')[0];
        }

        return array_filter($output);
    }

    function getRatingData()
    {
        $totalTourRating = get_post_meta($this->tour->ID, 'cats_total_rating', true);
        $avgTourRating = get_post_meta($this->tour->ID, 'cats_avg_rating', true);

        return array(
            'total' => (!$totalTourRating ? 0 : $totalTourRating),
            'avg' => (!$avgTourRating ? 0 : $avgTourRating)
        );
    }

    function getServices($key = '')
    {
        if ( isset($this->metaData['utthotelservice'][$key]) ) {
            return $this->metaData['utthotelservice'][$key];
        } else if ($key == ''){
            return isset($this->metaData['utthotelservice']) ? $this->metaData['utthotelservice'] : array();
        } else {
            return array();
        }
    }

    function getRoms($key = '')
    {
        if ( isset($this->metaData['utthotelroms'][$key]) ) {
            return $this->metaData['utthotelroms'][$key];
        } else if ($key == '') {
            return (isset($this->metaData['utthotelroms']) ? $this->metaData['utthotelroms'] : array());
        } else {
            return array();
        }
    }

    function getBookingData($key = '')
    {
        if (isset($this->metaData['metabooking'][$key])) {
            return $this->metaData['metabooking'][$key];
        } else {
            return '';
        }
    }
}