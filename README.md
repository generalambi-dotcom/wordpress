# Lyststyle Aggregator

A modern fashion aggregator WordPress theme and plugin inspired by Lyst and ShopStyle. Lyststyle Aggregator enables you to create a comprehensive fashion discovery platform with product listings, affiliate links, personalized recommendations, and user wishlists.

## Overview

**Lyststyle Aggregator** is a complete WordPress solution for building a fashion aggregation website. It combines a powerful core plugin with a beautiful, responsive theme to create a user experience similar to popular fashion aggregators like Lyst and ShopStyle.

### Key Features

- **Custom Post Types**
  - Products with comprehensive metadata
  - Articles & Guides for fashion content
  - Retailer profiles and management

- **Advanced Taxonomies**
  - Product Categories (Clothing, Shoes, Accessories, Bags, Jewellery)
  - Brand taxonomy for designer and retailer brands
  - Product tags for style categorization

- **Product Management**
  - SKU and pricing information
  - Multiple affiliate links per product
  - Gender, color, and material attributes
  - Multi-currency support (GBP, USD, EUR)
  - Product image galleries

- **User Features**
  - Personalized user preferences
  - Wishlist functionality (localStorage for guests, user meta for logged-in users)
  - User account pages with preference management
  - Personalized product recommendations

- **Events Tracking**
  - Product view tracking
  - Click tracking for affiliate links
  - User behavior analytics
  - Session-based tracking for guests

- **Advanced Filtering & Sorting**
  - Filter by category, brand, color, gender, price range
  - Sort by price (low to high, high to low), newest, recommended
  - Dynamic AJAX-powered filters

- **Responsive Design**
  - Mobile-first responsive layout
  - Grid-based product displays
  - Optimized for all screen sizes

- **REST API Integration**
  - Custom REST endpoints for wishlist management
  - Events logging API
  - Product data API for dynamic content

## Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.6 or higher (or MariaDB equivalent)

## Installation

### 1. Upload Plugin and Theme Files

#### Plugin Installation
1. Upload the `lyststyle-core` folder to `/wp-content/plugins/lyststyle-core/`
2. Ensure all plugin files are in the correct location

#### Theme Installation
1. Upload the `lyststyle-aggregator` folder to `/wp-content/themes/lyststyle-aggregator/`
2. Ensure all theme files are in the correct location

### 2. Activate the Plugin

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find **Lyststyle Core** in the list
4. Click **Activate**

### 3. Activate the Theme

1. Navigate to **Appearance > Themes**
2. Find **Lyststyle Aggregator** in the available themes
3. Click **Activate**

### 4. Flush Permalinks

1. Navigate to **Settings > Permalinks**
2. Select **Post name** (recommended for clean URLs)
3. Click **Save Changes**

This will ensure all custom post types and taxonomies have proper URLs.

## Initial Setup

### Configure Theme Customizer

Navigate to **Appearance > Customize** to configure the following settings:

#### Brand Settings
- **Logo:** Upload your site logo
- **Primary Brand Color:** Set your main brand color (default: #000000)
- **Accent Color:** Set your accent/highlight color (default: #FF6B6B)

#### Homepage Settings

**Hero Banner:**
- Hero Banner Title (default: "The Intelligent Gift Guide")
- Hero Banner Subtitle
- Hero Sponsored By Text (optional)
- Hero Background Image
- Hero CTA Button Label
- Hero CTA Button URL

**Editor's Picks Section:**
- Show/Hide Editor's Picks Section
- Section Title (default: "Editor's Picks")
- Section Subtitle
- Product Tag Slug (default: "editors-pick")
- Product IDs (optional, comma-separated)

**Trending Now Section:**
- Show/Hide Trending Now Section
- Section Title (default: "Trending Now")

**Trending Brands Section:**
- Show/Hide Trending Brands Section
- Section Title (default: "Trending Brands")
- Number of Brands to Show (default: 8)

**Luxury Deals Section:**
- Show/Hide Luxury Deals Section
- Section Title (default: "Luxury Deals")
- Product Tag Slug (default: "luxury-deal")

**Articles & Guides Section:**
- Show/Hide Articles Section
- Section Title (default: "Latest Articles & Guides")
- Number of Articles to Show (default: 3)

#### Account & System Pages

Create and assign the following pages:

1. **Login Page** - Create a page with template "Login Page"
2. **Register Page** - Create a page with template "Register Page"
3. **My Account Page** - Create a page with template "My Account Page"
4. **Wishlist Page** - Create a page with template "Wishlist Page"
5. **Help & Info Page** - Create a page with template "Help & Info Page"

Assign these pages in **Appearance > Customize > Account & System Pages**.

### Create Required Pages

1. Go to **Pages > Add New**
2. Create the following pages:

| Page Name | Page Template | Description |
|-----------|---------------|-------------|
| Login | Login Page | User login functionality |
| Register | Register Page | User registration |
| My Account | My Account Page | User account dashboard with preferences |
| Wishlist | Wishlist Page | User's saved products |
| Help & Info | Help & Info Page | FAQ and help resources |

3. Publish each page
4. Assign templates in **Appearance > Customize > Account & System Pages**

### Configure Navigation Menus

1. Go to **Appearance > Menus**
2. Create menus for:
   - **Primary Menu** - Main site navigation
   - **Help & Info Menu** - Help and information links
   - **Footer Menu** - Footer navigation

3. Add menu items and assign to their respective locations

## Creating Sample Content

### Product Categories

Create the following product categories via **Products > Product Categories**:

- Clothing
- Shoes
- Accessories
- Bags
- Jewellery

### Brands

Create brands via **Products > Brands**. Example brands:

- UGG
- Prada
- Nike
- Adidas
- Gucci
- Louis Vuitton
- Balenciaga
- Off-White
- Supreme

### Product Tags

Create product tags via **Products > Product Tags** for style categorization:

- minimalist
- streetwear
- platform
- luxury
- editors-pick (for homepage Editor's Picks section)
- luxury-deal (for homepage Luxury Deals section)
- bohemian
- athleisure
- formal

### Creating Products

1. Navigate to **Products > Add New**
2. Enter product title (e.g., "UGG Classic Ultra Mini Platform Boot")
3. Add product description in the content editor
4. Set featured image (recommended size: 600x800px)
5. Assign Product Category (e.g., Shoes)
6. Assign Brand (e.g., UGG)
7. Add Product Tags (e.g., platform, minimalist)

#### Product Core Details
Fill in the following meta fields:

- **SKU:** Product SKU/identifier
- **Gender:** All, Women, Men, or Unisex
- **Colour:** e.g., Black, Chestnut, Sand
- **Material:** e.g., Suede, Leather, Cotton
- **Base Price:** e.g., 150.00
- **Currency:** GBP, USD, or EUR

#### Affiliate Offers
Add affiliate links from various retailers:

1. Click **Add Retailer**
2. Enter:
   - Retailer Name (e.g., "ASOS")
   - Price (e.g., 150.00)
   - Affiliate URL (full product URL with affiliate code)
   - Stock Status: In Stock / Out of Stock / Low Stock

3. Add multiple retailers for price comparison

### Creating Articles/Guides

1. Navigate to **Articles > Add New**
2. Enter article title (e.g., "10 Must-Have Winter Boots for 2024")
3. Add article content
4. Set featured image (recommended size: 600x400px)
5. Assign article categories and tags
6. Fill in article meta:
   - Author Name
   - Reading Time (e.g., "5 min read")
   - Featured Article (checkbox)

### Creating Retailers

1. Navigate to **Retailers > Add New**
2. Enter retailer name (e.g., "ASOS")
3. Add retailer description and information
4. Set featured image (retailer logo, recommended size: 200x100px)
5. Add retailer meta:
   - Website URL
   - Commission Rate
   - Affiliate Network

## Features Overview

### Product Aggregation
- Aggregate fashion products from multiple retailers
- Display multiple pricing options per product
- Affiliate link management with click tracking
- Price comparison tables

### User Preferences
Users can set personalized preferences including:
- Gender preference (All, Women, Men, Unisex)
- Favorite product categories
- Preferred brands
- Price range preferences
- Color preferences
- Style preferences (tags)
- Occasions
- Sizes (shoes, clothing top, clothing bottom)
- Preferred retailers

### Personalized Recommendations
- Algorithm-based product recommendations
- Based on user preferences and browsing behavior
- Event tracking for improved recommendations over time

### Wishlist System
- **Guest Users:** Wishlist stored in browser localStorage
- **Logged-in Users:** Wishlist persisted in user meta
- Add/remove products via heart icon
- Dedicated wishlist page showing saved products
- Sync capability when guest users log in

### Events Tracking
Tracks user interactions including:
- Product views
- Affiliate link clicks
- Wishlist additions/removals
- Filter usage
- Search queries

Data stored for analytics and recommendation improvements.

### Advanced Filtering
Product archives support filtering by:
- Product Category
- Brand
- Color
- Gender
- Price Range (min/max)
- Sorting (Recommended, Price Low-High, Price High-Low, New In)

### REST API
The plugin provides REST API endpoints for dynamic interactions.

## Customizer Options

Access all customizer options via **Appearance > Customize**:

### Brand Settings
- Logo upload
- Primary Brand Color
- Accent Color

### Homepage Settings
- Hero Banner (title, subtitle, image, CTA)
- Editor's Picks section (show/hide, title, products)
- Trending Now section (show/hide, title)
- Trending Brands section (show/hide, title, number to display)
- Luxury Deals section (show/hide, title, tag)
- Articles & Guides section (show/hide, title, number to display)

### Account & System Pages
- Login Page
- Register Page
- My Account Page
- Wishlist Page

### Colors
- Background Color (via standard WordPress customizer)
- Custom colors applied throughout theme

## REST API Endpoints

The Lyststyle Core plugin provides the following REST API endpoints:

### Wishlist Endpoints

**Sync Wishlist** (POST)
```
/wp-json/lyststyle/v1/wishlist/sync
```
Parameters:
- `product_id` (int) - Product ID
- `action` (string) - "add" or "remove"

Requires: User authentication

**Get Wishlist** (GET)
```
/wp-json/lyststyle/v1/wishlist/get
```
Returns: Array of product IDs in user's wishlist

Requires: User authentication

### Events Endpoints

**Log Event** (POST)
```
/wp-json/lyststyle/v1/events/log
```
Parameters:
- `event_type` (string) - Type of event (e.g., "product_view", "click")
- `product_id` (int) - Related product ID (optional)
- `event_value` (string) - Event value (optional)
- `meta` (array) - Additional metadata (optional)

Requires: None (available for guests and logged-in users)

### Products Endpoints

**Get Products by IDs** (POST)
```
/wp-json/lyststyle/v1/products/by-ids
```
Parameters:
- `product_ids` (array) - Array of product IDs

Returns: Array of product objects with title, price, image, brand, etc.

Requires: None

## Development & Customization

### Theme File Structure

```
lyststyle-aggregator/
├── assets/
│   ├── css/
│   │   └── main.css
│   └── js/
│       ├── main.js
│       ├── wishlist.js
│       ├── filters.js
│       └── events.js
├── inc/
│   ├── custom-post-types.php
│   ├── taxonomies.php
│   ├── meta-boxes.php
│   ├── theme-options.php
│   └── helpers.php
├── template-parts/
│   ├── product-card.php
│   ├── article-card.php
│   ├── filters-bar.php
│   ├── hero-banner.php
│   ├── product-grid.php
│   └── price-comparison-table.php
├── functions.php
├── style.css
├── header.php
├── footer.php
├── front-page.php
├── archive-product.php
├── single-product.php
├── page-login.php
├── page-register.php
├── page-account.php
├── page-wishlist.php
└── page-help-info.php
```

### Plugin File Structure

```
lyststyle-core/
├── inc/
│   ├── cpt-product.php
│   ├── cpt-article.php
│   ├── cpt-retailer.php
│   ├── taxonomies.php
│   ├── product-meta.php
│   ├── user-preferences.php
│   ├── events-tracking.php
│   ├── recommendations.php
│   ├── rest-api.php
│   └── helpers.php
└── lyststyle-core.php
```

### Custom CSS

Add custom CSS via **Appearance > Customize > Additional CSS** or create a child theme.

### Hooks & Filters

The theme and plugin provide numerous WordPress hooks for customization:

- `lyststyle_product_card` - Customize product card output
- `lyststyle_before_product_content` - Add content before product details
- `lyststyle_after_product_content` - Add content after product details
- `lyststyle_user_preferences` - Modify user preference fields
- `lyststyle_recommendations_query_args` - Customize recommendation algorithm

## User Preferences Management

Users can manage their preferences via the **My Account** page:

1. Log in to the website
2. Navigate to My Account
3. Edit preferences including:
   - Gender preference
   - Favorite categories and brands
   - Price range
   - Preferred colors and styles
   - Sizes
   - Favorite retailers

Administrators can also edit user preferences via **Users > Edit User** in the WordPress admin.

## Support & Documentation

### Resources

- Theme Documentation: [https://lyststyle.com/docs](https://lyststyle.com/docs)
- Support Forum: [https://lyststyle.com/support](https://lyststyle.com/support)
- GitHub Repository: Coming soon
- Video Tutorials: Coming soon

### Troubleshooting

**Products not displaying?**
- Ensure the Lyststyle Core plugin is activated
- Go to Settings > Permalinks and click Save Changes
- Check that products are published and have categories assigned

**Wishlist not working?**
- Check browser console for JavaScript errors
- Ensure REST API is accessible (try accessing /wp-json/ in browser)
- For logged-in users, ensure user meta permissions are correct

**Affiliate links not tracking?**
- Verify events tracking is enabled
- Check that JavaScript is not blocked
- Ensure database tables were created during plugin activation

**Recommendations not showing?**
- Create user preferences for logged-in users
- Ensure sufficient product data exists
- Check that products have proper taxonomy assignments

## License

This theme and plugin are licensed under the GNU General Public License v2 or later.

- **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Credits

**Lyststyle Aggregator** is developed by the Lyststyle team.

- Website: [https://lyststyle.com](https://lyststyle.com)
- Version: 1.0.0
- Last Updated: 2024

---

## Changelog

### Version 1.0.0
- Initial release
- Product custom post type with affiliate links
- Article and Retailer post types
- User preference system
- Wishlist functionality
- Events tracking
- Personalized recommendations
- REST API integration
- Responsive theme with customizer options
- Homepage sections (Hero, Editor's Picks, Trending, etc.)
- Advanced filtering and sorting
- Price comparison tables
- User account pages

---

For more information, visit [https://lyststyle.com](https://lyststyle.com) or contact support.
