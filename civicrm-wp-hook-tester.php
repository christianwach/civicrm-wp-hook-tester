<?php
/*
Plugin Name: CiviCRM Hook Tester
Description: Just for testing...
Author: Christian Wach
Version: 0.1
Author URI: http://haystack.co.uk
*/



/**
 * Multiple add_filter test, mimicking hook_civicrm_managed()
 */
function my_test_callback( $entities ) {
	
	$entities[] = array(
		'module' => 'com.example.modulename',
		'name' => 'myreport',
		'entity' => 'ReportTemplate',
		'params' => array(
			'version' => 3,
			'label' => 'Example Report',
			'description' => 'Longish description of the example report',
			'class_name' => 'CRM_Modulename_Report_Form_Sybunt',
			'report_url' => 'mymodule/mysbunt',
			'component' => 'CiviContribute',
		),
	);
	
	// --<
	return $entities;

}

// uncomment to add actions for the above
//add_filter( 'blah', 'my_test_callback', 10, 1 );
//add_filter( 'blah', 'my_test_callback', 11, 1 );
//add_filter( 'blah', 'my_test_callback', 12, 1 );

/*
// start with an empty array
$arg1 = array();

// mimic how CRM_Utils_Hook_WordPress::invoke parses parameters
$x = array_slice( array( &$arg1 ), 0, 1 );

// trace
print_r( "\n\n" );
print_r( "pre-hook\n" );
print_r( $arg1 );

// run hook
$return = apply_filters_ref_array( 'blah', $x );

// trace
print_r( "\n\n" );
print_r( "post-hook\n" );

print_r( "\n\n" );
print_r( "arg1\n" );
print_r( $arg1 );

print_r( "\n\n" );
print_r( "return\n" );
print_r( $return );
die();
*/





/**
 * Global scope callback function
 * 
 * Note that CiviCRM expects $params to be passed by reference, but that
 * do_action_ref_array passes by value
 */
function my_test_civi_callback( $op, $objectName, $id, $params ) {
	
	/*
	// trace
	print_r( "\n\n" );
	print_r( 'my_test_civi_callback'."\n" ); 
	print_r( array( $op, $objectName, $id, $params ) ); 
	die();
	*/
	
	// add something
	$params['foo'] = 'bar'; 
	
	// --<
	return $params;
	
}

// uncomment to add action for the above
//add_filter( 'civicrm_pre', 'my_test_civi_callback', 10, 4 );






/**
 * TestingCiviCrmWordPressHooks Class
 */
class TestingCiviCrmWordPressHooks {



	/** 
	 * @description: initialises this object
	 * @return object
	 */
	function __construct() {
	
		// add class method to hook
		add_action( 'civicrm_pre', array( $this, 'my_test_civi_callback' ), 10, 4 );
		add_action( 'civicrm_pre', array( $this, 'my_test_civi_callback2' ), 11, 4 );
		
		// --<
		return $this;

	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_test_civi_callback( $op, $objectName, $id, $params ) {
	
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'TestingCiviCrmWordPressHooks my_test_civi_callback'."\n" ); 
		print_r( array( $op, $objectName, $id, $params ) ); 
		die();
		*/
		
		// let's see what happens
		//$op .= '-opone';
		//$objectName .= '-one';
		//$id++;
		$params['foo'] = 'bar'; 
		
		// --<
		return $params;
		
	}



	/** 
	 * @description: class method as callback
	 * @return object
	 */
	function my_test_civi_callback2( $op, $objectName, $id, $params ) {
	
		/*
		// trace
		print_r( "\n\n" );
		print_r( 'TestingCiviCrmWordPressHooks my_test_civi_callback2'."\n" ); 
		print_r( array( $op, $objectName, $id, $params ) ); 
		die();
		*/
		
		// let's see what happens
		//$op .= '-optwo';
		//$objectName .= '-two';
		//$id++;
		$params['foo2'] = 'bar2'; 
		
		// --<
		return $params;
		
	}



} // class ends

// uncomment to instantiate plugin class above
//$testing_civicrm_wordpress_hooks = new TestingCiviCrmWordPressHooks;
