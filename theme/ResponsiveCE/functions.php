<?php

/*
Logo Config
url logo or leave '' if you want show title on header screen
 */

$logo = '/data/uploads/flame_transparent_white.png';

/*
Light or dark mode 
$mode = 'dark' - dark mode
$mode = 'light' - light mode
$mode = '' - auto
*/

$mode = 'dark';

/*
you want use bootstrap grid cdn on your theme?
$bootstrap = 'yes' - use cdn
$bootstrap = 'no' or '' - default css
*/

$bootstrap = 'no';

/*
What you want default grid on template?
$defaultGrid = 'default' - sidebar right
$defaultGrid = 'left' - sidebar left
$defaultGrid = 'without' - sidebar right
*/

$defaultGrid =  'default';


/*
 * CUSTOM FIELDS DEFINITION FOR RESPONSIVECE THEME
 * 
 * Define custom fields that will appear in the page editor.
 * Requires the "Theme Custom Fields" plugin to be active.
 */

function theme_custom_field_definitions() {
	return array(
		'categoryName' => array(
			'label' => 'Category Name',
			'type' => 'text',
			'placeholder' => 'e.g., Health, Energy, Society, Climate',
			'hint' => 'The display name of the category'
		),
		'categoryLink' => array(
			'label' => 'Category Link',
			'type' => 'text',
			'placeholder' => 'e.g., index.php#health-subcards',
			'hint' => 'The URL to link back to the category section'
		),
		'subcardQuestion' => array(
			'label' => 'Subcard Question',
			'type' => 'textarea',
			'rows' => 3,
			'placeholder' => 'Enter a question or description that will appear below the page title...',
			'hint' => 'This text will appear prominently below the page title (optional)'
		),
		// Add more custom fields here as needed:
		// Example: Article image URL
		// 'articleImage' => array(
		//     'label' => 'Article Image URL',
		//     'type' => 'text',
		//     'placeholder' => '/data/uploads/image.png',
		//     'hint' => 'URL to the article header image'
		// ),
	);
}

// Helper function to display subcard question (for backwards compatibility)
function get_subcard_question() {
	$question = get_custom_field('subcardQuestion');
	if (!empty($question)) {
		echo '<p class="subcard-question">' . htmlspecialchars_decode($question) . '</p>';
	}
}
