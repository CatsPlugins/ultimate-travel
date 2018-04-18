<?php

// /wp-includes/comment-template.php Line 1470
add_filter( "comments_template", "CatsRating::layoutForm" );

// /wp-includes/comment-template.php Line 2308
add_action('comment_form_top', "CatsRating::addRating");


add_action('comment_post', "CatsRating::saveMetaComment" );

class CatsRating {
    public static function layoutForm($theme_template){
        $check_dirs = array(
            trailingslashit( get_stylesheet_directory() ) . UTTConfig::TEMPLATE . '/',
            trailingslashit( get_template_directory() ) . UTTConfig::TEMPLATE . '/',
            trailingslashit( dirname(UTT_PATH) . '/templates/')
        );

        foreach ( $check_dirs as $dir ) {
            if ( file_exists( trailingslashit( $dir ) . 'comments.php' ) ) {
                return trailingslashit( $dir ) . 'comments.php';
            }
        }

        return $theme_template;
    }


    public static function addRating(){
        $postType = get_post_type(get_the_ID());

        $ratingsCriteria = apply_filters("ratings_criteria_{$postType}", array());
        $ratingsCriteria = array_filter($ratingsCriteria);

        if (is_array($ratingsCriteria) && count($ratingsCriteria) > 0) :
            echo '<div class="ratingInput">';
            foreach ($ratingsCriteria as $key => $item) :
                $key = sanitize_key($key); ?>

                <div class="itemCol">
                    <label for=""><?php echo $item ?></label>
                    <input class="recieverRatingValue" type="hidden" name="cats-rating[<?php echo $key ?>]" value="0">
                    <div class="groupStartBtn">
                        <button type="button" onclick="CatSetRating.setRatingToInput(event)" class="startbtn">1 start</button>
                        <button type="button" onclick="CatSetRating.setRatingToInput(event)" class="startbtn">2 start</button>
                        <button type="button" onclick="CatSetRating.setRatingToInput(event)" class="startbtn">3 start</button>
                        <button type="button" onclick="CatSetRating.setRatingToInput(event)" class="startbtn">4 start</button>
                        <button type="button" onclick="CatSetRating.setRatingToInput(event)" class="startbtn">4 start</button>
                    </div>
                </div>

            <?php  endforeach;
            echo '</div>';
        endif;
    }

    public static function saveMetaComment($comment_id)
    {
        if ( ( isset( $_POST['cats-rating'] ) ) && ( $_POST['cats-rating'] != '') ) {

            $comment = get_comment($comment_id);
            $comment_post_id = $comment->comment_post_ID ;

            $catsRatingData =  $_POST['cats-rating'];

            $dataRatingTotal = array(
                'count' => 0,
                'number_start' => 0
            );

            if(is_array($catsRatingData) && count($catsRatingData) > 0) {
                foreach ($catsRatingData as $key => $item) {
                    if ((int)$item > 0) {
                        $dataRatingTotal['count']++;
                        $dataRatingTotal['number_start'] += (int)$item;

                        update_comment_meta( $comment_id, $key, (int)$item );
                    }
                }
            }

            $valueAvg = 0;
            if ($dataRatingTotal['count'] > 0 && $dataRatingTotal['number_start'] > 0) {
                $valueAvg = ceil($dataRatingTotal['number_start'] / $dataRatingTotal['count']);
            }

            $author_id = get_post_field ('post_author', $comment_post_id);

            update_comment_meta( $comment_id, 'cats_comment_rating_avg', $valueAvg );
            update_comment_meta( $comment_id, 'cats_comment_auth', $author_id );



            $commentsForPost = array(
                'post_id' => $comment_post_id,
            );

            $postType = get_post_type($comment_post_id);
            $ratingsCriteria = apply_filters("ratings_criteria_{$postType}", array());

            $comments = get_comments( $commentsForPost );
            if ($dataRatingTotal['count'] > 0) {

                if ($comments) {
                    $postTotalComNew = 0;
                    $totalStart = 0;

                    foreach ($comments as $comment) {

                        $_total = 0;
                        $_count = 0;
                        foreach ($ratingsCriteria as $key => $label) {
                            $value = get_comment_meta($comment->comment_ID, sanitize_key($key), true);
                            if ($value > 0) {
                                $_total += $value;
                                $_count ++;
                            }
                        }

                        $valueAvg = 0;
                        if ($_count > 0 && $_total > 0) {
                            $valueAvg = ceil($_total / $_count);

                            update_comment_meta( $comment_id, 'cats_comment_rating_avg', $valueAvg );

                            $postTotalComNew ++;
                            $totalStart += $valueAvg;
                        }
                    }

                    $avg = ceil($totalStart / $postTotalComNew);

                    update_post_meta($comment_post_id, 'cats_total_rating', $postTotalComNew);
                    update_post_meta($comment_post_id, 'cats_avg_rating', $avg);
                }
            }
        }
    }
}






//EDIT TEMPLATE COMMENT
class CatsCommentWalker extends Walker_Comment
{

    protected function getRatingOfComment($comment_id)
    {
        $comment = get_comment($comment_id);
        $comment_post_id = $comment->comment_post_ID ;
        $postType = get_post_type($comment_post_id);

        $cats_comment_rating_avg = get_comment_meta($comment_id, 'cats_comment_rating_avg', true);

        $ratingsCriteria = apply_filters("ratings_criteria_{$postType}", array());
        $ratingsCriteriaValue = array();
        if (count($ratingsCriteria) > 0){
            foreach ($ratingsCriteria as $key => $item) {
                $key = sanitize_key($key);
                $ratingsCriteriaValue[] = array(
                    'key' => $key,
                    'label' => $item,
                    'rating_avg' => get_comment_meta($comment_id, $key, true)
                );
            }
        }


        if ($cats_comment_rating_avg > 0) {
            echo "<div class='groupDisplayRatingCriteria'>";
                foreach ($ratingsCriteriaValue as $key => $value) {
                    echo "<div class='itemCol'>";
                    echo "<div class='labelName'>{$value['label']}</div>";
                    echo "<div class='valueRating'>{$this->getRatingDisplayTemplate($value['rating_avg'])}</div>";
                    echo "</div>";
                }
            echo "</div>";

        }
    }

    private  function getRatingDisplayTemplate($number)
    {
        $html = '';
        for($i = 1; $i <= 5; $i++) {
            if ($i <= $number) {
                $html .= "<div class='ratingAvg catStartRatingItem active'></div>";
            } else {
                $html .= "<div class='ratingAvg catStartRatingItem'></div>";
            }
        }

        return $html;
    }

    protected function comment( $comment, $depth, $args ) {
        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">

        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>


            <div class="comment-author vcard">
                <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            </div>

            <div class="comment-metadata">
                <?php
                /* translators: %s: comment author link */
                printf( __( '%s <span class="says">says:</span>' ),
                    sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
                );
                ?>
                <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                    <time datetime="<?php comment_time( 'c' ); ?>">
                        <?php
                        /* translators: 1: comment date, 2: comment time */
                        printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
                        ?>
                    </time>
                </a>
                <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                <?php endif; ?>



                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <div class="catsRatingValue">
                    <?php $this->getRatingOfComment($comment->comment_ID); ?>
                </div>

                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply">',
                    'after'     => '</div>'
                ) ) );
                ?>
            </div><!-- .comment-metadata -->


        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
        <?php
    }




    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">


                <div class="comment-author vcard">
                    <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>

                </div><!-- .comment-author -->



                <div class="comment-metadata">
                    <?php
                    /* translators: %s: comment author link */
                    printf( __( '%s <span class="says">says:</span>' ),
                        sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
                    );
                    ?>
                    <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            /* translators: 1: comment date, 2: comment time */
                            printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>

                    <?php if ( '0' == $comment->comment_approved ) : ?>
                        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                    <?php endif; ?>

                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div><!-- .comment-content -->

                    <div class="catsRatingValue">
                        <?php $this->getRatingOfComment($comment->comment_ID); ?>
                    </div>

                    <?php
                    comment_reply_link( array_merge( $args, array(
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<div class="reply">',
                        'after'     => '</div>'
                    ) ) );
                    ?>
                </div><!-- .comment-metadata -->

            </footer><!-- .comment-meta -->

        </article><!-- .comment-body -->
        <?php
    }
}
