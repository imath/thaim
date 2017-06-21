<?php
/**
 * Flag template
 *
 * @package  Thaim.
 *
 * @since  2.1.0
 */
?>
<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( thaim_question_sent() ) :

		thaim_question_content();

	endif; // thaim_question_sent() ?>

	<?php comment_form( array(
		'title_reply'          => __( 'You are about to flag a plugin of the Entrepôt. Please explain why.', 'thaim' ),
		'label_submit'         => __( 'Flag the plugin', 'thaim' ),
		'comment_notes_before' => sprintf( '<p class="comment-notes">%s</p>', __( 'Please, make sure to avoid adding more than 2 links.', 'thaim' ) ),
		'comment_field'        => sprintf( '
			<p class="comment-form-comment">
				<label for="comment">%s</label>
				<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>
			</p>', esc_html__( 'Description of the issue', 'thaim' ) ),
	) ); ?>

</div><!-- #comments .comments-area -->
