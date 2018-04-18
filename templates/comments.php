<?php
if ( post_password_required() ) {
    return;
}

global $post;
$post_id = $post->ID;
$commenter = wp_get_current_commenter();
$user = wp_get_current_user();
$user_identity = $user->exists() ? $user->display_name : '';
$html5 = false;
$req      = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$html_req = ( $req ? " required='required'" : '' );
$required_text = sprintf( ' ' . __('Required fields are marked %s', 'ultimate-travel'), '<span class="required">*</span>' );

$fields   =  array(
    'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'ultimate-travel' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' /></p>',
    'phone'    => '<p class="comment-form-url"><label for="url">' . __( 'Phone', 'ultimate-travel' ) . '</label> ' .
        '<input id="phone" name="phone" ' . ( $html5 ? 'type="phone"' : 'type="text"' ) . ' value="" size="30" maxlength="200" /></p>',
    'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'ultimate-travel' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>',
);

$ratingFrom = '';

$defaults = array(
    'fields'               => $fields,
    'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>',
    'comment_notes_before' => '',
    'comment_notes_after'  => '',
    'id_form'              => 'commentform',
    'id_submit'            => 'submit',
    'class_form'           => 'ut-form',
    'class_submit'         => 'submit ut-btn ut-btn--full',
    'name_submit'          => 'submit',
    'title_reply'          => __( '' ),
    'title_reply_to'       => __( '' ),
    'title_reply_before'   => '',
    'title_reply_after'    => '',
    'cancel_reply_before'  => ' <small>',
    'cancel_reply_after'   => '</small>',
    'cancel_reply_link'    => __( 'Cancel reply', 'ultimate-travel'),
    'label_submit'         => __( 'Post Comment', 'ultimate-travel'),
    'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" >%4$s</button>',
    'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
    'format'               => 'xhtml',
);
?>

<!--cats_comment_rating_avg-->
<!--cats_total_rating-->
<!--cats_avg_rating-->

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            printf( _nx( 'Review and Rate on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'ultimate-travel' ),
                number_format_i18n( get_comments_number() ), get_the_title() );
            ?>
        </h2>
        <?php
            $cats_total_rating = get_post_meta($post_id, 'cats_total_rating', true);
            $cats_avg_rating = get_post_meta($post_id, 'cats_avg_rating', true);
        ?>
        <div class="infoRatingPost">
            <span class="valueAvg">
                <?php
                    for($i = 1; $i <= 5; $i++) {
                        if ($i <= $cats_avg_rating) {
                            echo "<div class='ratingAvg catStartRatingItem active'></div>";
                        } else {
                            echo "<div class='ratingAvg catStartRatingItem'></div>";
                        }
                    }
                ?>
            </span>
            <span class="total"><?php echo $cats_total_rating; ?> <?php _e('(Review)') ?></span>

        </div>

        <hr>

        <?php cats_comment_nav(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 56,
                'walker' => new CatsCommentWalker()
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php cats_comment_nav(); ?>

    <?php else: // have_comments() ?>
        <h2 class="comments-title">
            <?php _e('Comment and review', 'ultimate-travel') ?>
        </h2>
    <?php endif; // have_comments() ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfifteen' ); ?></p>
    <?php endif; ?>

    <div class="uttReview">
        <?php comment_form($defaults); ?>
    </div>

</div><!-- .comments-area -->


