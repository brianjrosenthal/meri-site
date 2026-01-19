# Theme Custom Fields System

This project uses a custom fields plugin that allows themes to define additional fields for pages without modifying GetSimple core files.

## How It Works

1. **Plugin**: `plugins/theme-custom-fields.php` provides the framework
2. **Theme**: Defines fields via `theme_custom_field_definitions()` function
3. **Admin**: Fields appear in Page Options when editing pages
4. **Templates**: Display fields using `get_custom_field()` or `display_custom_field()`

## For ResponsiveCE Theme

### Current Custom Fields

**Subcard Question** - A textarea field for questions that appear below page titles

### Adding New Custom Fields

Edit `theme/ResponsiveCE/functions.php` and add to the array:

```php
function theme_custom_field_definitions() {
    return array(
        'subcardQuestion' => array(
            'label' => 'Subcard Question',
            'type' => 'textarea',
            'rows' => 3,
            'placeholder' => 'Enter a question...',
            'hint' => 'Appears below the title'
        ),
        
        // Add new fields here:
        'articleImage' => array(
            'label' => 'Article Image URL',
            'type' => 'text',
            'placeholder' => '/data/uploads/image.png',
            'hint' => 'URL to header image'
        ),
        
        'featured' => array(
            'label' => 'Featured Article',
            'type' => 'checkbox',
            'hint' => 'Show on homepage'
        ),
        
        'category' => array(
            'label' => 'Category',
            'type' => 'select',
            'options' => array(
                'health' => 'Health',
                'energy' => 'Energy',
                'society' => 'Society',
                'climate' => 'Climate'
            ),
            'hint' => 'Article category'
        ),
    );
}
```

## Supported Field Types

### textarea
Multi-line text input

```php
'fieldName' => array(
    'label' => 'Field Label',
    'type' => 'textarea',
    'rows' => 5,  // Height in rows
    'placeholder' => 'Enter text...',
    'hint' => 'Helper text shown below field'
)
```

### text
Single-line text input

```php
'fieldName' => array(
    'label' => 'Field Label',
    'type' => 'text',
    'placeholder' => 'Enter text...',
    'hint' => 'Helper text'
)
```

### checkbox
Boolean true/false toggle

```php
'fieldName' => array(
    'label' => 'Field Label',
    'type' => 'checkbox',
    'hint' => 'Helper text'
)
```

### select
Dropdown menu

```php
'fieldName' => array(
    'label' => 'Field Label',
    'type' => 'select',
    'options' => array(
        'value1' => 'Display Label 1',
        'value2' => 'Display Label 2',
    ),
    'hint' => 'Helper text'
)
```

## Using in Templates

### Get field value

```php
$value = get_custom_field('fieldName');
echo $value;
```

### Display with HTML wrapper

```php
// Simple wrapper
display_custom_field('subcardQuestion', '<p class="question">', '</p>');

// Conditional display
if (get_custom_field('featured') == '1') {
    echo '<span class="badge">Featured</span>';
}
```

### ResponsiveCE helper function

```php
// Backwards compatible - uses subcardQuestion field
get_subcard_question();
```

## Data Storage

Custom fields are stored in the page XML files as:

```xml
<customField_fieldName><![CDATA[value]]></customField_fieldName>
```

Example: `data/pages/dementia.xml`

```xml
<customField_subcardQuestion><![CDATA[How might we mitigate the impact of dementia?]]></customField_subcardQuestion>
```

## Admin Interface

When editing a page:
1. Click "Page Options" to expand metadata
2. Scroll to "Theme Custom Fields" section
3. Fill in any custom fields
4. Save page

## Plugin Activation

The plugin is located at `plugins/theme-custom-fields.php` and should be enabled in:

**Admin → Plugins → Theme Custom Fields** (check to enable)

## Benefits

✅ **No core file modifications** - Uses GetSimple's plugin hooks  
✅ **Theme-specific** - Different themes can have different fields  
✅ **Type-safe** - Built-in field type handling  
✅ **Extensible** - Easy to add new fields and types  
✅ **Clean data** - Stored properly in page XML
