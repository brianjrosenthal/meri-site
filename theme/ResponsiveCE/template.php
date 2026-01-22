<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); } ?>
<?php include('header.inc.php'); ?>

<section class="content">
	<div class="container">
		<div class="content-grid 
		<?php if ($defaultGrid == 'left') {
			echo 'content-grid-left';
		} elseif ($defaultGrid  == 'without') {
			echo 'content-grid-nosidebar';
		}; ?>
		">
			<main class="content-main">
					<nav aria-label="breadcrumb">

						<ul>
							<li><a href="<?php get_site_url(); ?>"><?php get_site_name(); ?></a></li>
							<li><a href="<?php get_site_url(); ?>"><?php get_page_title(); ?></a></li>
						</ul>

					</nav>

				<hgroup class="content-title">
					<h1><?php get_page_title(); ?></h1>

				</hgroup>
 
				<?php get_page_content(); ?>
			</main>

		</div>
	</div>
</section>

<?php include('footer.inc.php'); ?>