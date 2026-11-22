# Xclusive template for Joomla 6

Site template of [Xclusive Ballonvaarten](https://xclusiveballonvaarten.com/) for Joomla 6:
a child template of Cassiopeia that reproduces the look of the Joomla 3 template
`xclusive-joostrap` (based on the Joostrap "Portfolio" theme, Bootstrap 3).

Goal: get the site running on Joomla 6 quickly, looking as unchanged as possible. It is a
port, not a redesign.

## Install

```powershell
.\build.ps1
```

This writes `dist/tpl_xclusive-<version>.zip`. Install it in Joomla (System → Install →
Extensions). Cassiopeia must be installed (it is a core template). Then make a style of
**Xclusive** the default site style and set the options below.

## Template style options

| Option | Joomla 3 equivalent | Value for the live site |
| --- | --- | --- |
| Logo | Logo | `images/Logo/logo-white-transparant-min.png` (default) |
| Share Image Home Page | hard-coded `og:image` in `index.php` | `images/gallery1/06-xclusive-impressie8.jpg` (default) |
| Favicon | `favicon.ico` in the template folder | empty: the Xclusive balloon of the template is used |
| Component on Home Page | Frontpage show | Yes |
| Mobile Menu From | Menu slide | Right |
| Back to Top Link | To top | No (it was off on the live site) |
| Left / Right Sidebar Width | same | 4 / 4 (no sidebars are in use now) |
| Google Tag Manager ID | hard-coded in `index.php` | the `GTM-…` container ID from the old `index.php` |

The Google Fonts options of the old style (Lato for `p, li, h2, h3, h4`, Playfair Display
for `h1`) are now fixed in `xclusive.css` and `joomla.asset.json`, with the same Google
Fonts URLs.

## Module positions

All positions of `xclusive-joostrap` are kept with the same names, so module assignments
survive the migration:

`menu`, `mob-menu-above`, `mob-menu-below`, `carousel`, `strapline`, `top`, `top-article`,
`left`, `left_menu`, `right`, `middle`, `bottom1`, `bottom2`, `footer1`, `ondermenu1`–`4`,
`footer2`, `debug`.

`ondermenu1`–`4` (footer menu columns) were used by the old `index.php` but missing from
its manifest; they are now listed. `left_menu` was in the old manifest but never rendered;
it is listed but not rendered either. Positions in use on the live site in November 2026:
`menu`, `ondermenu1`–`4`, `footer2`.

Module chromes used by the positions: `none`, `standard` (ported from the old
`html/modules.php`) and `xhtml` (removed from the core in Joomla 4, added as a layout).

## What was ported

| Joomla 3 (`templates/xclusive-joostrap`) | Here |
| --- | --- |
| `index.php` (+ `functions/tpl-init.php`) | `src/index.php`: same HTML structure, classes and positions; body classes as before (`parentid-…`, menu alias, `view-…`, `frontpage`, `itemid-…`, `loggedin/loggedout`, `no-rtl`) |
| `css/custom.css` (live version) | `src/media/css/xclusive.css`, unchanged except the Regular Labs and JCE rules removed |
| `css/style.css`, `css/j-backbone.css` | `src/media/css/legacy.css`: the sections the site uses, verbatim |
| `css/bootstrap.min.css` (Bootstrap 3) | `src/media/css/bs3.css`: only the Bootstrap 3 rules the theme depends on (container widths, floats, navbar, dropdown, caret, `col-xs-*`, `visible-*`/`hidden-*`, `pull-*`, `img-responsive`), at the Bootstrap 3 breakpoints |
| `html/mod_menu/*` | `src/html/mod_menu/`: `nav menu` lists, `navbar-nav` / `dropdown` / `dropdown-menu` / caret in position `menu` |
| `html/mod_custom/default.php` | `src/html/mod_custom/default.php`: module class suffix on the `custom` div (for `.custom.headermodule`) |
| `html/modules.php` (`standard` chrome) | `src/html/layouts/chromes/standard.php` |
| jQuery mmenu (mobile menu) | Bootstrap 5 offcanvas (core asset), styled like mmenu "mm-dark" |
| `js/j-backbone.js` (back to top) | `src/media/js/xclusive.js` (no jQuery) |
| `favicon.ico` | `src/media/images/favicon.ico` |

CSS load order: Cassiopeia `template.css` (Bootstrap 5) → `bs3.css` → `legacy.css` →
`compat.css` → `xclusive.css`. `compat.css` holds the fixes for what Joomla 6 / Cassiopeia
changes (footer gradient, flex footer columns, link underlines, the offcanvas menu,
Font Awesome 4 brand icons).

Not ported: Bootstrap 3 JavaScript, jQuery and the jQuery plugins (fancybox, isotope,
swiper, prettify, instafeed, flickr, sticky, retina, mmenu), fontello/budicons icon fonts,
the LESS compiler, the colour/custom-CSS/Google-Fonts options, the old Universal Analytics
code (already switched off), `component.php` and `error.php` (Cassiopeia's are used), and
the `com_contact`, `com_content`, `com_users` and `mod_search` overrides (Joomla 6 core
layouts are used; `mod_search` no longer exists). JCE, Regular Labs and AcyMailing styling
is left out, as those extensions do not come back.

## Checked so far

Without a Joomla 6 test site (no database running), a static page was made with the
Joomla 6.2 Cassiopeia and Font Awesome CSS, these CSS files, and the menu, home page
content and footer HTML of the live site. Compared with the live site at 1280, 800 and
375 px: header bar, logo, menu items (same positions to the pixel), dropdown on hover,
headings, text, buttons, footer menu columns and red footer match. The PHP files pass
`php -l`; the manifest, asset JSON and language files parse.

## Differences to check on a Joomla 6 test site

1. **Mobile menu**: mmenu pushed the page aside and opened submenus as sliding panels;
   the offcanvas overlays the page and shows submenus expanded under their parent.
2. **Core output in the content area**: article layout, system messages, pagination,
   contact form and login forms now come from Joomla 6 core layouts (Bootstrap 5
   markup) instead of the Joostrap overrides; spacing and buttons may differ.
3. **Content with Bootstrap 3 classes**: `col-xs-*`, `col-sm/md/lg-*`, `pull-*`,
   `img-responsive`, `visible-*`, `table-responsive` are covered; other Bootstrap 3
   components in articles (`panel`, `well`, `label`, `glyphicon`, `btn-default`,
   `collapse.in`, data-toggle attributes) are not.
4. **Icons**: Font Awesome 4 classes (`fa fa-…`) now use Joomla's Font Awesome 6; brand
   icons (facebook, youtube, …) are mapped to the brands font. Check the footer social
   icons and the db8 social share buttons, if those come back.
5. **Revolution slider / Widgetkit / floating buttons**: their CSS is still in
   `legacy.css`, but whether they come back is decided elsewhere; check the home page
   slider area.
6. **RSForm Pro forms**: the form rules of the old theme are kept; RSForm on Joomla 6
   uses its own Bootstrap 5 layout, so check the booking forms.
7. **Error and offline pages** use Cassiopeia's layout, not the old look.
8. **Widths between 768 and 991 px**: Bootstrap 3 breakpoints are restored for the grid
   and the navbar; check pages with sidebars at tablet width if sidebars come into use.
9. **Fonts**: same Google Fonts URLs as before (Roboto, Roboto Condensed, Roboto Slab,
   Lato, Playfair Display). Consider hosting them locally (privacy).

## License

GNU General Public License version 2 or later, see [LICENSE.txt](LICENSE.txt). The theme
CSS in `legacy.css` comes from Joostrap Portfolio, (C) 2005 - 2014 Joostrap, also GPL.
