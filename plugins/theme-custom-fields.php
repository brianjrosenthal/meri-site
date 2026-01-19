<?php
/**
 * Theme Custom Fields Plugin
 * 
 * Allows themes to define custom fields that appear in the page editor.
 * Fields are defined by the theme and automatically added to Page Options.
 * 
 * @package GetSimple
 * @subpackage ThemeCustomFields
 */

// Register plugin
$thisfile = basename(__FILE__, ".php");
register_plugin(
	$thisfile,
	'Theme Custom Fields',
	'1.0',
	'GetSimple Community',
	'https://get-simple.info/',
	'Allows themes to define custom fields for pages',
	'pages',
	'theme_custom_fields_init'
);

// Initialize the plugin
function theme_custom_fields_init() {
	// Add field UI to page editor
	add_action('edit-extras', 'theme_custom_fields_display');
	
	// Save field data when page is saved
	add_action('changedata-aftersave', 'theme_custom_fields_save');
}

// Actually call the init function!
theme_custom_fields_init();

/**
 * Get custom field definitions from the theme
 */
function theme_custom_fields_get_definitions() {
	// If function doesn't exist, try loading theme functions.php
	if (!function_exists('theme_custom_field_definitions')) {
		global $TEMPLATE;
		if (isset($TEMPLATE) && $TEMPLATE) {
			$theme_functions = GSTHEMESPATH . $TEMPLATE . '/functions.php';
			if (file_exists($theme_functions)) {
				include_once($theme_functions);
			}
		}
	}
	
	// Check if theme has defined custom fields
	if (function_exists('theme_custom_field_definitions')) {
		return theme_custom_field_definitions();
	}
	return array();
}

/**
 * Display custom fields in the page editor
 */
function theme_custom_fields_display() {
	global $id, $TEMPLATE;
	
	/* DEBUG OUTPUT - Uncomment to diagnose issues
	echo '<div class="clear"></div>';
	echo '<div style="background: #ffffcc; border: 2px solid #ff9900; padding: 15px; margin: 20px 0;">';
	echo '<h3 style="color: #ff9900;">🔍 DIAGNOSTIC INFO</h3>';
	echo '<p><strong>Plugin Status:</strong> theme_custom_fields_display() is running!</p>';
	echo '<p><strong>Current Theme:</strong> ' . (isset($TEMPLATE) ? htmlspecialchars($TEMPLATE) : 'NOT SET') . '</p>';
	
	if (isset($TEMPLATE)) {
		$theme_path = GSTHEMESPATH . $TEMPLATE . '/functions.php';
		echo '<p><strong>Theme Functions Path:</strong> ' . htmlspecialchars($theme_path) . '</p>';
		echo '<p><strong>File Exists:</strong> ' . (file_exists($theme_path) ? 'YES ✓' : 'NO ✗') . '</p>';
	}
	
	echo '<p><strong>Function Exists (before load):</strong> ' . (function_exists('theme_custom_field_definitions') ? 'YES ✓' : 'NO ✗') . '</p>';
	
	$fields = theme_custom_fields_get_definitions();
	
	echo '<p><strong>Function Exists (after load):</strong> ' . (function_exists('theme_custom_field_definitions') ? 'YES ✓' : 'NO ✗') . '</p>';
	echo '<p><strong>Fields Returned:</strong> ' . count($fields) . ' field(s)</p>';
	if (!empty($fields)) {
		echo '<p><strong>Field Names:</strong> ' . implode(', ', array_keys($fields)) . '</p>';
	}
	echo '</div>';
	*/
	// END DEBUG
	
	$fields = theme_custom_fields_get_definitions();
	
	if (empty($fields)) {
		return; // No fields defined by theme
	}
	
	// Get existing field values if editing a page
	$field_values = array();
	if ($id) {
		$id_safe = preg_replace('/[^a-zA-Z0-9\-_]/', '', $id);
		$file = GSDATAPAGESPATH . $id_safe . '.xml';
		if (file_exists($file)) {
			$data = getXML($file);
			foreach ($fields as $field_name => $field_config) {
				$xml_field = 'customField_' . $field_name;
				if (isset($data->$xml_field)) {
					$field_values[$field_name] = stripslashes((string)$data->$xml_field);
				}
			}
		}
	}
	
	// Output fields
	echo '<div class="clear"></div>';
	echo '<h3 style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">Theme Custom Fields</h3>';
	
	foreach ($fields as $field_name => $field_config) {
		$value = isset($field_values[$field_name]) ? $field_values[$field_name] : '';
		$type = isset($field_config['type']) ? $field_config['type'] : 'text';
		$label = isset($field_config['label']) ? $field_config['label'] : ucfirst($field_name);
		$placeholder = isset($field_config['placeholder']) ? $field_config['placeholder'] : '';
		$hint = isset($field_config['hint']) ? $field_config['hint'] : '';
		$rows = isset($field_config['rows']) ? $field_config['rows'] : 3;
		
		echo '<p>';
		echo '<label for="custom-field-' . htmlspecialchars($field_name) . '">' . htmlspecialchars($label) . ':</label>';
		
		switch ($type) {
			case 'textarea':
				echo '<textarea class="text" id="custom-field-' . htmlspecialchars($field_name) . '" name="custom-field-' . htmlspecialchars($field_name) . '" rows="' . intval($rows) . '" placeholder="' . htmlspecialchars($placeholder) . '">' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</textarea>';
				if ($hint) {
					echo '<br><span class="hint">' . htmlspecialchars($hint) . '</span>';
				}
				break;
				
			case 'text':
				echo '<input type="text" class="text" id="custom-field-' . htmlspecialchars($field_name) . '" name="custom-field-' . htmlspecialchars($field_name) . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" placeholder="' . htmlspecialchars($placeholder) . '" />';
				if ($hint) {
					echo '<br><span class="hint">' . htmlspecialchars($hint) . '</span>';
				}
				break;
				
			case 'checkbox':
				$checked = ($value == '1' || $value == 'on') ? 'checked' : '';
				echo '<input type="checkbox" id="custom-field-' . htmlspecialchars($field_name) . '" name="custom-field-' . htmlspecialchars($field_name) . '" ' . $checked . ' />';
				if ($hint) {
					echo '<span class="hint">' . htmlspecialchars($hint) . '</span>';
				}
				break;
				
			case 'select':
				if (isset($field_config['options']) && is_array($field_config['options'])) {
					echo '<select class="text" id="custom-field-' . htmlspecialchars($field_name) . '" name="custom-field-' . htmlspecialchars($field_name) . '">';
					foreach ($field_config['options'] as $opt_value => $opt_label) {
						$selected = ($value == $opt_value) ? 'selected' : '';
						echo '<option value="' . htmlspecialchars($opt_value) . '" ' . $selected . '>' . htmlspecialchars($opt_label) . '</option>';
					}
					echo '</select>';
				}
				if ($hint) {
					echo '<span class="hint">' . htmlspecialchars($hint) . '</span>';
				}
				break;
		}
		
		echo '</p>';
	}
}

/**
 * Save custom field data when page is saved
 * Note: changedata-aftersave hook doesn't pass parameters, so we get URL from POST
 */
function theme_custom_fields_save() {
	$fields = theme_custom_fields_get_definitions();
	
	if (empty($fields)) {
		return; // No fields defined
	}
	
	// Get the URL from POST data
	if (!isset($_POST['post-id']) || trim($_POST['post-id']) == '') {
		return; // No URL provided
	}
	
	$url = trim($_POST['post-id']);
	$url_safe = preg_replace('/[^a-zA-Z0-9\-_]/', '', $url);
	$file = GSDATAPAGESPATH . $url_safe . '.xml';
	
	if (!file_exists($file)) {
		return; // File doesn't exist
	}
	
	$data = getXML($file);
	$modified = false;
	
	foreach ($fields as $field_name => $field_config) {
		$post_field = 'custom-field-' . $field_name;
		$xml_field = 'customField_' . $field_name;
		
		if (isset($_POST[$post_field])) {
			$type = isset($field_config['type']) ? $field_config['type'] : 'text';
			
			// Handle checkbox specially
			if ($type == 'checkbox') {
				$field_value = '1';
			} else {
				$field_value = trim($_POST[$post_field]);
			}
			
			// Add or update the field
			if (isset($data->$xml_field)) {
				$data->$xml_field = $field_value;
			} else {
				$data->addChild($xml_field, htmlspecialchars($field_value, ENT_QUOTES, 'UTF-8'));
			}
			$modified = true;
		} else {
			// For checkboxes, if not in POST, it's unchecked
			$type = isset($field_config['type']) ? $field_config['type'] : 'text';
			if ($type == 'checkbox') {
				if (isset($data->$xml_field)) {
					$data->$xml_field = '0';
				} else {
					$data->addChild($xml_field, '0');
				}
				$modified = true;
			}
		}
	}
	
	// Save if modified
	if ($modified) {
		$xml_string = $data->asXML();
		file_put_contents($file, $xml_string);
	}
}

/**
 * Template function: Get a custom field value
 * 
 * @param string $field_name The field name
 * @param string $page_slug Optional page slug (defaults to current page)
 * @return string The field value or empty string
 */
function get_custom_field($field_name, $page_slug = null) {
	if (!$page_slug) {
		global $url;
		$page_slug = $url ? $url : return_page_slug();
	}
	
	$slug_safe = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page_slug);
	$file = GSDATAPAGESPATH . $slug_safe . '.xml';
	
	if (file_exists($file)) {
		$data = getXML($file);
		$xml_field = 'customField_' . $field_name;
		if (isset($data->$xml_field)) {
			return stripslashes((string)$data->$xml_field);
		}
	}
	
	return '';
}

/**
 * Template function: Display a custom field with HTML wrapper
 * 
 * @param string $field_name The field name
 * @param string $before HTML before the value
 * @param string $after HTML after the value
 */
function display_custom_field($field_name, $before = '', $after = '') {
	$value = get_custom_field($field_name);
	if (!empty($value)) {
		echo $before . htmlspecialchars_decode($value) . $after;
	}
}
