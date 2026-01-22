<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); }
/****************************************************
 *
 * @File: 		header.inc.php
 * @Package:	GetSimple CE
 * @Action:		Rites of Passage Header
 *
 *****************************************************/
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php get_page_clean_title(); ?> | <?php get_site_name(); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php get_theme_url(); ?>/css/pico.min.css">
    <link rel="stylesheet" type="text/css" href="<?php get_theme_url(); ?>/css/style.css?v=<?php echo rand(); ?>">
    <link rel="stylesheet" type="text/css" href="<?php get_theme_url(); ?>/css/all.min.css">
    <?php get_header(); ?>
</head>

<body>

    <header class="site-header">
        <div class="header-container">
            <nav class="header-nav">
                <a class="site-logo" href="<?php get_site_url(); ?>">
                    <span class="logo-text">Rites of Passage</span>
                </a>

                <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <ul class="nav-menu" id="navMenu">
                    <li class="<?php echo (return_page_slug() == 'index') ? 'mobile-only' : ''; ?>"><a href="<?php get_site_url(); ?>">Home</a></li>
                    <li><a href="<?php get_site_url(); ?>index.php?id=about">About</a></li>
                    <li><a href="<?php get_site_url(); ?>index.php#services">Services</a></li>
                    <li><a href="<?php get_site_url(); ?>index.php?id=forms">Forms</a></li>
                    <li><a href="<?php get_site_url(); ?>index.php?id=contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
