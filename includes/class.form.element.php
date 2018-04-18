<?php 

class UTTFormElemt
{
    /*
     *	JavaScript Wordpress editor
     *	Author: 		Ante Primorac
     *	Author URI: 	http://anteprimorac.from.hr
     *	Version: 		1.1
     *	License:
     *		Copyright (c) 2013 Ante Primorac
     *		Permission is hereby granted, free of charge, to any person obtaining a copy
     *		of this software and associated documentation files (the "Software"), to deal
     *		in the Software without restriction, including without limitation the rights
     *		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     *		copies of the Software, and to permit persons to whom the Software is
     *		furnished to do so, subject to the following conditions:
     *
     *		The above copyright notice and this permission notice shall be included in
     *		all copies or substantial portions of the Software.
     *
     *		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     *		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     *		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     *		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     *		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     *		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     *		THE SOFTWARE.
     *	Usage:
     *		server side(WP):
     *			js_wp_editor( $settings );
     *		client side(jQuery):
     *			$('textarea').wp_editor( options );
     */

    public static function js_wp_editor( $settings = array() ) {
        $ap_vars = array(
            'url' => get_home_url(),
            'includes_url' => includes_url()
        );

        wp_register_script( 'utt_editor_js', plugin_dir_url(UTT_PATH) . '/asset/admin/js/js-wp-editor.js', array( 'jquery' ), '0.1', true );
        wp_localize_script( 'utt_editor_js', 'ap_vars', $ap_vars );
        wp_enqueue_script( 'utt_editor_js' );
    }

    public static function galleries($post, $key)
    {
        ?>
        <div class="uttGalleries" id="<?php echo $key ?>_wrapper">
            <ul class="ul_images" data-refreshinput="#<?php echo $key ?>">
                <?php
                if ( metadata_exists( 'post', $post->ID, $key ) ) {
                    $image_gallery = get_post_meta( $post->ID, $key, true );
                } else {
                    // Backwards compat
                    $attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' );
                    $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
                    $image_gallery = implode( ',', $attachment_ids );
                }

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
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image' ) . '">' . __( 'Delete' ) . '</a></li>
								</ul>
							</li>';

                        // rebuild ids to be saved
                        $updated_gallery_ids[] = $attachment_id;
                    }

                    // need to update product meta to set new gallery ids
                    if ( $update_meta ) {
                        update_post_meta( $post->ID, $key, implode( ',', $updated_gallery_ids ) );
                    }
                }
                ?>
            </ul>

            <input type="hidden" id="<?php echo $key ?>" class="recieverGalleries" name="<?php echo $key ?>" value="<?php echo esc_attr( $image_gallery ); ?>" />

            <p class="add_galleries hide-if-no-js">
                <a href="#"
                   data-choose="<?php esc_attr_e( 'Add images to tour gallery' ); ?>"
                   data-update="<?php esc_attr_e( 'Add to gallery' ); ?>"
                   data-delete="<?php esc_attr_e( 'Delete image' ); ?>"
                   data-text="<?php esc_attr_e( 'Delete' ); ?>">
                    <?php _e( 'Add tour gallery images' ); ?>
                </a>
            </p>

        </div>

        <?php
    }

}