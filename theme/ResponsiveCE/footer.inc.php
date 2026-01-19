<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); }
/****************************************************
 *
 * @File: 		footer.inc.php
 * @Package:	GetSimple CE
 * @Action:		Rites of Passage Footer
 *
 *****************************************************/
?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <small>&copy; <?php echo date('Y'); ?> Rites of Passage - Meredith Madon</small>
            <small><a href="<?php get_site_url(); ?>index.php?id=contact">Contact Us</a></small>
        </div>
    </div>
</footer>

<script type="text/javascript" src="<?php get_theme_url(); ?>/js/script.js?v=<?php echo rand(); ?>"></script>
<?php get_footer(); ?>
</body>

</html>
