<?php
/*
Plugin Name: CiviCRM Hook Tester
Description: For testing, um, CiviCRM hooks...
Author: Christian Wach
Version: 0.2
Author URI: http://haystack.co.uk
*/



/**
--------------------------------------------------------------------------------
Notes for developers
--------------------------------------------------------------------------------



----------------------------
CALLBACKS WITH RETURN VALUES
----------------------------

Because of the way CiviCRM hooks are constructed, the WordPress Plugin API cannot 
be used for any hooks that require a return value, because they are seemingly 
incompatible with apply_filters and apply_filters_ref_array.

Luckily, there are very few of them at present. The full list is:

'civicrm_upgrade'
'civicrm_validate' (obsolete in CiviCRM 4.3)
'civicrm_validateForm'
'civicrm_caseSummary'
'civicrm_dashboard'
'civicrm_links'
'civicrm_aclWhereClause'
'civicrm_alterSettingsMetaData'

Any plugins that need to have callbacks for these hooks must register a unique code 
with CiviCRM. The code must be a valid part of a PHP function name, so stick to
letters and underscores. This allows them to use global scope functions as callbacks 
in the form:

{code}_{hookname}

This plugin uses the prefix 'my_unique_plugin_code' and the callback function name 
for 'hook_civicrm_links' thus becomes:

'my_unique_plugin_code_civicrm_links'

If a plugin needs to keep its logic inside a class, it will have to route the call 
to the class method from the global function. 

If you want to set a priority for the callback, well, good luck.

Yes, Drupal-style hooks in WordPress, oh joy.



-------------------------------
CALLBACKS WITH NO RETURN VALUES
-------------------------------

However, if your plugin doesn't need to create callbacks for any of these hooks, 
you can simply use the WordPress Plugin API as per usual:

add_action( 'civicrm_hookname', array( $this, 'my_callback' ), $priority, $num_args );

So, to hook into what CiviCRM's docs call 'hook_civicrm_tabs' (and you wanted to do
so with a priority) you would use something like:

add_action( 'civicrm_tabs', array( $this, 'my_callback' ), 20, 2 );

Yay!

Just remember that because the parameters are passed by reference, you can modify
them in your callback if you need to and that modification will be passed on to the
next callback in its modified state.

--------------------------------------------------------------------------------
*/



/**
 * CiviCRM_WordPress_Hook_Tester Class
 */
class CiviCRM_WordPress_Hook_Tester {



	/** 
	 * @description: initialises this object
	 * @return object
	 */
	function __construct() {
		
		// register this plugin's unique code with CiviCRM so it can receive callbacks
		// for certain hooks. Given that CiviCRM requires PHP5.3, you could return this
		// with an anonymous function, but I've resisted for clarity.
		add_filter( 'civicrm_wp_plugin_codes', array( $this, 'register_my_civicrm_plugin_code' ) );

		// add class method to hook
		add_action( 'civicrm_config', array( $this, 'my_civi_config_callback' ), 10, 1 );
		add_action( 'civicrm_config', array( $this, 'my_civi_config_callback2' ), 11, 1 );
		
		// add class method to hook
		add_action( 'civicrm_pre', array( $this, 'my_civi_pre_callback' ), 10, 4 );
		add_action( 'civicrm_pre', array( $this, 'my_civi_pre_callback2' ), 11, 4 );
		
		// add class method to hook
		add_action( 'civicrm_tabs', array( $this, 'my_civi_tabs_callback' ), 10, 2 );
		//add_action( 'civicrm_pre', array( $this, 'my_civi_pre_callback2' ), 11, 4 );
		
		// --<
		return $this;

	}



	/** 
	 * @description: class method as callback for 'civicrm_wp_plugin_codes'
	 * @return array
	 */
	function register_my_civicrm_plugin_code( $plugin_codes ) {
	
		// add our unique code and send back to CiviCRM
		$plugin_codes[] = 'my_unique_civicrm_plugin_code';
		return $plugin_codes;
		
	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_civi_config_callback( $config ) {
	
		// let's see what happens
		$config->foo = 'foo'; 
		
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'CiviCRM_WordPress_Hook_Tester my_civi_config_callback'."\n" ); 
		print_r( array( 'config' => $config ) ); 
		die();
		*/
		
	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_civi_config_callback2( $config ) {
	
		// let's see what happens
		$config->bar = 'bar'; 
		
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'CiviCRM_WordPress_Hook_Tester my_civi_config_callback2'."\n" ); 
		print_r( array( 'config' => $config ) ); 
		die();
		*/
		
	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_civi_pre_callback( $op, $objectName, $id, &$params ) {
	
		// let's see what happens
		$params['foo'] = 'foo'; 
		
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'CiviCRM_WordPress_Hook_Tester my_civi_pre_callback'."\n" ); 
		print_r( array( 'op' => $op, 'objectName' => $objectName, 'id' => $id, 'params' => $params ) ); 
		die();
		*/
		
	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_civi_pre_callback2( $op, $objectName, $id, &$params ) {
	
		// let's see what happens
		$params['bar'] = 'bar'; 
		
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'CiviCRM_WordPress_Hook_Tester my_civi_pre_callback2'."\n" ); 
		print_r( array( 'op' => $op, 'objectName' => $objectName, 'id' => $id, 'params' => $params ) ); 
		die();
		*/
		
	}



	/** 
	 * @description: class method as callback for 'hook_civicrm_tabs'
	 * @return object
	 */
	function my_civi_tabs_callback( &$tabs, $contactID ) {
	
		// example taken from the CiviCRM docs for this hook:
		// http://wiki.civicrm.org/confluence/display/CRMDOC43/hook_civicrm_tabs
 
		// unset the contribition tab, i.e. remove it from the page
		unset( $tabs[1] );
 
		// let's add a new "contribution" tab with a different name and put it last
		// this is just a demo, in the real world, you would create a url which would
		// return an html snippet etc.
		$url = CRM_Utils_System::url( 'civicrm/contact/view/contribution',
									  "reset=1&snippet=1&force=1&cid=$contactID" );
		$tabs[] = array( 'id'    => 'mySupercoolTab',
						 'url'   => $url,
						 'title' => 'Contribution Tab Renamed',
						 'weight' => 300 );

		/*
		// trace
		print_r( "\n\n" );
		print_r( 'CiviCRM_WordPress_Hook_Tester my_civi_tabs_callback'."\n" ); 
		print_r( array( 'tabs' => $tabs, 'contactID' => $contactID ) ); 
		die();
		*/
		
	}



} // class ends



// instantiate plugin class above
$testing_civicrm_wordpress_hooks = new CiviCRM_WordPress_Hook_Tester;



/**
--------------------------------------------------------------------------------
Global scope callbacks
--------------------------------------------------------------------------------
*/

/** 
 * @description: global function as callback for 'hook_civicrm_links'
 * @return object
 */
function my_unique_civicrm_plugin_code_civicrm_links( $op, $objectName, $objectId, &$links ) {

	// example taken from the CiviCRM docs for this hook:
	// http://wiki.civicrm.org/confluence/display/CRMDOC43/hook_civicrm_links

	/*
	// trace
	print_r( "\n\n" );
	print_r( 'my_unique_civicrm_plugin_code_civicrm_links'."\n" ); 
	print_r( array( 'op' => $op, 'objectName' => $objectName, 'objectId' => $objectId, 'links' => $links ) ); 
	//die();
	*/
	
	// init return
	$mylinks = array();
	
	switch ($objectName) {

		case 'Contact':

			switch ($op) {
				
				// place link in the menu that is accessible from the "Actions" button 
				// at top left on a Contact's page
				case 'view.contact.activity':
					// Adds a link to the main tab.
					$my_links[] = array(
						'id' => 'mymoduleactions',
						'title' => 'My Module Actions',
						'url' => '/'. $objectId,
						'weight' => 1,
						'ref'    => 'my-module-link' // this is unique identifier for this link
					);
					break;
				
				// I think this is a Drupal block
				case 'create.new.shortcuts':
					// add link to create new profile
					$links[] = array( 
						'url'   => '/civicrm/admin/uf/group?action=add&reset=1',
						'title' => ts('New Profile'),
						'ref'   => 'new-profile'
					);
					break;
					
			}

	}
	
	
	// return our links
	return $my_links;
	
}



