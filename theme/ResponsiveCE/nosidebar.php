<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); } ?>
<?php include('header.inc.php'); ?>

<section class="content">
	<div class="container">
		<div class="content-grid content-grid-nosidebar">
			<main class="content-main">

				<hgroup class="content-title">
					<?php
					// Get category info from custom fields
					$cat_name = get_custom_field('categoryName');
					$cat_link = get_custom_field('categoryLink');
					
					// Display category label with link if both are provided
					if ($cat_name && $cat_link) {
						echo '<p style="font-size: 24px; margin-bottom: 0; margin-top: 0;">';
						echo '<a href="' . htmlspecialchars($cat_link) . '" style="color: var(--primary); text-decoration: none;">';
						echo '/' . htmlspecialchars($cat_name);
						echo '</a></p>';
					}
					?>
					<h1><?php get_page_title(); ?></h1>
					<?php get_subcard_question(); ?>
				</hgroup>

 
				<?php get_page_content(); ?>
			</main>
		</div>
	</div>
</section>

<?php include('footer.inc.php'); ?>
