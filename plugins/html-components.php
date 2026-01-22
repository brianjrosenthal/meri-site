<?php
/**
 * HTML Components Plugin for GetSimple CMS
 * 
 * Provides a simple way to store and edit HTML components without XML escaping.
 * Components are stored as plain .html files in data/other/html-components/
 * 
 * @package GetSimple
 * @subpackage HTMLComponents
 * @version 1.0
 */

// Security check
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

// Plugin registration
$thisfile = basename(__FILE__, ".php");
register_plugin(
	$thisfile,
	'HTML Components',
	'1.0',
	'Custom',
	'#',
	'Store and edit HTML components as plain HTML files (no XML escaping required)',
	'theme',
	'html_components_admin'
);

// Define constants
define('HTMLCOMPONENTSPATH', GSDATAOTHERPATH . 'html-components/');

// Add admin menu link
add_action('theme-sidebar', 'createSideMenu', array($thisfile, i18n_r($thisfile.'/PLUGIN_TITLE')));

/**
 * Ensure the storage directory exists
 */
function html_components_ensure_directory() {
	if (!file_exists(HTMLCOMPONENTSPATH)) {
		mkdir(HTMLCOMPONENTSPATH, 0755, true);
	}
}

/**
 * Get all HTML component files
 * @return array Array of component slugs
 */
function html_components_get_all() {
	html_components_ensure_directory();
	$components = array();
	$files = glob(HTMLCOMPONENTSPATH . '*.html');
	foreach ($files as $file) {
		$components[] = basename($file, '.html');
	}
	sort($components);
	return $components;
}

/**
 * Get component content
 * @param string $slug Component slug
 * @return string|false Component HTML content or false if not found
 */
function html_components_get($slug) {
	$slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);
	$file = HTMLCOMPONENTSPATH . $slug . '.html';
	if (file_exists($file)) {
		return file_get_contents($file);
	}
	return false;
}

/**
 * Save component content
 * @param string $slug Component slug
 * @param string $content HTML content
 * @return bool Success
 */
function html_components_save($slug, $content) {
	html_components_ensure_directory();
	$slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);
	$file = HTMLCOMPONENTSPATH . $slug . '.html';
	return file_put_contents($file, $content) !== false;
}

/**
 * Delete component
 * @param string $slug Component slug
 * @return bool Success
 */
function html_components_delete($slug) {
	$slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);
	$file = HTMLCOMPONENTSPATH . $slug . '.html';
	if (file_exists($file)) {
		return unlink($file);
	}
	return false;
}

/**
 * Template function: Display HTML component
 * @param string $slug Component slug
 */
function get_html_component($slug) {
	$content = html_components_get($slug);
	if ($content !== false) {
		echo $content;
	}
}

/**
 * Admin interface
 */
function html_components_admin() {
	html_components_ensure_directory();
	
	// Handle actions
	if (isset($_POST['action'])) {
		if ($_POST['action'] == 'save' && isset($_POST['slug']) && isset($_POST['content'])) {
			$slug = trim($_POST['slug']);
			$content = $_POST['content']; // CKEditor content
			
			if (html_components_save($slug, $content)) {
				echo '<div class="updated">Component saved successfully!</div>';
			} else {
				echo '<div class="error">Failed to save component.</div>';
			}
		} elseif ($_POST['action'] == 'delete' && isset($_POST['slug'])) {
			$slug = trim($_POST['slug']);
			if (html_components_delete($slug)) {
				echo '<div class="updated">Component deleted successfully!</div>';
			} else {
				echo '<div class="error">Failed to delete component.</div>';
			}
		}
	}
	
	// Determine view
	$view = isset($_GET['edit']) ? 'edit' : (isset($_GET['create']) ? 'create' : 'list');
	
	if ($view == 'list') {
		html_components_list_view();
	} elseif ($view == 'create') {
		html_components_edit_view('', '');
	} elseif ($view == 'edit') {
		$slug = $_GET['edit'];
		$content = html_components_get($slug);
		if ($content !== false) {
			html_components_edit_view($slug, $content);
		} else {
			echo '<div class="error">Component not found.</div>';
			html_components_list_view();
		}
	}
}

/**
 * List view
 */
function html_components_list_view() {
	$components = html_components_get_all();
	?>
	<h3 class="floated">HTML Components</h3>
	<div class="edit-nav clearfix">
		<a href="?id=html-components&create=true" class="btn">+ Create New Component</a>
	</div>
	
	<table class="edittable highlight paginate">
		<thead>
			<tr>
				<th>Component Slug</th>
				<th style="width: 200px;">Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php if (empty($components)): ?>
			<tr>
				<td colspan="2" style="text-align: center; padding: 40px;">
					<em>No HTML components yet. Click "Create New Component" to get started.</em>
				</td>
			</tr>
		<?php else: ?>
			<?php foreach ($components as $slug): ?>
			<tr>
				<td><strong><?php echo htmlspecialchars($slug); ?></strong></td>
				<td>
					<a href="?id=html-components&edit=<?php echo urlencode($slug); ?>">Edit</a> | 
					<a href="#" onclick="if(confirm('Delete component \'<?php echo htmlspecialchars($slug); ?>\'?')) { document.getElementById('delete-<?php echo htmlspecialchars($slug); ?>').submit(); } return false;" class="cancel">Delete</a>
					<form id="delete-<?php echo htmlspecialchars($slug); ?>" method="post" style="display:none;">
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug); ?>">
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	
	<p style="margin-top: 30px; padding: 15px; background: #f5f5f5; border-left: 4px solid #4CAF50;">
		<strong>💡 How to use:</strong> In your theme templates, use <code>&lt;?php get_html_component('slug-name'); ?&gt;</code> to display a component.
	</p>
	<?php
}

/**
 * Edit/Create view
 */
function html_components_edit_view($slug, $content) {
	$is_new = empty($slug);
	$title = $is_new ? 'Create New Component' : 'Edit Component: ' . htmlspecialchars($slug);
	?>
	<h3><?php echo $title; ?></h3>
	<form method="post" action="?id=html-components">
		<input type="hidden" name="action" value="save">
		
		<p>
			<label for="component-slug">Component Slug:</label>
			<?php if ($is_new): ?>
				<input type="text" class="text" id="component-slug" name="slug" value="<?php echo htmlspecialchars($slug); ?>" placeholder="e.g., my-component" required pattern="[a-zA-Z0-9\-_]+" title="Only letters, numbers, hyphens and underscores allowed">
				<span class="hint">Only letters, numbers, hyphens and underscores. This will be the filename.</span>
			<?php else: ?>
				<input type="text" class="text" id="component-slug" name="slug" value="<?php echo htmlspecialchars($slug); ?>" readonly>
				<span class="hint">Component slug cannot be changed after creation.</span>
			<?php endif; ?>
		</p>
		
		<p>
			<label for="component-content">Content:</label>
			<textarea class="text" id="component-content" name="content" style="width: 100%; height: 400px;"><?php echo htmlspecialchars($content); ?></textarea>
		</p>
		
		<p>
			<input type="submit" class="submit" value="Save Component">
			<a href="?id=html-components" class="cancel">Cancel</a>
		</p>
	</form>
	
	<script type="text/javascript" src="template/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
		CKEDITOR.replace('component-content', {
			skin: 'getsimple',
			forcePasteAsPlainText: true,
			height: '400px',
			entities: false,
			toolbar: [
				['Source'],
				['Bold', 'Italic', 'Strike'],
				['NumberedList', 'BulletedList', 'Blockquote'],
				['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
				['Link', 'Unlink', 'Anchor'],
				['Table', 'HorizontalRule'],
				['Format'],
				['Undo', 'Redo'],
				['Maximize']
			]
		});
	</script>
	<?php
}
