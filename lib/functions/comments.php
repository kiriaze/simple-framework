<?php
// Custom Comments Callback
function simple_comments($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	
	<article id="div-comment-<?php comment_ID() ?>" class="comment">

		<div class="comment-author vcard">
		
			<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		
		</div>

		<div class="comment-content">

			<div class="comment-meta commentmetadata">
				<?php printf(__('<span class="by">By</span> <cite class="fn">%s</cite>', SIMPLE_THEME_SLUG), get_comment_author_link()) ?>	
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php printf( __(' on %1$s', SIMPLE_THEME_SLUG), get_comment_date() ) ?>
				</a>
				<?php edit_comment_link(__('(Edit)', SIMPLE_THEME_SLUG),'  ','' ); ?>
			</div>

			<?php comment_text() ?>

			<footer>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
			</footer>

		</div>

	</article>
<?php }