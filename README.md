# WP Rizzo Menu

This will insert a WordPress nav menu into the correct place in the body header HTML that is provided by [WP Rizzo](https://github.com/lonelyplanet/wp-rizzo).

## Requirements

This plugin requires a custom body header route that provides an empty navigation area.
This could be something like what Ben is working on: http://rizzo.lonelyplanet.com/custom/india/body

---

The custom rizzo routes are a work in progress as of 2014-07-02.

**This plugin should not be used until the custom routes are finalized.**

## Installation

1. Install and activate the [WP Rizzo](https://github.com/lonelyplanet/wp-rizzo) plugin.
1. Configure WP Rizzo with the custom body header route.
1. Activate this plugin on the plugins page in the admin.
1. Create navigation menu in WordPress admin > appearance > menus.
1. Assign your navigation menu to the rizzo menu location.

### Known Limitations

The Rizzo menu styles currently support only one drop down menu level.
If you want to do nested drop down menus, you will have to add your own CSS rules to do that.