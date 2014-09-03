CiviCRM WordPress Hook Tests
============================

A simple plugin for testing CiviCRM hooks in WordPress. Its major advance over the current Drupal-centric hook system is that it allows multiple WordPress plugins to receive callbacks from CiviCRM hooks. To enable this, do **one** of the following:

* Replace `wp-content/plugins/civicrm/civicrm/CRM/Utils/Hook/WordPress.php` with the file `civicrm_custom_php/CRM/Utils/Hook/WordPress.php` bundled with this plugin. Bear in mind that the file will be overwritten every time you upgrade CiviCRM.
* The better option is to put the file `civicrm_custom_php/CRM/Utils/Hook/WordPress.php` bundled with this plugin in a path such as:
`/wp-content/plugins/files/civicrm/custom_php/CRM/Utils/Hook/WordPress.php`
and then tell CiviCRM where to find its resources by going to 
*Administer* --> *System Settings* --> *Directories*
and fill that out appropriately.

*Also note:* if choose your copy of `WordPress.php` based on the version of CiviCRM that you are running. CiviCRM has added an extra parameter to hook callbacks in version 4.5, so if you're running version 4.5, switch to the 4.5 branch of this repo and use that version of `WordPress.php` instead.

### Notes for developers ###

This is a copy of the notes in the main plugin file.

#### Callbacks with return values ####

Because of the way some CiviCRM hooks are constructed, the WordPress Plugin API cannot be used for any hooks that require a return value, because they are seemingly incompatible with `apply_filters` and `apply_filters_ref_array`. Luckily, there are very few of them at present. The full list is:

* `civicrm_upgrade`
* `civicrm_validate` (obsolete in CiviCRM 4.3)
* `civicrm_validateForm`
* `civicrm_caseSummary`
* `civicrm_dashboard`
* `civicrm_links`
* `civicrm_aclWhereClause`
* `civicrm_alterSettingsMetaData`

Any plugins that need to have callbacks for these hooks must register a unique code with CiviCRM. The code must be a valid part of a PHP function name, so stick to letters and underscores. This allows them to use global scope functions as callbacks in the form:

`{code}_{hookname}`

This plugin uses the prefix `my_unique_plugin_code` and the callback function name for `hook_civicrm_links` thus becomes:

`my_unique_plugin_code_civicrm_links`

If a plugin needs to keep its logic inside a class, it will have to route the call to the class method from the global function. If you want to set a priority for the callback, well, good luck. Yes, Drupal-style hooks in WordPress, oh joy.

#### Callbacks without return values ####

However, if your plugin doesn't need to create callbacks for any of these hooks, you can simply use the WordPress Plugin API as per usual:

`add_action( 'civicrm_hookname', array( $this, 'my_callback' ), $priority, $num_args );`

So, to hook into what CiviCRM's docs call `hook_civicrm_tabs` (and you wanted to do so with a priority) you would use something like:

`add_action( 'civicrm_tabs', array( $this, 'my_callback' ), 20, 2 );`

Yay!

Just remember that because the parameters are passed by reference, you can modify them in your callback if you need to and that modification will be passed on to the next callback in its modified state.
