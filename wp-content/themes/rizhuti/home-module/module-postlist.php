<!-- 最新文章1 -->
<section class="container">
	<?php if ((!$paged || $paged===1)) { ?>
	<div class="section-info"> 
		<h2 class="postmodettitle"><?php echo _hui('mo_postlist_title') ?></h2> 
		<div class="postmode-description"><?php echo _hui('mo_postlist_desc') ?></div> 
	</div>
	<?php } ?>
	<?php 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 0;

		$args = array(
            'ignore_sticky_posts' => 0, //1改为0 置顶生效，默认不生效是怕和下面的CMS模块文章太多重复，不需要刻意不理 不是BUG
            'paged'               => $paged
		);
		$mo_postlist_no_cat = _hui('mo_postlist_no_cat');
		
		if($mo_postlist_no_cat){
			// var_dump(implode($mo_postlist_no_cat, ',-'));
			$args['cat'] = '-'.implode($mo_postlist_no_cat, ',-');
		}
		query_posts($args);

		get_template_part( 'excerpt', 'home' );
	?>
</section>
<!-- 最新文章1end -->
