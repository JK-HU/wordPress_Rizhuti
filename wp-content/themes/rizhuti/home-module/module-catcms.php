<!-- 分类楼层CMS一 -->
<div class='vipImg' onclick="location.href='https://weidian.com/item.html?itemID=2582634581&wfr=c&ifr=itemdetail&spider_token=a692'" style="width:59%;height:135px;background: url(http://spzzds.wuyiyizhan.com/wp-content/uploads/2019/08/232059cb5361a93.jpg);line-height:90px;overflow:hidden;margin:40px auto 0 auto;background-repeat: no-repeat;background-position: center center;"></div>

<?php 
if ((!$paged || $paged===1)) { 
$module_catcms = (_hui( 'catcms' )) ? _hui( 'catcms' ) : [] ;
?>
	<?php foreach ($module_catcms as $key => $value) { ?>
			<?php if ($value['cms_cat_id']) { ?>
			<section class="container cms">
				<div class="section-info"> 
					<h2 class="postmodettitle"><?php echo $value['cms_title'] ?></h2> 
					<div class="postmode-description"><?php echo $value['cms_desc'] ?></div> 
				</div>
				<?php 
				 	$category_id= $value['cms_cat_id'];
   					$category_link = get_category_link( $category_id );
					$cms_args = array(
						 'cat'      => $category_id,
						 'ignore_sticky_posts' => 1,
						 'showposts' => $value['cms_cat_num']
					 );
					query_posts($cms_args);
					if ($value['cms_cat_of']):
					$category = get_term_by('id',$category_id,'category');
					$cat_childs = get_categories("parent=".$category_id."&hide_empty=0&depth=1"); 
					$parent_id = $category->parent; 
					if ($cat_childs || $parent_id) {
						$parentcat_ID = ($parent_id) ? $parent_id : $cat_ID ;
						$variable = wp_list_categories(array('echo' => false, 'show_count' => false, 'title_li' => '', 'hide_empty' => 0, 'child_of' => $parentcat_ID, 'depth' => 1));
						echo '<div class="pagination"><ul class="cat-navbtn">';
						echo $variable;
						echo '</ul></div>';
					}
					endif;
					// get_template_part( 'excerpt');
					echo '<div class="excerpts-wrapper"><div class="excerpts">';
				        while ( have_posts() ) : the_post();
				            get_template_part( 'excerpt', 'item' );
				        endwhile; 
				    echo '</div></div>';
				    echo'<div class="pagination"><a class="btn btn-primary" href="'.$category_link.'">'.$value['cms_btn'].'</a></div>';
				    wp_reset_query();
				?>
			</section>
			<?php } ?>	
	<?php } ?>
<?php } ?>

<!-- 分类楼层一end -->
