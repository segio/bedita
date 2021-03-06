<?php
/* -----8<--------------------------------------------------------------------
 * 
 * BEdita - a semantic content management framework
 * 
 * Copyright 2008-2011 ChannelWeb Srl, Chialab Srl
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
 * ------------------------------------------------------------------->8-----
 */

/**
 * UsersController: administrate users and groups
 *
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
class UsersController extends ModulesController {
	
	public $uses = array('User', 'Group');
	
	public $helpers = array('Paginator');
	 
	public $paginate = array(
	 	'User' => array('limit' => 20, 'page' => 1, 'order'=>array('created'=>'desc')),
		'Group' => array('limit' => 20, 'page' => 1, 'order'=>array('created'=>'desc') )//, 'contain' => array())
	 );
	
	protected $moduleName = 'users';
	
	function index() {
		$conditions = array();
		if (!empty($this->params["form"]["searchstring"]) && strlen($this->params["form"]["searchstring"]) > 3) {
			$conditions = array(
				"OR" => array(
					"User.userid LIKE" => $this->params["form"]["searchstring"] . "%",
					"User.realname LIKE" => "%" . $this->params["form"]["searchstring"] . "%",
					"User.email LIKE" => "%" . $this->params["form"]["searchstring"] . "%"
				)
			);
			$this->set("stringSearched", $this->params["form"]["searchstring"]);
		}

		$users = $this->paginate('User', $conditions);

		$beObject = ClassRegistry::init("BEObject");
		foreach ($users as &$user) {
			$res = $beObject->find('list', array(
				"conditions" => "user_created=" . $user["User"]['id']
			));

			if (!empty($res)) {
				$user['User']['related_obj'] = 1;
			}else {
				$user['User']['related_obj'] = 0;
			}
		}

		$this->set('users', $users);
	}
	
	function showUsers() {
		$allGroups = $this->Group->find("all", array("contain" => array()));
		$authGroups = array();
		$userGroups = array();
		if(isset($user)) {
			foreach ($user['Group'] as $g) {
				array_push($userGroups, $g['name']);
			}
		}
		$formGroups = array();
		foreach ($allGroups as $g) {
			$isGroup=false;
			if(array_search($g['Group']['name'],$userGroups) !== false) {
				$isGroup = true;
			}
			$formGroups[$g['Group']['name']] = $isGroup;
			if($g['Group']['backend_auth'] == 1) {
				$authGroups[] = $g['Group']['name'];
			}
		}
		$this->set('users', $this->User->find("all"));
		$this->set('formGroups',  $formGroups);
		$this->set('authGroups',  $authGroups);
		$this->layout = null;
	}

	function saveUser() {

		$this->checkWriteModulePermission();

		$userGroups=array();
		if(isset($this->data['groups'] )) {
			foreach ($this->data['groups'] as $k=>$v) {
				array_push($userGroups, $k);
			}
		}

		// Format custom properties
		$this->BeCustomProperty->setupUserPropertyForSave() ;

		if(!isset($this->data['User']['id'])) {
			if (!$this->BeAuth->checkConfirmPassword($this->params['form']['pwd'], $this->data['User']['passwd'])) {
				throw new BeditaException(__("Passwords mismatch",true));
			}
			$this->data['User']['passwd'] = trim($this->data['User']['passwd']);
			$this->BeAuth->createUser($this->data, $userGroups);
			$this->eventInfo("user ".$this->data['User']['userid']." created");
			$this->userInfoMessage(__("User created",true));
		} else {
			$pass = trim($this->data['User']['passwd']);
			$confirmPass = trim($this->params['form']['pwd']);
			if(empty($pass) && empty($confirmPass)) {
				unset($this->data['User']['passwd']);
			} elseif (!$this->BeAuth->checkConfirmPassword($this->params['form']['pwd'], $this->data['User']['passwd'])) {
				throw new BeditaException(__("Passwords mismatch",true));
			}
			$this->BeAuth->updateUser($this->data, $userGroups);
			$this->eventInfo("user ".$this->data['User']['userid']." updated");
			$this->userInfoMessage(__("User updated",true));
		}
	}

	function saveUserAjax () {
		$this->layout = null;
		$this->checkWriteModulePermission();
		try {
			$this->Transaction->begin() ;
			if(empty($this->data)) {
				throw new BeditaException(__("Empty data",true));
			}
			$userGroups=array();
			if(isset($this->data['groups'] )) {
				foreach ($this->data['groups'] as $k=>$v) {
					array_push($userGroups, $k);
				}
			}
			$this->data['User']['passwd'] = substr($this->data['User']['userid'],0,4) . "+pwd";
			$this->BeAuth->createUser($this->data, $userGroups);
			$u = $this->User->findByUserid($this->data['User']['userid']);
			$this->eventInfo("user ".$this->data['User']['userid']." created");
			$this->Transaction->commit();
			$this->set("userId", $u['User']['id']);
			$this->set("userCreated", true);
		} catch (BeditaException $ex) {
			$errTrace = get_class($ex) . " - " . $ex->getMessage()."\nFile: ".$ex->getFile()." - line: ".$ex->getLine()."\nTrace:\n".$ex->getTraceAsString();   
			$this->handleError($ex->getMessage(), $ex->getMessage(), $errTrace);
			$this->setResult(self::ERROR);
			$this->set("errorMsg", $ex->getMessage());
		}
	}
	
	function removeUser($id) {
		$this->checkWriteModulePermission();
		if(isset($id)) {
			$u = $this->isUserEditable($id);
			if ($u === false) {
				throw new BeditaException(__("You are not allowed to remove this user", true));
			}
			if(empty($u)) {
				throw new BeditaException(__("Bad data",true));
			}
			$userid = $u['User']['userid'];
			if($userid === $this->BeAuth->user["userid"]) {
				throw new BeditaException(__("Auto-remove forbidden",true));
			}
			$this->BeAuth->removeUser($userid);
			$this->eventInfo("user ".$userid." deleted");
		}
	}

	function blockUser($id) {
		$this->checkWriteModulePermission();
		if (isset($id)) {
			if ($id === $this->BeAuth->user["userid"]) {
				throw new BeditaException(__("Auto-block forbidden",true));
			}

			$u = $this->User->findById($id);
			if (empty($u)) {
				throw new BeditaException(__("Bad data",true));
			}

			$data = array(
				"id" => $id,
				"valid" => 0,
				"userid" => "deleted-user-$id",
				"realname" => null,
				"email" => null
			);

			if (!$this->User->save($data)) {
				throw new BeditaException(__("Error blocking user", true));
			}
		}
	}

	/**
	 * return user data if user in session can edit him
	 * 
	 * @param int $id
	 * @return mixed 
	 */
	protected function isUserEditable($id) {
		$userToEdit = $this->User->findById($id);
		if (!empty($userToEdit)) {
			$sessionUser = $this->BeAuth->getUserSession();
			$userGroups = Set::classicExtract($userToEdit, 'Group.{n}.name');
			if (!in_array("administrator", $sessionUser["groups"]) && in_array("administrator", $userGroups)) {
				return false;
			}
		}
		return $userToEdit;
	}


	function viewUser($id=NULL) {

		if(isset($id)) {
			$userdetail = $this->isUserEditable($id);
			if ($userdetail === false) {
				throw new BeditaException(__("You are not allowed to edit this user", true));
			}
			if(empty($userdetail)) {
				throw new BeditaException(__("Bad data",true));
			}
			$userdetailModules = ClassRegistry::init("PermissionModule")->getListModules($userdetail['User']['userid']);

		} else {
			
			$this->set('genpassword', substr( str_shuffle( 'abcdefghjkmnpqrstuvwxyz123456789' ) , 0 , 6 ));	
			$userdetail = NULL;
			$userdetailModules = NULL;
		}

		$userGroups = array();
		if(isset($userdetail)) {
			foreach ($userdetail['Group'] as $g) {
				array_push($userGroups, $g['name']);
			}

			if ($userdetail['User']['userid'] == "deleted-user-" . $userdetail['User']['id']) {
				$this->set("userDeleted", true);
			}
		}
		
		$sessionUser = $this->BeAuth->getUserSession();
		$conditions = array();
		if (!in_array("administrator", $sessionUser["groups"])) {
			$conditions = array(
				"NOT" => array("name" => "administrator")
			);
		}
		$allGroups = $this->Group->find("all", array(
			"contain" => array(),
			"conditions" => $conditions
		));
		
		$formGroups = array();
		$authGroups = array();
		foreach ($allGroups as $g) {
			$isGroup=false;
			if(array_search($g['Group']['name'],$userGroups) !== false) {
				$isGroup = true;
			}
			$formGroups[$g['Group']['name']] = $isGroup;
			if($g['Group']['backend_auth'] == 1) {
				$authGroups[] = $g['Group']['name'];
			}
		}

		$this->set('userdetail',  $userdetail['User']);
		if (is_array($userdetail["ObjectUser"])) {
			$this->set('objectUser', $this->objectRelationArray($userdetail["ObjectUser"]));
		}

		$this->set('formGroups',  $formGroups);
		$this->set('authGroups',  $authGroups);
		$this->set('userdetailModules', $userdetailModules);

		$property = $this->BeCustomProperty->setupUserPropertyForView($userdetail);
		$this->set('userProperty',  $property);

		BeLib::getObject("BeConfigure")->setExtAuthTypes();
	}

	function groups() {
		if (!empty($this->params["form"]["searchstring"]) && strlen($this->params["form"]["searchstring"]) > 3) {
			$this->paginate["Group"]["conditions"] = array("Group.name LIKE" => $this->params["form"]["searchstring"] . "%");
			$this->set("stringSearched", $this->params["form"]["searchstring"]);
		}
		$this->Group->recursive = -1;
		$groups = $this->paginate('Group');
		foreach ($groups as &$g) {
			$g['Group']['num_of_users'] = $this->Group->countUsersInGroup($g["Group"]["id"]);
		}
		$this->set('groups', $groups);
		$this->set('group',  NULL);
		$this->set('modules', $this->allModulesWithFlag());
	}
	 
	function viewGroup($id = null) {
		if(!empty($id)) {
			$g = $this->Group->findById($id);
			if (empty($g)) {
				throw new BeditaException(__("No group found with id", true) . " " . $id);
			}
			$this->set('group', $g);
		}
		$modules = $this->allModulesWithFlag();
		$permsMod = ClassRegistry::init("PermissionModule")->getPermissionModulesForGroup($id);
		foreach ($permsMod as $p) {
			$modId = $p['PermissionModule']['module_id'];
			foreach ($modules as &$mod) {
				if($mod['Module']['id'] === $modId) {
					$mod['Module']['flag'] = $p['PermissionModule']['flag'];
				}
			}
		}
		$this->set('modules', $modules);
	}
	  
	function saveGroup() {
		$this->checkWriteModulePermission();

		$this->Transaction->begin();
		$newGroup = false;
		$groupId = $this->BeAuth->saveGroup($this->data);
		if(!isset($this->data['Group']['id'])) {
			$this->eventInfo("group ".$this->data['Group']['name']." created");
			$newGroup = true;
		} else {
			$this->eventInfo("group ".$this->data['Group']['name']." update");
		}
		if(isset($this->data['ModuleFlags'])) {
			$permissionModule = ClassRegistry::init("PermissionModule");
			// if user doesn't belong to administrator group then it adds admin flag to update module persmission with the same value
			$user = $this->BeAuth->getUserSession();
			if (!in_array("administrator", $user["groups"])) {
				$adminModuleId = ClassRegistry::init("Module")->field("id", array("name" => "admin"));
				$groupId = $this->Group->field("id", array("name" => "administrator"));
				$this->data['ModuleFlags']["admin"] =  $permissionModule->field("flag", array(
					"module_id" => $adminModuleId,
					"ugid" => $groupId,
					"switch" => $permissionModule->SWITCH_GROUP
				));
			}
			$permissionModule->updateGroupPermission($groupId, $this->data['ModuleFlags']);
		}

		$this->userInfoMessage(__("Group ".($newGroup? "created":"updated"),true));
		$this->Transaction->commit();
	}
	  
	function removeGroup($id) {
		$this->checkWriteModulePermission();
		$groupName = $this->Group->field("name", array("id" => $id));
		$this->BeAuth->removeGroup($groupName);
		$this->eventInfo("group ".$groupName." deleted");
		$this->userInfoMessage(__("Group deleted",true));
	}
	
	private function allModulesWithFlag() {
		$user = $this->BeAuth->getUserSession();
		$conditions = array();
		// if user doesn't belong to administrator group then exclude admin module
		if (!in_array("administrator", $user["groups"])) {
			$conditions = array(
				"NOT" => array("name" => array("admin"))
			);
		}
		$modules = ClassRegistry::init('Module')->find("all", array(
			"conditions" => $conditions
		));
		foreach ($modules as &$mod) {
			$mod['Module']['flag'] = 0;
		}
		return $modules;
	}
	  
	  
	protected function forward($action, $esito) {
		$REDIRECT = array(
			"viewUser" => 	array(
				"ERROR"	=> $this->referer()
			),
			"viewGroup" => 	array(
				"ERROR"	=> '/users/groups'
			),
			"saveUser" => 	array(
				"OK"	=> "/users/viewUser/" . @$this->User->id,
				"ERROR"	=> $this->referer() 
			),
			"removeUser" => 	array(
				"OK"	=> "/users",
				"ERROR"	=> "/users" 
			),
			"blockUser" => 	array(
				"OK"	=> "/users",
				"ERROR"	=> "/users" 
			),
			"saveGroup" => 	array(
				"OK"	=> "/users/viewGroup/" . @$this->Group->id,
				"ERROR"	=> $this->referer() 
			),
			"removeGroup" => 	array(
				"OK"	=> "/users/groups",
				"ERROR"	=> "/users/groups" 
			),
			"saveUserAjax" =>	array(
				"OK"	=> self::VIEW_FWD.'save_user_ajax_response',
				"ERROR"	=> self::VIEW_FWD.'save_user_ajax_response'
			)
		);
		if(isset($REDIRECT[$action][$esito])) {
			return $REDIRECT[$action][$esito];
		}
		return false;
	}
	
}

?>