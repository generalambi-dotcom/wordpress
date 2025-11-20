# Lyststyle Aggregator - WordPress Fashion Theme

A modern, responsive WordPress theme designed for fashion product aggregation sites similar to Lyst.com and ShopStyle. This theme allows you to create a complete fashion discovery platform with product listings, price comparison, affiliate links, and editorial content.

## Features

- **Custom Post Types:**
  - Products with detailed metadata (SKU, price, color, material, etc.)
  - Articles for editorial content and fashion guides
  - Retailers for managing store information

- **Custom Taxonomies:**
  - Product Categories (hierarchical)
  - Brands (non-hierarchical)
  - Product Tags for styles and attributes

- **Product Functionality:**
  - Multiple retailer price comparison
  - Affiliate link integration
  - Advanced filtering (category, brand, price range, color)
  - Sorting options (price, date, recommended)
  - Responsive product grid layouts

- **Wishlist System:**
  - Browser-based wishlist using localStorage
  - No login required
  - Persistent across sessions

- **Editorial Features:**
  - Article/guide post type
  - Featured content sections
  - Related articles

- **Design:**
  - Clean, minimal aesthetic inspired by Lyst/ShopStyle
  - Fully responsive (mobile, tablet, desktop)
  - Sticky header navigation
  - Card-based layouts
  - Professional typography

## Installation

### Step 1: Upload the Theme

1. Download or copy the `lyststyle-aggregator` folder
2. Upload it to your WordPress installation at: `wp-content/themes/`
3. Alternatively, zip the folder and upload via WordPress admin:
   - Go to **Appearance > Themes > Add New > Upload Theme**
   - Choose the zip file and click **Install Now**

### Step 2: Activate the Theme

1. Go to **Appearance > Themes** in your WordPress admin
2. Find "Lyststyle Aggregator" and click **Activate**

### Step 3: Flush Permalinks

After activation, go to **Settings > Permalinks** and click **Save Changes** (without making any changes). This ensures custom post types and taxonomies work correctly.

## Initial Setup

### 1. Create Menus

1. Go to **Appearance > Menus**
2. Create a new menu and assign it to "Primary Menu" location
3. Add pages/links for:
   - Clothing
   - Shoes
   - Accessories
   - Bags
   - Jewellery
   - Brands
   - Guides

### 2. Configure Theme Settings

1. Go to **Appearance > Customize**
2. Open "Lyststyle Settings" panel
3. Configure:
   - **Logo:** Upload your site logo
   - **Primary Brand Color:** Choose your main color (default: black)
   - **Accent Color:** Choose accent color (default: #FF6B6B)
   - **Default Currency:** Select GBP, USD, or EUR
   - **Footer Copyright Text:** Enter your copyright info

4. Open "Homepage Settings" panel:
   - **Hero Banner Title:** Set main headline
   - **Hero Banner Subtitle:** Set subtitle
   - **Hero Banner Image:** Upload hero image
   - **Editor's Picks Tag:** Enter product tag slug (e.g., "editors-pick")

5. Open "Social Media Links":
   - Add URLs for Instagram, TikTok, Facebook, X (Twitter)

### 3. Create Sample Content

#### Create Product Categories

1. Go to **Products > Product Categories**
2. Add categories like:
   - Clothing
   - Shoes
   - Bags
   - Accessories
   - Jewellery

#### Create Brands

1. Go to **Products > Brands**
2. Add brands like:
   - PRADA
   - UGG
   - Josef Seibel
   - Aquazzura
   - etc.

#### Create Product Tags

1. Go to **Products > Product Tags**
2. Add style tags like:
   - editors-pick (for homepage)
   - shearling
   - platform
   - mini-boot
   - chelsea
   - etc.

#### Add Products

1. Go to **Products > Add New**
2. Enter:
   - **Title:** Product name (e.g., "Shearling Coat - Brown")
   - **Description:** Product details
   - **Featured Image:** Main product image
   - **Categories:** Select product category
   - **Brands:** Select brand
   - **Tags:** Add relevant tags

3. In **Product Details** meta box:
   - **SKU:** Product SKU
   - **Base Price:** Starting price
   - **Currency:** GBP/USD/EUR
   - **Gender:** Women/Men/Unisex
   - **Color:** e.g., Brown, Black
   - **Material:** e.g., Shearling, Leather

4. In **Retailer Affiliate Links** meta box:
   - Click "Add Retailer Link"
   - Enter:
     - Retailer Name (e.g., "PRADA")
     - Price
     - Currency
     - Affiliate URL (full product page URL)
     - Shipping Info (e.g., "Free shipping")
   - Add multiple retailers for price comparison

5. Click **Publish**

#### Add Articles/Guides

1. Go to **Articles > Add New**
2. Enter:
   - **Title:** Article headline
   - **Content:** Full article text
   - **Featured Image:** Article hero image
   - **Excerpt:** Short summary
   - **Categories/Tags:** Organize content

3. Click **Publish**

### 4. Create Wishlist Page

1. Go to **Pages > Add New**
2. **Title:** "Wishlist"
3. **Page Attributes > Template:** Select "Wishlist"
4. Click **Publish**
5. Note the page URL (typically `/wishlist/`)

### 5. Set Homepage

1. Go to **Settings > Reading**
2. Choose "A static page"
3. **Homepage:** Select a page or leave as "Posts page" to use front-page.php template
4. Click **Save Changes**

## Usage Guide

### Managing Products

- Add products via **Products > Add New**
- Each product can have multiple retailer links for price comparison
- Use categories, brands, and tags to organize products
- Featured images should be high quality (recommended: 800x1067px or similar 3:4 ratio)

### Filtering & Search

- Filters automatically appear on product archive pages
- Users can filter by:
  - Category
  - Brand
  - Price range
  - Color
- Sorting options: Recommended, Price (low-high), Price (high-low), New In

### Wishlist

- Users can click the heart icon on any product to save it
- Wishlist is stored in browser localStorage (no account needed)
- Access wishlist via the heart icon in header or `/wishlist/` page
- Wishlist persists across browser sessions

### Customization

#### Colors

Edit in Customizer or add custom CSS:
```css
:root {
    --primary-color: #000000;
    --accent-color: #FF6B6B;
}
```

#### Fonts

The theme uses Inter font by default. To change:
1. Edit `functions.php`
2. Update Google Fonts enqueue
3. Update CSS variable `--font-family`

#### Layout

- Container max-width: 1400px (edit in `assets/css/main.css`)
- Product grid: Auto-responsive (minimum 280px per item)
- Mobile breakpoints: 480px, 768px, 992px, 1200px

## File Structure

```
lyststyle-aggregator/
├── style.css                      # Theme header
├── functions.php                  # Theme functions
├── README.md                      # This file
│
├── Template files
├── front-page.php                 # Homepage
├── archive-product.php            # Product archive
├── single-product.php             # Single product
├── archive-article.php            # Articles archive
├── single-article.php             # Single article
├── taxonomy-product_category.php  # Category archive
├── taxonomy-brand.php             # Brand archive
├── page-wishlist.php              # Wishlist page
├── index.php                      # Default template
├── page.php                       # Pages
├── single.php                     # Posts
├── archive.php                    # Archives
├── search.php                     # Search results
├── 404.php                        # Error page
├── header.php                     # Header
├── footer.php                     # Footer
├── sidebar.php                    # Sidebar
│
├── inc/                           # Theme functions
│   ├── custom-post-types.php     # Register post types
│   ├── taxonomies.php             # Register taxonomies
│   ├── meta-boxes.php             # Product meta boxes
│   ├── theme-options.php          # Customizer settings
│   └── helpers.php                # Helper functions
│
├── template-parts/                # Reusable parts
│   ├── hero-banner.php
│   ├── product-card.php
│   ├── product-grid.php
│   ├── article-card.php
│   ├── filters-bar.php
│   └── price-comparison-table.php
│
└── assets/                        # Theme assets
    ├── css/
    │   └── main.css              # Main stylesheet
    ├── js/
    │   ├── main.js               # Main scripts
    │   ├── filters.js            # Filter functionality
    │   └── wishlist.js           # Wishlist functionality
    └── images/
        └── (placeholder images)
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Tips

1. **Optimize Images:**
   - Use WebP format when possible
   - Recommended sizes:
     - Product images: 800x1067px
     - Article images: 1200x800px
     - Hero banners: 1200x600px

2. **Caching:**
   - Use a caching plugin (WP Super Cache, W3 Total Cache)
   - Enable browser caching

3. **CDN:**
   - Consider using a CDN for static assets
   - Cloudflare is a good free option

4. **Lazy Loading:**
   - Theme includes lazy loading for images
   - Native browser lazy loading is supported

## Troubleshooting

### Products don't display

1. Check permalinks: **Settings > Permalinks > Save Changes**
2. Ensure products are published (not draft)
3. Clear any caching plugins

### Filters not working

1. Verify products have metadata (price, color, etc.)
2. Check browser console for JavaScript errors
3. Ensure jQuery is loaded

### Wishlist not saving

1. Check browser localStorage is enabled
2. Verify JavaScript is not blocked
3. Check browser console for errors

### Images not loading

1. Verify images are uploaded correctly
2. Check file permissions (should be 644)
3. Regenerate thumbnails (use plugin)

## Support & Documentation

For issues or questions:
- Review this README
- Check WordPress Codex: https://codex.wordpress.org/
- Review theme files for inline documentation

## Credits

- Font: Inter by Google Fonts
- Icons: Feather Icons (SVG inline)
- Inspiration: Lyst.com, ShopStyle

## License

This theme is licensed under the GNU General Public License v2 or later.

## Changelog

### Version 1.0.0
- Initial release
- Product catalog with filtering
- Price comparison
- Wishlist functionality
- Editorial articles
- Responsive design
- Customizer integration
