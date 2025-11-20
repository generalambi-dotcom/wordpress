# AI Outils WordPress Theme

A clean, modern WordPress theme for AI tools directory with membership functionality. Designed specifically for the AI Outils platform.

## Version
1.0.0

## Description
AI Outils is a professionally designed WordPress theme built for AI tool directories and membership-based platforms. The theme provides a clean, maintainable architecture with clear separation between business logic (handled by plugins) and presentation (handled by the theme).

## Features

- **Mobile-first responsive design**
- **Clean, modern UI with purple accent colors**
- **Comprehensive template coverage:**
  - Homepage with hero section and featured tools
  - AI Tools archive and category pages
  - Single tool pages with video embeds
  - AI Tools directory page
  - Blog and post templates
  - Members-only area (dashboard, recommendations, saved tools, profile)
- **Reusable template parts** (tool cards, blog cards, category panels)
- **JSON-LD schema for SEO**
- **Accessible markup**
- **Lazy loading for images**
- **Consistent card-based design system**

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- **Required Plugin:** AI Tools Plugin (for custom post type and taxonomy)
- **Recommended Plugin:** AI Membership Plugin (for membership features)

## Installation

### Step 1: Upload Theme

1. Download the theme files
2. Navigate to **Appearance → Themes** in WordPress admin
3. Click **Add New → Upload Theme**
4. Choose the theme ZIP file and click **Install Now**
5. Click **Activate**

### Step 2: Install Required Plugins

The theme requires the following plugins to function properly:

1. **AI Tools Plugin** - Provides the custom post type (`ai_tool`) and taxonomy (`ai_category`)
2. **AI Membership Plugin** (optional) - Provides membership functionality for dashboard, recommendations, and saved tools

### Step 3: Configure Theme Settings

#### Adjust CPT and Taxonomy Slugs (if needed)

If your plugins use different slugs for the custom post type or taxonomy, update the constants at the top of `functions.php`:

```php
// Custom Post Type slug (from AI Tools plugin)
define( 'AI_OUTILS_CPT_SLUG', 'ai_tool' );

// Taxonomy slug (from AI Tools plugin)
define( 'AI_OUTILS_TAXONOMY_SLUG', 'ai_category' );
```

#### Set Up Menus

1. Go to **Appearance → Menus**
2. Create menus for the following locations:
   - **Primary Menu** - Main navigation (Discover, Resources, Useful Tools)
   - **Footer Navigation** - Footer legal links
   - **Footer Pages** - Footer page links
   - **Footer Resources** - Footer resource links

#### Upload Logo

1. Go to **Appearance → Customize → Site Identity**
2. Upload your site logo

### Step 4: Create Required Pages

Create the following pages and assign the appropriate templates:

#### Members Area Pages

1. **Members Dashboard**
   - Create a new page titled "Members Dashboard"
   - Assign template: **Members Dashboard**
   - Slug: `/members-dashboard/`

2. **Tool Recommendations**
   - Create a new page titled "Tool Recommendations"
   - Assign template: **Tool Recommendations**
   - Slug: `/tool-recommendations/`

3. **Saved Tools**
   - Create a new page titled "Saved Tools"
   - Assign template: **Saved Tools**
   - Slug: `/saved-tools/`

4. **Profile**
   - Create a new page titled "Profile" or "Your Profile"
   - Assign template: **Profile & Interests**
   - Slug: `/profile/`

#### Directory Page

5. **AI Tools Directory**
   - Create a new page titled "AI Tools Directory"
   - Assign template: **AI Tools Directory**
   - Slug: `/ai-tools-directory/`

### Step 5: Configure Homepage

1. Go to **Settings → Reading**
2. Select **A static page**
3. Choose your homepage for **Homepage**
4. Choose a blog page for **Posts page** (optional)
5. Click **Save Changes**

### Step 6: Add AI Tools and Categories

1. Go to **AI Tools → AI Categories** in the admin menu
2. Add your AI tool categories (e.g., AI Agents, AI Code Assistants, Automation, etc.)
3. Go to **AI Tools → Add New**
4. Add your AI tools with the following information:
   - Title
   - Content/Description
   - Featured Image (tool logo)
   - Categories
   - Custom fields (if supported by plugin):
     - Pricing model
     - Website URL
     - Video URL
     - Rating
     - Verified status

## Plugin Integration

### AI Tools Plugin

The theme expects the AI Tools plugin to register:
- Custom post type: `ai_tool` (configurable in functions.php)
- Taxonomy: `ai_category` (configurable in functions.php)

The theme uses the following meta fields (adjust based on your actual plugin):
- `_ai_tool_pricing_model` - Pricing information
- `_ai_tool_website_url` - Tool website URL
- `_ai_tool_video_url` - YouTube video URL
- `_ai_tool_verified` - Verified badge (true/false)
- `_ai_tool_rating` - Rating (0-5)
- `_ai_tool_social_links` - Social media links (array)

### AI Membership Plugin

The theme provides placeholder functions for membership plugin integration. Update these functions in `functions.php` based on your actual plugin:

- `ai_outils_membership_dashboard()` - Dashboard content
- `ai_outils_membership_recommendations()` - Tool recommendations
- `ai_outils_membership_saved_tools()` - Saved tools list
- `ai_outils_membership_profile_form()` - Profile/interests form

Replace the placeholder comments with actual plugin shortcodes or function calls.

## Shortcodes

The theme provides the following shortcodes:

### Tools Grid
```php
[tools_grid limit="6" category="ai-agents" orderby="date" order="DESC"]
```

### Categories Grid
```php
[categories_grid hide_empty="true"]
```

## Customization

### Colors

Update CSS custom properties in `style.css`:

```css
:root {
    --color-primary: #8B5CF6;        /* Purple */
    --color-secondary: #10B981;      /* Green */
    --color-accent: #F59E0B;         /* Orange */
    /* ... more colors ... */
}
```

### Typography

The theme uses system fonts by default. To use custom fonts:

1. Enqueue your font in `functions.php`
2. Update the CSS variables in `style.css`

### Layout

Adjust container width and spacing in `style.css`:

```css
:root {
    --container-max: 1280px;
    --spacing-xl: 2rem;
    /* ... more spacing ... */
}
```

## Template Hierarchy

```
ai-outils/
├── style.css                          # Main stylesheet
├── functions.php                      # Theme functions
├── index.php                          # Fallback template
├── header.php                         # Site header
├── footer.php                         # Site footer
├── front-page.php                     # Homepage
├── page.php                           # Default page template
│
├── AI Tools Templates
├── archive-ai_tool.php                # All tools archive
├── taxonomy-ai_category.php           # Category archive
├── single-ai_tool.php                 # Single tool page
├── page-ai-tools-directory.php        # Directory page template
│
├── Blog Templates
├── home.php                           # Blog posts index
├── single.php                         # Single blog post
├── category.php                       # Category archive
├── category-cheatsheets.php           # Cheatsheets category
├── archive.php                        # Generic archive
├── tag.php                            # Tag archive
│
├── Members Area Templates
├── page-members-dashboard.php         # Members dashboard template
├── page-tool-recommendations.php      # Recommendations template
├── page-saved-tools.php               # Saved tools template
├── page-profile.php                   # Profile template
│
└── template-parts/
    ├── tool-card.php                  # Tool card component
    ├── blog-card.php                  # Blog card component
    ├── category-panel.php             # Category panel component
    └── members/
        ├── stat-card.php              # Stat card component
        └── action-card.php            # Action card component
```

## Support

For support, please:
1. Check the documentation above
2. Review the code comments in template files
3. Consult the plugin documentation for integration details

## Changelog

### 1.0.0 - Initial Release
- Complete theme structure
- Public area templates (homepage, tools, blog)
- Members area templates (dashboard, recommendations, saved tools, profile)
- Reusable template parts
- Mobile-responsive design
- SEO optimization with JSON-LD schema

## Credits

- Theme Development: AI Outils Team
- Icons: Emoji (placeholder - replace with icon font if needed)

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html
