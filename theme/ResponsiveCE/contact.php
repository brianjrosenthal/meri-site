<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); } ?>
<?php include('header.inc.php'); ?>

<section class="content">
	<div class="container">
		<div class="content-main">
			<?php 
			// Contact content with PHP execution for form handling
			include('contact-content.php'); 
			?>
		</div>
	</div>
</section>

<?php include('footer.inc.php'); ?>
