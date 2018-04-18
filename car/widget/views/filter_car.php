<div class="ut-sidebar ut-filter">
    <form action="">
        <?php foreach ($instance as $key => $item): ?>
            <?php

            if ($item == 'on')  :
                $terms = get_terms(array(
                    'taxonomy' => $key
                ))
                ?>
                <div class="ut-widget">
                    <h3 class="ut-widget__heading">
                        <?php echo strtoupper($key) ?>
                    </h3>
                    <?php if($terms) : ?>
                        <ul class="ut-ul ut-filter__list">
                            <?php foreach ($terms as $term) : ?>
                                <li class="ut-filter__item">
                                    <label class='ut-checkbox-group'>
                                        <input
                                                <?php
                                                $keyFilter = UTTTravelRequest::getQuery('f', array());
                                                if (isset($keyFilter[$key]) && is_array($keyFilter[$key]) && in_array($term->term_id, $keyFilter[$key])) {
                                                    echo 'checked';
                                                }
                                                ?>
                                                name="f[<?php echo $key ?>][]"
                                                onchange="form.submit()" type="checkbox" value='<?php echo $term->term_id ?>'>
                                        <span><?php echo $term->name ?></span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </form>
</div>