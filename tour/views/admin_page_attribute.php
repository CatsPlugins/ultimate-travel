
<div class="wrap woocommerce">
    <h1><?php _e('Attributes'); ?></h1>

    <?php UTTFlasSession::output() ?>

    <br class="clear">
    <div id="col-container">

        <div id="col-right">
            <div class="col-wrap">
                <table class="widefat attributes-table wp-list-table ui-sortable" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col"><?php _e('Name', 'ultimate-travel') ?></th>
                        <th scope="col"><?php _e('Slug', 'ultimate-travel') ?></th>
                        <th scope="col"><?php _e('Public', 'ultimate-travel') ?></th>
                        <th scope="col"><?php _e('Terms', 'ultimate-travel') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attributes as $item) : ?>
                            <tr>
                                <td>
                                    <a href="edit-tags.php?taxonomy=<?php echo esc_html( $item->attribute_name ); ?>&post_type=tour"><b><?php echo $item->attribute_label ?></b></a>
                                    <div class="row-actions">
                                        <span class="edit"><a href="<?php echo esc_url( add_query_arg(array('typeaction' => 'edit', 'id' => $item->attribute_id)) ); ?>"><?php _e('Edit') ?></a>  | </span>
                                        <span class="delete"><a href="<?php echo esc_url( add_query_arg(array('typeaction' => 'delete', 'id' => $item->attribute_id)) ); ?>"><?php _e('Delete') ?></a></span>
                                    </div>
                                </td>
                                <td><?php echo $item->attribute_name ?></td>
                                <td><?php echo $item->attribute_public ?></td>
                                <td>
                                    <?php
                                    $taxonomy = esc_html($item->attribute_name);

                                    if ( taxonomy_exists( $taxonomy ) ) {
                                        $terms = get_terms( $taxonomy, 'hide_empty=0&menu_order=ASC' );
                                        $terms_string = implode( ', ', wp_list_pluck( $terms, 'name' ) );
                                        if ( $terms_string ) {
                                            echo $terms_string;
                                        } else {
                                            echo '<span class="na">&ndash;</span>';
                                        }
                                    } else {
                                        echo '<span class="na">&ndash;</span>';
                                    }
                                    ?>
                                    <br />
                                    <a href="edit-tags.php?taxonomy=<?php echo esc_html($item->attribute_name); ?>&amp;post_type=tour" class="configure-terms"><?php _e( 'Configure terms' ); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h2><?php _e('Add new attribute') ?></h2>
                    <p><?php _e('Attributes') ?></p>

                    <form action="" method="post" onsubmit="createAttributeTax(event)">
                        <div class="form-field">
                            <label for="attribute_label">Name</label>
                            <input name="form[attribute_label]" id="attribute_label" type="text" value="">
                            <p class="description">Name for the attribute (shown on the front-end).</p>
                        </div>

                        <div class="form-field">
                            <label for="attribute_name">Slug</label>
                            <input name="form[attribute_name]" id="attribute_name" type="text" value="" maxlength="28">
                            <p class="description">Unique slug/reference for the attribute; must be no more than 28 characters.</p>
                        </div>

                        <div class="form-field">
                            <label for="attribute_public">
                            <input name="form[attribute_public]" id="attribute_public" type="checkbox" value="1"> Enable Archives?</label>

                            <p class="description">Enable this if you want this attribute to have product archives in your store.</p>
                        </div>

                        <p class="submit">
                            <input type="submit" name="add_new_attribute" id="submit" class="button button-primary" value="Add attribute">
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        /* <![CDATA[ */
        var $ = jQuery;

        jQuery( '.delete a' ).click( function() {
            if( window.confirm( '<?php _e('Are you sure you want to delete this attribute?') ?>' ) ) {
                return true;
            }
            return false;
        });

        function createAttributeTax(event) {
            event.preventDefault();

            var data = $(event.target).serialize();

            var t = $('[name="add_new_attribute"]').text();
            $('[name="add_new_attribute"]').text('Adding...');

            $.post(ajaxurl + '?action=createattributetax', data, function (res) {
                location.reload();
            }).done(function(){
                $('[name="add_new_attribute"]').text(t);
            });
        }

        /* ]]> */
    </script>
</div>

<div class="clear"></div>
