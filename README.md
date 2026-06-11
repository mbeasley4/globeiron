# Globe Iron Roofing — WordPress Theme

A fully custom WordPress block theme built for **Globe Iron Roofing**. Designed by [BLDG](https://bldgbrands.com) and developed by **Mike Beasley** at [Black Lab Development](https://www.blacklabdev.com). Built from the ground up as a performance-first, editor-friendly theme that gives a regional roofing contractor enterprise-grade web presence without the enterprise overhead.

---

## Overview

Globe Iron Roofing operates across Ohio, Indiana, and Kentucky — commercial roofing, residential, and historic restoration. The theme was built to match that positioning: confident, craft-focused, and technically precise. Every block, schema node, and animation was written by hand with no page-builder bloat.

The build prioritises:

- **Editor autonomy** — content editors control every section through ACF fields; no code required to update page content, service areas, hours, or team members
- **Local SEO + AEO** — structured data on every page type (Organization, RoofingContractor, LocalBusiness, Service, Review, BlogPosting) designed specifically for local pack rankings and AI-generated answer eligibility
- **Performance** — single JS bundle, single CSS bundle, no render-blocking third-party scripts beyond Google Maps (deferred inline)

---

## Tech Stack

| Layer | Technology |
|---|---|
| CMS | WordPress 6.x |
| Fields | Advanced Custom Fields Pro |
| Templating | PHP 8.x (server-side ACF block renders) |
| Styles | SCSS + Tailwind CSS (utility layer) |
| Bundler | Webpack 5 |
| Block Editor | `@wordpress/scripts` (wp-scripts) |
| Animations | GSAP 3.15 |
| Slider | Splide.js 4 + Auto Scroll extension |
| Map | Google Maps JavaScript API (inline-deferred) |
| Schema | Hand-written JSON-LD (`wp_head` hooks) |

---

## Custom Blocks

All 21 blocks are ACF-registered with JSON-driven field groups (`acf-json/`) and server-side PHP renders. Each block ships with full editor preview support.

| Block | Description |
|---|---|
| `hero-home` | Full-viewport homepage hero — standard centred or split layout with an inline inspection-form card |
| `hero-interior` | Interior page header — standard or split-collage image layout |
| `section-page-hero` | Flexible page-level hero with background image and overlay controls |
| `section-features` | Icon + copy feature grid with configurable column counts |
| `section-content-image-split` | Two-column text/image split with alignment and background colour options |
| `section-process` | Numbered process steps with branded icon squares |
| `section-services` | Service listing grid with icons, descriptions, and CTAs |
| `service-hubs` | 2–4 column hub cards for service category landing pages |
| `section-reviews` | Splide.js review slider pulling from the Reviews CPT |
| `section-testimonials` | Testimonial cards — manual repeater or CPT-driven |
| `section-certifications` | Certification badge rail pulled from the Certifications CPT |
| `section-team-grid` | Team member grid pulled from the Team Member CPT |
| `section-work` | Tabbed project portfolio slider pulling from the Projects CPT |
| `section-post-listing` | Blog post grid with category filter and search |
| `section-map` | Google Maps embed with custom gold SVG pins for all service area cities |
| `section-partnership` | Full-bleed partner logo marquee pulled from the Partners CPT |
| `section-border-columns` | Bordered feature columns for specs and differentiators |
| `section-cta` | Configurable call-to-action band — light, dark, and brand colour variants |
| `project-header` | Full-width project hero with technical snapshot card |
| `project-details` | Before/After image stack with highlights repeater |
| `project-outcome` | Full-width outcome section with optional background image |

---

## Custom Post Types

| CPT | Slug | Purpose |
|---|---|---|
| Projects | `project` | Portfolio entries with gallery, specs, and category taxonomy |
| Partners | `partner` | Partner/manufacturer logos for the partnership marquee |
| Certifications | `certification` | Certification badges and seals |
| Team Members | `team_member` | Staff with headshot, bio, role, and LinkedIn — used in blog authorship schema |
| Reviews | `globeiron_review` | Customer reviews with rating, source, date, and optional photo — feeds the review slider and Review schema nodes |

---

## Architecture

```
globeiron/
├── blocks/                  # ACF server-side block renders (one dir per block)
│   ├── section-map/
│   ├── section-reviews/
│   └── ...
├── inc/
│   ├── acf-blocks.php       # ACF block registrations
│   ├── block-fields.php     # Programmatic ACF field supplements
│   ├── canonical.php        # Canonical URL management
│   ├── meta-boxes.php       # Custom meta box additions
│   ├── opengraph.php        # Open Graph + Twitter Card tags
│   ├── options.php          # ACF Options pages (Site Settings)
│   ├── post-types.php       # CPT and taxonomy registration + REST fields
│   └── schema.php           # All JSON-LD structured data
├── src/
│   ├── js/
│   │   ├── main.js          # Front-end JS (animations, map rail, nav)
│   │   ├── editor.js        # Block editor entry
│   │   └── blocks/          # React block edit/save components
│   └── scss/
│       ├── _variables.scss
│       ├── _blocks.scss     # All block-level styles
│       ├── _layout.scss     # Page-level layout, 404, archives
│       └── main.scss
├── dist/                    # Webpack output (committed)
├── acf-json/                # ACF field group JSON (version-controlled)
├── functions.php            # Theme bootstrap, enqueue, Google Maps init
└── 404.php
```

---

## Structured Data (Schema)

The theme outputs JSON-LD on every page type without a plugin. All schema is hand-written in `inc/schema.php`.

| Page | Schema Types |
|---|---|
| Homepage | `@graph` → `WebSite`, `Organization`, `RoofingContractor`, `LocalBusiness`, `WebPage`, `Review` (×6 from CPT), `AggregateRating` (from Trustindex) |
| All other pages | `RoofingContractor` + `LocalBusiness` (with `areaServed`, `aggregateRating`) |
| Service pages | `Service` (keyed by slug: `commercial`, `residential`, `historic-restoration`, `metal-roofing`, `roof-repair`, `roof-replacement`, `roof-inspection`) |
| Blog posts | `BlogPosting` + `Person` (author linked to Team Member CPT) |

**Service areas covered in `areaServed`:** Cincinnati OH, Columbus OH, Indianapolis IN, Dayton OH, Lexington KY, Somerset KY (South KY), Pikeville KY (Eastern KY)

---

## Build

```bash
# Install dependencies
npm install

# Development (watch mode)
npm run dev              # Webpack — main JS + CSS
npm run dev:blocks       # wp-scripts — block editor assets

# Production
npm run build            # Webpack — minified main bundle
npm run build:blocks     # wp-scripts — minified editor bundle
```

Output lands in `dist/js/` and `dist/css/`. Both directories are committed so the theme works on hosts without a build step.

---

## Requirements

- WordPress 6.3+
- PHP 8.1+
- Advanced Custom Fields Pro 6.x
- Google Maps API key (set as `GOOGLE_MAPS_API_KEY` constant in `wp-config.php`)

---

## Configuration

Site-wide settings (phone, address, social links, business hours, footer copy) are managed through the **Site Settings** options pages in the WordPress admin, registered via ACF Options.

```
WP Admin → Site Settings → General
                         → Announcement Bar
                         → Archive Heroes
```

Map pins and service areas are hardcoded in `functions.php` alongside the Google Maps initialization. Schema `areaServed` is maintained separately in `inc/schema.php → globeiron_schema_area_served()`.

---

## Development Notes

- **ACF field groups** are version-controlled as JSON in `acf-json/`. Do not register existing groups via `acf_add_local_field_group()` in PHP — ACF's JSON loader fires last on `acf/init` and wins, reverting to the JSON definition.
- **Tailwind** is used only as a utility layer for spacing and layout in a handful of sections. Core design tokens (colours, type scale, spacing) live in `src/scss/_variables.scss`.
- **GSAP** handles the hero animations, SVG path draws, and crosshair scroll reveals. Splide.js handles the reviews and work sliders.
- The `globeiron_review` CPT feeds both the front-end review slider block and the `Review` schema nodes in the homepage `@graph`.

---

## Credits

**Design** — [BLDG](https://bldgbrands.com)

**Development** — Mike Beasley, [Black Lab Development](https://www.blacklabdev.com) — Cincinnati, OH

> *"Solid roofs, precise code."*
