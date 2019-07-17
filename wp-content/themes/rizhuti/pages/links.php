<?php
/**
 * Template name: 友情链接
 * Description:   A links page
 */

get_header();
?>

<?php _the_focusbox('', get_the_title());?>

<section class="container">
<div class="row">
    <ul class="plinks">
		<?php
		wp_list_bookmarks(array(
		    'show_description' => true,
		    'show_name'        => true,
		    'orderby'          => 'rating',
		    'title_before'     => '<h2><i class="fa">&#xe63e;</i> ',
		    'title_after'      => '</h2>',
		    'order'            => 'DESC'
		));
		?>
	</ul>
</div>
</section>

<?php

get_footer();