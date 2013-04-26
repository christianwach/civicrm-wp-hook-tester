<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */
class CRM_Utils_Hook_WordPress extends CRM_Utils_Hook {

	function invoke(
		$numParams,
		&$arg1, &$arg2, &$arg3, &$arg4, &$arg5,
		$fnSuffix
	) {

		// only pass the arguments that have values
		$args = array_slice( 
			array( &$arg1, &$arg2, &$arg3, &$arg4, &$arg5 ), 
			0, 
			$numParams
		);

		// use WordPress API to run hooks
		// uncomment in the real world and delete everything below
		//return apply_filters_ref_array( $fnSuffix, $args );

		// to test return values
		$return = apply_filters_ref_array( $fnSuffix, $args );

		/*
		// testing 'hook_civicrm_pre', for example
		if ( $fnSuffix == 'civicrm_pre' ) {

			// trace stuff
			print_r( "\n\n" );
			print_r( 'invoke-post-apply_filters_ref_array' ); 

			print_r( "\n\n" );
			print_r( 'returned value'."\n" ); 
			print_r( $return ); 

			print_r( "\n\n" );
			print_r( 'arg1'."\n" ); 
			print_r( $arg1 ); 

			print_r( "\n\n" );
			print_r( 'arg2'."\n" ); 
			print_r( $arg2 ); 

			print_r( "\n\n" );
			print_r( 'arg3'."\n" ); 
			print_r( $arg3 ); 

			print_r( "\n\n" );
			print_r( 'arg4'."\n" ); 
			print_r( $arg4 ); 

			print_r( "\n\n" );
			print_r( 'arg5'."\n" ); 
			print_r( $arg5 ); 

			die();

		}
		*/

		// --<
		return $return;

	}

}

