<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Feb 6, 2014
 * Time: 4:52:36 PM
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */

/**
 * AllTreeHelperTest
 * 
 * @package TreeHelperTest
 * @subpackage Tree
 */
class AllTreeHelperTest extends PHPUnit_Framework_TestSuite {

	/**
	 * Suite define the tests for this suite
	 *
	 * @return void
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All TreeHelper Tests');

		$path = App::pluginPath('TreeHelper') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);
		return $suite;
	}
}