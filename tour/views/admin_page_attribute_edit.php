<div class="wrap woocommerce">
    <h1><?php _e('Edit Attributes'); ?></h1>

    <br class="clear">
    <div id="col-container">

        <div class="col-wrap">
            <div class="form-wrap">
                <h2><?php _e('Add new attribute') ?></h2>

                <?php UTTFlasSession::output() ?>

                <p><?php _e('Attributes') ?></p>

                <form action="" method="post">
                    <input type="hidden" name="form[attribute_id]" value="<?php echo $attibuteDetail->attribute_id ?>">
                    <div class="form-field">
                        <label for="attribute_label">Name</label>
                        <input name="form[attribute_label]" id="attribute_label" type="text" value="<?php echo $attibuteDetail->attribute_label ?>">
                        <p class="description">Name for the attribute (shown on the front-end).</p>
                    </div>

                    <div class="form-field">
                        <label for="attribute_name">Slug</label>
                        <input name="form[attribute_name]" id="attribute_name" type="text" value="<?php echo $attibuteDetail->attribute_name ?>">
                        <p class="description">Unique slug/reference for the attribute; must be no more than 28 characters.</p>
                    </div>

                    <div class="form-field">
                        <label for="attribute_public">
                            <input
                                <?php echo $attibuteDetail->attribute_public == '1' ? 'checked' : '' ?>
                                name="form[attribute_public]" id="attribute_public" type="checkbox" value="1"> Enable Archives?</label>

                        <p class="description">Enable this if you want this attribute to have product archives in your store.</p>
                    </div>
                    <input type="hidden" name="form[origin_taxonomy]" value="<?php echo $attibuteDetail->attribute_name ?>">
                    <p class="submit">
                        <input type="submit" name="add_new_attribute" id="submit" class="button button-primary" value="Edit attribute">
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

