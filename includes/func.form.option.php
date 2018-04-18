<?php

function utttravel_locationpicker($key, $data){
?>
    <div class="locationPicker">
        <input type="hidden" name="<?php echo $key ?>[lng]" class="mainInputLng" value="<?php echo @$data['lng'] ?>">
        <input type="hidden" name="<?php echo $key ?>[lat]" class="mainInputLat" value="<?php echo @$data['lat'] ?>">
        
        <input autocomplete="false" style="width: 100%" oninput="UttSearchMap(event)" type="text" class="mainInput" 
            placeholder="<?php echo @$data['placeholder'] ?>" 
            value="<?php echo @$data['address'] ?>" 
            name="<?php echo $key ?>[address]"
            >  
        <div class="resultMap">
            
        </div>
    </div>

<?php 
}

function utttravel_multiple_image($keyName, $data) {
    global $post;

    ?>
    <div class="uttGalleries" id="<?php echo $keyName ?>_wrapper">
        <ul class="ul_images" data-refreshinput="#<?php echo $keyName ?>">
            <?php

            $image_gallery = $data['default'];

            $attachments         = array_filter( explode( ',', $image_gallery ) );
            $update_meta         = false;
            $updated_gallery_ids = array();

            if ( ! empty( $attachments ) ) {
                foreach ( $attachments as $attachment_id ) {
                    $attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

                    // if attachment is empty skip
                    if ( empty( $attachment ) ) {
                        $update_meta = true;
                        continue;
                    }

                    echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
                            ' . $attachment . '
                            <ul class="actions">
                                <li>
                                <a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image' ) . '">' . __( 'Delete' ) . '</a></li>
                            </ul>
                        </li>';

                    // rebuild ids to be saved
                    $updated_gallery_ids[] = $attachment_id;
                }

                // need to update product meta to set new gallery ids
                if ( $update_meta ) {
                    update_post_meta( $post->ID, $keyName, implode( ',', $updated_gallery_ids ) );
                }
            }
            ?>
        </ul>

        <input type="hidden"
               id="<?php echo esc_attr($keyName); ?>"
               class="recieverGalleries"
               name="<?php echo esc_attr($keyName); ?>"
               value="<?php echo esc_attr( $image_gallery ); ?>"
        >

        <p class="add_galleries hide-if-no-js">
            <a href="#"
               data-choose="<?php esc_attr_e( 'Add images to tour gallery' ); ?>"
               data-update="<?php esc_attr_e( 'Add to gallery' ); ?>"
               data-delete="<?php esc_attr_e( 'Delete image' ); ?>"
               data-text="<?php esc_attr_e( 'Delete' ); ?>">
                <?php _e( 'Add gallery images' ); ?>
            </a>
        </p>

    </div>
<?php }

function utttravel_editor($key, $data) {
    $config = array(
        'wpautop'       => true,
        'media_buttons' => true,
        'textarea_name' => $key,
        'textarea_id' => $key,
        'editor_height' => 300,
        'teeny'         => false
    );
    wp_editor( html_entity_decode($data['default']), sanitize_title($key), $config );
}
function utttravel_image( $key, $data ) {
    $image = 'Upload image';
    $image_size = 'thumbnail'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state ot the "Remove image" button
    $imageHtml = '';
    if( $image_attributes = wp_get_attachment_image_src( $data['default'], $image_size ) ) {
        $imageHtml = '<img style="max-height: 100px; display: block" src="' . $image_attributes[0] . '" />';
        $display = 'inline-block';

    }
    $uniid = md5($key);
    $functionUpload = 'UTTuploadImage(event, \'#utl_upload_' . $uniid . '\', \'#utt_preview_'. $uniid .'\', \'#utt_delete_'. $uniid .'\')';
    $functionReset = 'UTTuploadImageReset(event, \'#utl_upload_' . $uniid . '\', \'#utt_preview_'. $uniid .'\', \'#utt_delete_'. $uniid .'\')';

    echo '
    <div>
        <div class="preivewImage" id="utt_preview_'. $uniid .'">'. $imageHtml .'</div>
        <a href="#" onclick="' . $functionUpload . '" class="button">'.  $image.'</a>
        <input type="hidden" name="' . $key . '" id="utl_upload_' . $uniid . '" value="' . $data['default'] . '" />
        <a href="#" onclick="'. $functionReset .'" id="utt_delete_'. $uniid .'" class="button button-link" style="display:inline-block;display:' . $display . '">Remove image</a>
    </div>';
}

function utttravel_checkbox($key, $data) {
    echo '<input type="checkbox" '. ($data['default'] == 'on' ? 'checked' : '') .' name="'. $key .'">';
}

function utttravel_icontravel($key, $data) {
    $uniqId = uniqid();
    ?>
    <?php add_thickbox(); ?>

    <div class="wrapPopupinline">
        <span class="valueSelected">
            <?php
                if (isset($data['default']) && !empty($data['default']))  {
                    echo "<button class='button'><i class='{$data['default']}'></i><input type='hidden' name='{$key}' value='{$data['default']}'></button>";
                }
            ?>
        </span>
        <button
                type="button"
                title="<?php _e('Select a icon', 'ultimate-travel') ?>"
                class="button buttonIcon"
                onclick="jQuery('#icon-travel-<?php echo $uniqId ?>').toggle()"
        >
            <?php _e('Select icon', 'ultimate-travel') ?>
        </button>

        <div id="icon-travel-<?php echo $uniqId ?>" style="display:none;" class="contentPopup">
            <div class="overlayPopup"></div>
            <div style="" class="iconTravelSelect">
                <?php
                $icons = UTTConfig::getIconTravel();
                foreach ($icons as $iconClass) {
                    echo "<button data-nameinput='{$key}' data-iconclass='{$iconClass}' type='button' class=\"button\"><i class=\"glyph-icon {$iconClass}\"></i></button>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php
}


function utttravel_text($key, $data) {
    echo '<input class="uttinput '. (isset($data['class']) ? $data['class'] : '') .'" type="text" name="'. $key .'" value="'. $data['default'] .'" placeholder="'. (isset($data['placeholder']) ? $data['placeholder'] : '') .'">';
}
function utttravel_number($key, $data) {
    echo '<input class="uttinput '. (isset($data['class']) ? $data['class'] : '') .'" type="number" name="'. $key .'" value="'. $data['default'] .'" placeholder="'. (isset($data['placeholder']) ? $data['placeholder'] : '') .'">';
}
function utttravel_textarea($key, $data) {
    $attribute = '';
    if (isset($data['attributes'])) {
        if (is_array($data['attributes'])) {
            foreach ($data['attributes'] as $name => $item) {
                $attribute[] = $name . '="' . $item . '"';
            }

            $attribute = implode(' ', $attribute);
        } else{
            $attribute = $data['attributes'];
        }
    }
    echo '<textarea '. $attribute .' name="'. $key .'" class="'. (isset($data['class']) ? $data['class'] : '') .'" id="'. (isset($data['id']) ? $data['id'] : '') .'" rows="2">'. $data['default'] .'</textarea>';
}

function utttravel_html($key, $data) {
    echo $data['content'];
}

function utttravel_hr($data) {
    if ( isset($data['label']) ) {
        echo '<tr><td></td><td style="color: red">'.  $data['label'] .'</td></tr>';
    } else {
        echo '<tr><td style="text-align: left" colspan="2">&#160</td></tr>';
    }

}
function utttravel_select($key, $data) {

    $enselect2 = ( isset($data['select2']) && $data['select2'] == true ? 'en-select2' : '' );
    $ajaxUrl = ( isset($data['select2_ajax']) && $data['select2_ajax'] == true ? $data['select2_ajax'] : '' );
    $Multi = ( isset($data['multiple']) && $data['multiple'] == true ? 'multiple' : '' );
    if ($Multi == 'multiple') {
        $key = $key .'[]';
    }

    if ($ajaxUrl != '') {
        $ajaxUrl = 'data-ajax="'. $ajaxUrl .'"';
    }
    $attribute = '';
    if (isset($data['attributes'])) {
        if (is_array($data['attributes'])) {
            foreach ($data['attributes'] as $name => $item) {
                $attribute[] = $name . '="' . $item . '"';
            }

            $attribute = implode(' ', $attribute);
        } else{
            $attribute = $data['attributes'];
        }
    }
    echo (isset($data['before']) ? $data['before'] : '');
    echo '<select '. $attribute .' '.$ajaxUrl.' name="'. $key .'" class="uttinput '. $enselect2 .'"  '. $Multi  .'>';
    foreach ($data['options'] as $key => $value) {

        $select = '';
        if (is_array($data['default'])) {
            if (in_array($key, $data['default'])) {
                $select = 'selected';
            } else {
                $select = '';
            }
        } else {
            if($data['default'] == $key) {
                $select = 'selected';
            } else {
                $select = '';
            }
        }
        echo '<option '. $select .'  value="'. $key .'">'. $value .'</option>';
    }
    echo '</select>';
    echo (isset($data['after'])? $data['after'] : '');
}

function utttravel_uttdatepicker($key, $data) {
    ob_start();
    $time = strtotime('+1 month');
    $month = date('m', $time);
    $year = date('Y', $time);
    $date = date('d', $time);
    ?>

    <div class="timestamp-wrap">
        <label>
            <span class="screen-reader-text">Month</span>
            <select name="" class="month">
                <option value="01" <?php echo ( $month == 1 ? 'selected' : '') ?> data-text="Jan">01-Jan</option>
                <option value="02" <?php echo ( $month == 2 ? 'selected' : '') ?> data-text="Feb">02-Feb</option>
                <option value="03" <?php echo ( $month == 3 ? 'selected' : '') ?> data-text="Mar">03-Mar</option>
                <option value="04" <?php echo ( $month == 4 ? 'selected' : '') ?> data-text="Apr">04-Apr</option>
                <option value="05" <?php echo ( $month == 5 ? 'selected' : '') ?> data-text="May">05-May</option>
                <option value="06" <?php echo ( $month == 6 ? 'selected' : '') ?> data-text="Jun">06-Jun</option>
                <option value="07" <?php echo ( $month == 7 ? 'selected' : '') ?> data-text="Jul">07-Jul</option>
                <option value="08" <?php echo ( $month == 8 ? 'selected' : '') ?> data-text="Aug">08-Aug</option>
                <option value="09" <?php echo ( $month == 9 ? 'selected' : '') ?> data-text="Sep">09-Sep</option>
                <option value="10" <?php echo ( $month == 10 ? 'selected' : '') ?> data-text="Oct">10-Oct</option>
                <option value="11" <?php echo ( $month == 11 ? 'selected' : '') ?> data-text="Nov">11-Nov</option>
                <option value="12" <?php echo ( $month == 12 ? 'selected' : '') ?> data-text="Dec">12-Dec</option>
            </select>
        </label>

        <label>
            <span class="screen-reader-text">Day</span>
            <input type="text" class="day" name="" value="<?php echo $date ?>" size="2" maxlength="2" autocomplete="off">
        </label>,

        <label>
            <span class="screen-reader-text">Year</span>
            <input type="text" class="year" value="<?php echo $year ?>" size="4" maxlength="4" autocomplete="off">
        </label>

        @

        <label>
            <span class="screen-reader-text">Hour</span>
            <input type="text" class="hour" value="08" size="2" maxlength="2" autocomplete="off">
        </label>

        :

        <label>
            <span class="screen-reader-text">Minute</span>
            <input type="text"  class="minute" value="00" size="2" maxlength="2" autocomplete="off">
        </label>

        <button class="button button-primary" onclick="uttAddTime(event)"><?php _e('Add') ?></button>
        <div class="clear clearfix"></div>

        <span class="templateSelected" style="display: none">
            <div class="itemTime">
                <input type="hidden" name="<?php echo $key ?>[__index__]" value="__value__">
                <span class="date">__date__</span>
                <span class="time">__time__</span>

                <button onclick="uttRemoveTime(event)" class="button deleteDate"><span class="dashicons dashicons-no-alt"></span></button>
            </div>
        </span>

        <div class="areaSelected">
            <?php if (is_array($data['default'])) : ?>
                <?php
                $loop = 0;
                foreach ($data['default'] as $time) :
                    $loop ++;
                    if(is_numeric($time) && $time > 0) :
                ?>
                    <div class="itemTime">
                        <input type="hidden" name="<?php echo $key ?>[<?php echo $loop ?>]" value="<?php echo $time; ?>">
                        <span class="date"><?php echo ( $time > 0 ? date('m/d/Y', $time) : '' ) ?></span>
                        <span class="time"><?php echo ( $time > 0 ? date('h:s', $time) : '' ) ?></span>

                        <button onclick="uttRemoveTime(event)" class="button deleteDate"><span class="dashicons dashicons-no-alt"></span></button>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php
    echo ob_get_clean();
}


function renderHtmlOption($configForm, $postMeta, $unikey)
{
    ob_start();
    ?>
    <table class="wp-list-table utttravel-metabox striped" style="min-width: 100%">
        <?php foreach ($configForm as $key => $value) :
            ?>
            <!-- TYPE HR -->
            <?php if ($value['type'] == 'hr') : utttravel_hr($value) ?>

                <!-- TYPE GROUP -->
            <?php elseif ($value['type'] == 'group') : ?>

                <?php foreach ($value['element'] as $_key => $_value) : ?>
                    <tr>
                        <td class="tdLabel">
                            <?php echo $_value['label'] ?>
                        </td>
                        <td>
                            <?php

                            $__key = $unikey . '['.$key.']' . '[' . $_key . ']';
                            if (isset($postMeta[$key]) && isset($postMeta[$key][$_key])) {
                                $_value['default'] = $postMeta[$key][$_key];
                            } else {
                                $_value['default'] = (isset($_value['default']) ? $_value['default'] : '');
                            }

                            $_function = 'utttravel_'.$_value['type'];

                            call_user_func($_function, $__key, $_value) ;
                            if (isset($value['desc'])) {
                                echo $value['desc'];
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- TYPE NORMAL -->
            <?php else : ?>
                <tr>
                    <td class="tdLabel">
                        <?php echo $value['label'] ?>
                    </td>
                    <td>
                        <?php

                        $_function = 'utttravel_'.$value['type'];
                        if (isset($postMeta[$key])) {
                            $value['default'] = $postMeta[$key];
                        } else {
                            $value['default'] =   (isset($value['default']) ? $value['default'] : '');
                        }

                        $_key = $unikey . '['. $key .']';
                        call_user_func($_function, $_key, $value) ;
                        if (isset($value['desc'])) {
                            echo $value['desc'];
                        }
                        ?>
                    </td>
                </tr>
            <?php endif ?>
        <?php endforeach; ?>

    </table>
    <?php

    return ob_get_clean();
}