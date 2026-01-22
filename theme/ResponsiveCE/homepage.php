<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); } ?>
<?php include('header.inc.php'); ?>

				<?php 
				// Homepage content is now stored in an external PHP file with editable components
				// Service descriptions can be edited in the admin panel under Components
				include('homepage-content.php'); 
				?>

<?php include('footer.inc.php'); ?>
