<?php
/*-----8<--------------------------------------------------------------------
 * 
 * BEdita - a semantic content management framework
 * 
 * Copyright 2008 ChannelWeb Srl, Chialab Srl
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
 * 
 *
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
class AddressbookController extends ModulesController {
	
	var $name = 'Addressbook';
	var $helpers 	= array('BeTree', 'BeToolbar');
	var $components = array('BeTree', 'BeCustomProperty', 'BeLangText', 'BeFileHandler');

	var $uses = array('BEObject','Tree', 'Category', 'Card', 'MailGroup') ;
	protected $moduleName = 'addressbook';
	
    public function index($id = null, $order = "", $dir = true, $page = 1, $dim = 20) {
		$conf  = Configure::getInstance() ;
		$filter["object_type_id"] = $conf->objectTypes['card']["id"];
		$filter["Card.country"] = "";
		$filter["Card.email"] = "";
		$filter["Card.company_name"] = "";
		$filter["object_user"] = "card";
		$filter["count_annotation"] = "EditorNote";
		$this->paginatedList($id, $filter, $order, $dir, $page, $dim); 
		$this->loadCategories($filter["object_type_id"]);
		$this->loadMailgroups();
	 }

	function view($id = null) {
		if($id == null) {
			Configure::write("defaultStatus", "on"); // set default ON for new objects
		}
		$this->viewObject($this->Card, $id);
		$this->set("groupsByArea", $this->MailGroup->getGroupsByArea(null, $id));
	}

	function save() {
		$this->checkWriteModulePermission();
		$this->Transaction->begin();
		$kind = ($this->request->data['company']==0) ? 'person' : 'cmp';
		if($kind == 'person') {
			if(!empty($this->request->data['person']['name']) || !empty($this->request->data['person']['surname'])) {
				$this->request->data['title'] = $this->request->data['person']['name']." ".$this->request->data['person']['surname'];
			}
			$this->request->data['birthdate'] = $this->request->data['person']['birthdate'];
			$this->request->data['deathdate'] = $this->request->data['person']['deathdate'];
		} else {
			if(!empty($this->request->data['cmp']['company_name'])) {
				$this->request->data['title'] = $this->request->data['cmp']['company_name'];
			}
			$this->request->data['company_name'] = $this->request->data['cmp']['company_name'];
		}

		$this->request->data['name'] = $this->request->data[$kind]['name'];
		$this->request->data['surname'] = $this->request->data[$kind]['surname'];
		$this->request->data['person_title'] = $this->request->data[$kind]['person_title'];
		$this->request->data['company_name'] = $this->request->data[$kind]['company_name'];
		if(empty($this->request->data['User'][0])) {
			$this->request->data['User'] = array();
		}
		
		$this->saveObject($this->Card);
	 	$this->Transaction->commit();
	 	if(empty($this->request->data["title"])) {
	 		$this->request->data["title"] = "";
	 	}
		$this->userInfoMessage(__("Card saved")." - ".$this->request->data["title"]);
		$this->eventInfo("card [". $this->request->data["title"]."] saved");
	}

	function delete() {
		$this->checkWriteModulePermission();
		$objectsListDeleted = $this->deleteObjects("Card");
		$this->userInfoMessage(__("Card deleted") . " -  " . $objectsListDeleted);
		$this->eventInfo("card $objectsListDeleted deleted");
	}

	function deleteSelected() {
		$this->checkWriteModulePermission();
		$objectsListDeleted = $this->deleteObjects("Card");
		$this->userInfoMessage(__("Cards deleted") . " -  " . $objectsListDeleted);
		$this->eventInfo("cards $objectsListDeleted deleted");
	}
	
	public function categories() {
		$this->showCategories($this->Card);
	}

	public function saveCategories() {
		$this->checkWriteModulePermission();
		if(empty($this->request->data["label"])) 
 	 	    throw new BeditaException( __("No data"));
		$this->Transaction->begin() ;
		if(!$this->Category->save($this->request->data)) {
			throw new BeditaException(__("Error saving tag"), $this->Category->validationErrors);
		}
		$this->Transaction->commit();
		$this->userInfoMessage(__("Category saved")." - ".$this->request->data["label"]);
		$this->eventInfo("category [" .$this->request->data["label"] . "] saved");
	}
	
	public function deleteCategories() {
		$this->checkWriteModulePermission();
		if(empty($this->request->data["id"])) 
 	 	    throw new BeditaException( __("No data"));
 	 	$this->Transaction->begin() ;
		if(!$this->Category->delete($this->request->data["id"])) {
			throw new BeditaException(__("Error saving tag"), $this->Category->validationErrors);
		}
		$this->Transaction->commit();
		$this->userInfoMessage(__("Category deleted") . " -  " . $this->request->data["label"]);
		$this->eventInfo("Category " . $this->request->data["id"] . "-" . $this->request->data["label"] . " deleted");
	}

	public function cloneObject() {
		unset($this->request->data['ObjectUser']);
		parent::cloneObject();
	}

	private function loadMailgroups() {
		$result = ClassRegistry::init("MailGroup")->find("all",
			array(
				"fields" => array("id","group_name"),
				"contain" => array()
			)
		);
		$mailgroups = array();
		foreach($result as $k => $v) {
			$mailgroups[$k] = $v['MailGroup'];
		}
		$this->set("mailgroups",$mailgroups);
	}

	public function addToMailgroup() {
		$this->checkWriteModulePermission();
		$counter = 0;
		if(!empty($this->request->params['form']['objects_selected'])) {
			$objects_to_assoc = $this->request->params['form']['objects_selected'];
			$mailgroup = $this->request->data['mailgroup'];
			$MailGroupObj = ClassRegistry::init("MailGroupCard");
			$this->Transaction->begin() ;
			for($i = 0; $i < count($objects_to_assoc); $i++) {
				// get email from  card
				$email = $this->Card->field("newsletter_email", array("id" => $objects_to_assoc[$i]));
				if(!empty($email)) { // if 'newsletter_email' skip saving
					$data = array(
						"card_id"=>$objects_to_assoc[$i],
						"mail_group_id" => $mailgroup
					);
					$mg = $MailGroupObj->find("first",array('conditions' => $data));
					if(empty($mg)) { // if relation already exists, skip saving
						$data["status"] = "confirmed";
						$MailGroupObj->create();
						$MailGroupObj->save($data);
						$counter++;
					}
				}
			}
			$this->Transaction->commit() ;
			$this->userInfoMessage("$counter" . __("card(s) associated to mailgroup") . " - " . $mailgroup);
			$this->eventInfo("$counter card(s) associated to mailgroup " . $mailgroup);
		}
	}

	protected function forward($action, $esito) {
		$REDIRECT = array(
			"cloneObject"	=> 	array(
							"OK"	=> "/addressbook/view/".@$this->Card->id,
							"ERROR"	=> "/addressbook/view/".@$this->Card->id 
							),
			"save"	=> 	array(
							"OK"	=> "/addressbook/view/".@$this->Card->id,
							"ERROR"	=> "/addressbook/view/".@$this->Card->id 
							), 
			"delete" =>	array(
							"OK"	=> $this->fullBaseUrl . $this->Session->read('backFromView'),
							"ERROR"	=> "/addressbook/view/".@$this->request->params['pass'][0]
							),
			"deleteSelected" =>	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
							),
			"changeStatusObjects"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
							),			
 			"saveCategories" 	=> array(
 							"OK"	=> "/addressbook/categories",
 							"ERROR"	=> "/addressbook/categories"
 									),
 			"deleteCategories" 	=> array(
 							"OK"	=> "/addressbook/categories",
 							"ERROR"	=> "/addressbook/categories"
 									),
			"addItemsToAreaSection"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
 									),
			"moveItemsToAreaSection"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
 									),
 			"removeItemsFromAreaSection"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
							),
			"assocCategory"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
			),
			"disassocCategory"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
			),
			"addToMailgroup"	=> 	array(
							"OK"	=> $this->referer(),
							"ERROR"	=> $this->referer() 
			)
		);
		if(isset($REDIRECT[$action][$esito])) return $REDIRECT[$action][$esito] ;
		return false ;
	}

}

?>