<?php 
/*-----8<--------------------------------------------------------------------
 * 
 * BEdita - a semantic content management framework
 * 
 * Copyright 2008 ChannelWeb Srl, Chialab Srl
 * 
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the Affero GNU General Public License as published 
 * by the Free Software Foundation, either version 3 of the License, or 
 * (at your option) any later version.
 * BEdita is distributed WITHOUT ANY WARRANTY; without even the implied 
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the Affero GNU General Public License for more details.
 * You should have received a copy of the Affero GNU General Public License 
 * version 3 along with BEdita (see LICENSE.AGPL).
 * If not, see <http://gnu.org/licenses/agpl-3.0.html>.
 * 
 *------------------------------------------------------------------->8-----
 */

/**
 * 
 * @link			http://www.bedita.com
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
require_once ROOT . DS . APP_DIR. DS. 'tests'. DS . 'bedita_base.test.php';

class QuestionTestCase extends BeditaTestCase {

 	var $uses		= array('Question') ;
    var $dataSource	= 'test' ;	
 	var $components	= array('Transaction') ;

 	protected $inserted = array();
 	
    /////////////////////////////////////////////////
    //      TEST METHODS
    /////////////////////////////////////////////////
 	function testActsAs() {
 		$this->checkDuplicateBehavior($this->Question);
 	}
 	
 	function testInsert() {
		$this->requiredData(array("insert"));
		$result = $this->Question->save($this->data['insert']['multiple']) ;
		$this->assertEqual($result,true);		
		if(!$result) {
			debug($this->Question->validationErrors);
			return ;
		}
		
		$result = $this->Question->findById($this->Question->id);
		pr("Question created:");
		pr($result);
		$this->inserted[] = $this->Question->id;
	} 
	
	
 	function testDelete() {
        pr("Removinge inserted questions:");
        foreach ($this->inserted as $ins) {
        	$result = $this->Question->delete($ins);
			$this->assertEqual($result, true);		
			pr("Questions deleted");
        }        
 	}
 	
    /////////////////////////////////////////////////
	//     END TEST METHODS
	/////////////////////////////////////////////////

	protected function cleanUp() {
		$this->Transaction->rollback() ;
	}
	
	public   function __construct () {
		parent::__construct('Question', dirname(__FILE__)) ;
	}	
}

?> 