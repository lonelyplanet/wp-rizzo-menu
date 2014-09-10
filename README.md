# WP Rizzo Menu

This will insert a WordPress nav menu into the correct place in the body header HTML that is provided by [WP Rizzo](https://github.com/lonelyplanet/wp-rizzo).

## Requirements

This plugin requires a custom route that provides an empty navigation area.
Please contact us to get your API endpoints.

## Installation

1. Install and activate the [WP Rizzo](https://github.com/lonelyplanet/wp-rizzo) plugin.
1. Configure WP Rizzo with the custom body header route.
1. Activate this plugin on the plugins page in the admin.
1. Create navigation menu in WordPress admin > appearance > menus.
1. Assign your navigation menu to the rizzo menu location.

### Known Limitations

The Rizzo menu styles currently support only one drop down menu level.
If you want to do nested drop down menus, you will have to add your own CSS rules to do that.
