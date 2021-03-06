<?php
/*-----8<--------------------------------------------------------------------
 * 
 * BEdita - a semantic content management framework
 * 
 * Copyright 2011 ChannelWeb Srl, Chialab Srl
 * 
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published 
 * by the Free Software Foundation, either version 3 of the License, or 
 * (at your option) any later version.
 * BEdita is distributed WITHOUT ANY WARRANTY; without even the implied 
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Lesser General Public License for more details.
 * You should have received a copy of the GNU Lesser General Public License 
 * version 3 along with BEdita (see LICENSE.LGPL).
 * If not, see <http://gnu.org/licenses/lgpl-3.0.html>.
 * 
 *------------------------------------------------------------------->8-----
 */

/**
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
require_once ROOT . DS . APP_DIR. DS. 'tests'. DS . 'bedita_base.test.php';

class BelibTestCase extends BeditaTestCase {
	
	var $uses = array();
	
 	public   function __construct () {
		parent::__construct('Belib', dirname(__FILE__)) ;
	}
	
 	function testSqlDate() {
		$this->requiredData(array("little-endian", "middle-endian", "ddmm", "mmdd", "m-d", "sql", "year", "yyyy"));
		
		// littleEndian
		$expect = $this->data['sql'];
		foreach ($this->data['little-endian'] as $d) {
			$result = BeLib::sqlDateFormat($d, "little-endian");
			pr($d . " -- " . $result);			
			$this->assertEqual($result, $expect);
		}
		
		// middleEndian
		foreach ($this->data['middle-endian'] as $d) {
			$result = BeLib::sqlDateFormat($d, "middle-endian");
			pr($d . " -- " . $result);
			$this->assertEqual($result, $expect);
		}
		
		// year
		$date = new DateTime();
		$year = $date->format("Y");
		
		$expect = $year . "-" .  $this->data["m-d"];
		// littleEndian - only ddmm
		foreach ($this->data['ddmm'] as $d) {
			$result = BeLib::sqlDateFormat($d, "little-endian");
			pr($d . " -- " . $result);			
			$this->assertEqual($result, $expect);
		}
		
		// middleEndian - only mmdd
		foreach ($this->data['mmdd'] as $d) {
			$result = BeLib::sqlDateFormat($d, "middle-endian");
			pr($d . " -- " . $result);
			$this->assertEqual($result, $expect);
		}
		
		// year
		$count = 0;
		$mmdd = "01-01";
//		$mmdd = $date->format("m-d");
		foreach ($this->data['year'] as $d) {
			$result = BeLib::sqlDateFormat($d);
			pr($d . " -- " . $result);
			$expect = $this->data['yyyy'][$count] . "-" . $mmdd;
			$this->assertEqual($result, $expect);
			$count++;
		}
	}
	
	function testVariableFromNickname() {
		$this->requiredData(array("nickname"));
		
		foreach ($this->data["nickname"] as $nickname => $expectedVarName) {
			$varName = BeLib::getInstance()->variableFromNickname($nickname);
			pr($nickname . " => " . $varName);
			$this->assertEqual($varName, $expectedVarName);
		}
	}
	
	function testUrlfriendly() {
		$name = "Test A.jpg";
		$expected = "test-a-jpg";
		$result = BeLib::getInstance()->friendlyUrlString($name);
		$this->assertEqual($result, $expected);
	
		// preserve .
		$expected = "test-a.jpg";
		$result = BeLib::getInstance()->friendlyUrlString($name, "\.");
		$this->assertEqual($result, $expected);
	}
	
	
}
?>