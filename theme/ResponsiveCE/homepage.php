<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); } ?>
<?php include('header.inc.php'); ?>

				<?php 
				// Homepage content is now stored in an external HTML file for easier editing
				// No need to deal with XML escaping anymore!
				include('homepage-content.html'); 
				?>

<?php include('footer.inc.php'); ?>
