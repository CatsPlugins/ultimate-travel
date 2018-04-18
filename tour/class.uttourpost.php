<?php


class uttour {

    public $tour;
    public $postType;

    function __construct($id)
    {
        global $uttour;

        $this->tour = get_post($id);
        $this->postType = UTTTravelTour::$postType;
        $this->metaData = get_post_meta($id, UTTTravelTour::$keyMeta, true);

        $uttour = $this;

        if (count($this->getSchedules(false)) == 0) {
            $postMetaBookingData = get_post_meta($this->tour->ID, UTTTravelTour::metaKeyBooking, true);
            $postMetaBookingData['price_children'] = 0;
            $postMetaBookingData['price_children_sale'] = 0;

            update_post_meta($this->tour->ID, '_sale_price', 0);
            update_post_meta($this->tour->ID, '_regular_price', 0);
            update_post_meta($this->tour->ID, UTTTravelTour::metaKeyBooking, $postMetaBookingData);
        }
    }

    function getScheduleRecent($format = true)
    {
        $schedules = $this->getSchedules($format);
        if(isset($schedules[0])) {
            return $schedules[0];
        } else {
            return '';
        }
    }

    function getSchedules($format = true)
    {
        $output = array();
        $schedules = $this->getBookingData('schedule');
        if (is_array($schedules) && count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                if ($schedule <= strtotime('now') ) {
                    delete_post_meta($this->tour->ID, 'schedule', $schedule);
                    continue;
                } else {
                    $output[] = $schedule;
                }
            }
        }

        $output = array_unique($output);
        sort($output);

        if ($format) {
            foreach ($output as $key => $item) {
                $output[$key] = date(get_option('date_format') . ' ' . get_option('time_format'), $item);
            }
        }

        return $output;
    }

    public function getHotel($field)
    {
        $idHotel = (int)$this->getBookingData('booking_hotels');
        if ($idHotel > 0) {
            switch ($field) {
                case 'title':
                    return get_the_title($idHotel);
                    break;
                case 'link':
                    return get_permalink($idHotel);
                    break;
                default:
                    return '';
            }
        } else {
            return '';
        }
    }

    function getPriceHtml()
    {
        $price = $this->getPriceAdults();

        if ($price['price'] == 0) {
            $price['price'] = apply_filters('text_price_free', 'Contact');
        } else {
            $price['price'] = UTTCurrencyPosition(UTTCurrencyFormat($price['price']));
        }

        ob_start();
        ?>

            <?php if ($price['price_origin'] > 0): ?>
                <del class='ut-product__compare-price'>
                    <?php echo UTTCurrencyPosition(UTTCurrencyFormat($price['price_origin'])) ?>
                </del>
            <?php endif; ?>

            <ins class='ut-product__price'>
                <?php echo $price['price'] ?>
            </ins>

        <?php

        return ob_get_clean();
    }

    function checkLastMinute($format = true)
    {
        $schedules = $this->getScheduleRecent(false);
        if($schedules > 0) {
            $date = get_option('tour_last_minutes', 5);
            if ($date == 'notset') {
                return false;
            }

            $timeToday = strtotime('+'. $date .' day');

            if ($schedules <= $timeToday) {
                if($format) {
                    return date(get_option('date_format') . ' ' . get_option('time_format'), $schedules);
                } else {
                    return $schedules;
                }

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getPriceAdults($key = '')
    {
        $priceS = get_post_meta($this->tour->ID, '_sale_price', true);
        $priceR = get_post_meta($this->tour->ID, '_regular_price', true);

        

        if ($priceS > 0) {
            $price = array(
                'price' =>  $priceS,
                'price_origin' => $priceR
            );
        } else {
            $price = array(
                'price' =>  $priceR,
                'price_origin' => 0
            );
        }

        if (isset($price[$key])) {
            return $price[$key];
        } else {
            return $price;
        }
    }

    function getPriceChildren($key)
    {
        $postMetaBookingData = get_post_meta($this->tour->ID, UTTTravelTour::metaKeyBooking, true);

        $price = array(
            'price' =>  '',
            'price_origin' => ''
        );
        $price['price_origin'] = @$postMetaBookingData['price_children'];
        $price['price'] = @$postMetaBookingData['price_children'];

        if (isset($postMetaBookingData['price_children_sale']) && $postMetaBookingData['price_children_sale'] > 0) {
            $price['price'] = $postMetaBookingData['price_children_sale'];
        }

        if (isset($price[$key])) {
            return $price[$key];
        } else {
            return $price;
        }
    }

    function getBookingData($key = '', $default = '')
    {
        $postMetaBookingData = get_post_meta($this->tour->ID, UTTTravelTour::metaKeyBooking, true);

        $postMetaBookingData['price_adults_booking']= $this->getPriceAdults('price');
        $postMetaBookingData['price_children_booking']= $this->getPriceChildren('price');

        if ($key == '') {
            return $postMetaBookingData;
        }

        if (isset($postMetaBookingData[$key])) {
            return $postMetaBookingData[$key];
        } else {
            return $default;
        }

    }

    function getTourContent()
    {
        return get_post_meta($this->tour->ID, UTTTravelTour::$keyMetaTourDetail, true);;
    }

    function getGalleries()
    {
        $_tourGalleries = get_post_meta(get_the_ID(), UTTTravelTour::$keyMetaGallery, true);
        $_tourGalleries = explode(',', $_tourGalleries);
        $tourGalleries = array();
        foreach ($_tourGalleries as $key => $value) {
            $tourGalleries[] = wp_get_attachment_image_src($value, UTTConfig::SIZE_BIG)[0];
        }

        return $tourGalleries;
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

    function getMeta($key, $default = '')
    {
        if (isset($this->metaData[$key])) {
            return $this->metaData[$key];
        } else {
            return $default;
        }
    }

    function getRegions()
    {
        $regions = wp_get_post_terms($this->tour->ID, UTTTravelTour::regionTour);
        $output = array();
        if ($regions) {
            foreach ($regions as $re) {
                $output[] = $re;
            }
        }
        return $output;
    }
}