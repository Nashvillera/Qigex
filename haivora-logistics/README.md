# Haivora Logistics - Native WordPress Theme (Phase 1)

**Company Branding:** Qidex Express LOGISTICS  
**Theme Name:** Haivora Logistics  
**Version:** 1.0.0  
**License:** GPL v2 or later  

---

## 🚚 Overview

**Haivora Logistics** is a production-grade, high-performance native WordPress theme designed specifically for international logistics companies, freight forwarders, cargo ocean liners, air express couriers, and supply chain management platforms.

Phase 1 establishes the complete theme core, visual design system, responsive navigation, customizer integration, and interactive Phase 1 tracking widget interface.

---

## 📁 Directory Structure

```
haivora-logistics/
│
├── style.css             # Theme header & design system CSS variables
├── functions.php         # Core theme setup, enqueues, customizer hooks
├── index.php             # Main template fallback
├── front-page.php        # Homepage layout (11 Phase 1 sections)
├── home.php              # Blog / Logistics Insights page
├── header.php            # Announcement bar, branding logo, navigation bar
├── footer.php            # 4-column footer, quick links, contact & copyright
├── page.php              # Single page template (Elementor compatible)
├── single.php            # Single post template
├── archive.php           # Archive & category template
├── search.php            # Search results template
├── searchform.php        # Accessible search form
├── 404.php               # 404 error page template
├── comments.php          # Comments layout
├── sidebar.php           # Sidebar widget area
├── screenshot.png        # WordPress theme dashboard preview image
├── README.md             # Documentation
│
├── inc/
│   ├── customizer.php    # WP Appearance Customizer settings
│   └── template-tags.php # Helper tags & logo renderer
│
└── assets/
    ├── css/
    │   ├── main.css      # Core theme styles
    │   └── responsive.css# Breakpoint media queries
    ├── js/
    │   ├── navigation.js # Mobile menu & hamburger accessibility
    │   ├── main.js       # Sticky header & smooth anchor scroll
    │   └── tracking-preview.js # Interactive tracking demo widget
    ├── images/           # SVG graphics & vector assets
    └── icons/            # SVG icon set
```

---

## ⚙️ Installation Instructions (WordPress)

1. Download or clone this repository or export as ZIP.
2. Ensure the `haivora-logistics` folder contains all theme files listed above.
3. Log in to your WordPress Admin Dashboard (`wp-admin`).
4. Go to **Appearance** &rarr; **Themes** &rarr; **Add New Theme**.
5. Click **Upload Theme**, select the `haivora-logistics.zip` file (or upload the `haivora-logistics` folder to `/wp-content/themes/`).
6. Click **Install Now** and then **Activate**.

---

## 🎨 WordPress Customizer Features

Navigate to **Appearance** &rarr; **Customize** &rarr; **Logistics Company Info** to customize:
- Company Name (default: `Qidex Express LOGISTICS`)
- Hotline Phone (default: `+1 (800) 555-QIDEX`)
- Support Email (default: `support@qidexexpress.com`)
- Corporate Address
- WhatsApp Direct Number
- Theme Primary & Accent Colors

---

## 🗺️ Roadmap & Multi-Phase Plan

- **Phase 1 (COMPLETED):** Brand identity, visual design system, WP theme templates, responsive header/footer, homepage layout, interactive tracking widget preview interface.
- **Phase 2 (UPCOMING):** Shipment Custom Post Type (CPT), custom fields, meta boxes, shipment status taxonomies, tracking page template.
- **Phase 3 (UPCOMING):** Live shipment tracking database engine, search logic, status timeline renderer.
- **Phase 4 (UPCOMING):** Customer accounts, shipment history dashboard, tracking notifications.
- **Phase 5 (UPCOMING):** Admin shipment manager, bulk update tool, barcode / QR code generator.
- **Phase 6 (UPCOMING):** Instant freight quote calculator, automated email receipts, WhatsApp API integration.
- **Phase 7 (UPCOMING):** Multi-language & currency support (WPML / Polylang compatibility), print manifest generator.
- **Phase 8 (UPCOMING):** Final security audit, performance optimization, demo content XML import, theme package release.
