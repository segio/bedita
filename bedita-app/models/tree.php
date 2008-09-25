<?php
/**
 *	Bedita Tree operations:
 * 		* insert, remove, move....
 * 		* re-define save and delete
 * 
 * @filesource
 * @copyright		Copyright (c) 2008
 * @link
 * @package
 * @subpackage
 * @since
 * @version
 * @modifiedby
 * @lastmodified
 * @license
 * @author 		giangi giangi@qwerg.com, ste ste@channelweb.it
 *
*/
class Tree extends BEAppModel
{

	/**
	 * save: do nothing
	 */
	function save($data = null, $validate = true, $fieldList = array()) {
		return true ;
	}

	/**
	 * definisce la radice dell'albero
	 */
	function setRoot($id) {
		$this->id = $id ;
	}

	/**
	 * ritorna l'indice  della radice dell'albero
	 */
	function getRoot() {
		return $this->id  ;
	}

	/**
	 * Crea il clone di una determinata ramificazione.
	 *
	 * @param integer $newId	Id radice ramificazione clonata
	 * @param integer $id		Id ramificazione
	 */
	function cloneTree($newId, $id = null) {
		if (isset($id)) {
			$this->id = $id ;
		}
		if(!isset($this->id)) return false ;

		$id = $this->id ;

		$ret = $this->query("CALL cloneTree({$id}, {$newId})");
		return (($ret === false)?false:true) ;
	}

	/**
	 * Torna il parent/ i parent dell'albero
	 *
	 * @param integer $id
	 *
	 * @return mixed	integer, se solo un parent
	 * 					array, se inserito in + parent
	 * 					false, in caso d'errore
	 */
	function getParent($id = null) {
		if (isset($id)) {
			$this->id = $id ;
		}
		$id = $this->id ;

		if(($ret = $this->query("SELECT parent_id FROM  trees WHERE id = {$id}")) === false) {
			return false ;
		}

		if(!count($ret)) return false ;
		else if(count($ret) == 1) return $ret[0]['trees']['parent_id'] ;
		else {
			$tmp = array() ;
			for($i=0; $i < count($ret) ; $i++) {
				$tmp[] = $ret[$i]['trees']['parent_id'] ;
			}

			return $tmp ;
		}
	}

	function getRootForSection($id) {
		$path = $this->field("path", array("id"=>$id));
		if($path === false) {
			$this->log("No path found in Tree for id ".$id);
			throw new BeditaException(__("No path found in Tree for id", true)." ".$id);
		}
		$path = substr($path,1);
		$pos = strpos($path,"/");
		if($pos == -1)
			return $path;
		return substr($path,0, $pos);
	}

	function appendChild($id, $idParent = null) {
		$idParent = (empty($idParent)) ? "NULL" :  $idParent ;

		$ret = $this->query("CALL appendChildTree({$id}, {$idParent})");
		return (($ret === false)?false:true) ;

	}

	function moveChildUp($id, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("CALL moveChildTreeUp({$id}, {$this->id})");
		return (($ret === false)?false:true) ;
	}

	function moveChildDown($id, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("CALL moveChildTreeDown({$id}, {$this->id})");
		return (($ret === false)?false:true) ;
	}

	function moveChildFirst($id, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("CALL moveChildTreeFirst({$id}, {$this->id})");
		return (($ret === false)?false:true) ;
	}

	function moveChildLast($id, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("CALL moveChildTreeLast({$id}, {$this->id})");
		return (($ret === false)?false:true) ;
	}

	function switchChild($id, $priority, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("CALL switchChildTree({$id}, {$this->id}, {$priority})");
		return (($ret === false)?false:true) ;
	}
	
	function removeChild($id, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret = $this->query("DELETE FROM trees WHERE id = {$id} AND parent_id = {$this->id}");
		return (($ret === false)?false:true) ;
	}

	function removeChildren($idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		
		// preleva i figli
		$conditions = array() ;
		$this->_getCondition_parentID($conditions, $this->id) ;
		$sqlClausole = ConnectionManager::getDataSource($this->useDbConfig)->conditions($conditions, true, true) ;
		
		$children = $this->query("SELECT id FROM view_trees {$sqlClausole}") ;
		
		// Cancella i rami di cui i figli sono radice
		for ($i =0; $i < count($children); $i++) {
			$tmp = $this->am($children[$i]);
			
			if($this->query("CALL deleteTreeWithParent({$tmp['id']}, {$this->id})") === false) return false ;
		}
				
		return true ;
	}

	function setPriority($id, $priority, $idParent = false) {
		if (!empty($idParent)) {
			$this->id = $idParent ;
		}
		$ret =  $this->query("UPDATE trees SET priority = {$priority} WHERE id = {$id} AND parent_id = {$this->id}");
		return (($ret === false)?false:true) ;
	}


	function move($idNewParent, $idOldParent, $id = NULL) {
		if (empty($id)) {
			$id = $this->id;
		}

		// Verifica che il nuovo parent non sia un discendente dell'albero da spostare
		$ret = $this->query("SELECT isParentTree({$id}, {$idNewParent}) AS parent");
		if(!empty($ret[0][0]["parent"])) return  false ;

 		$ret = $this->query("CALL moveTree({$id}, {$idOldParent}, {$idNewParent})");
		return (($ret === false)?false:true) ;
	}

	/**
	 * Cancella il ramo con la root con un determinato id.
	 */
	function del($id = null, $idParent = null) {
		if (!empty($id)) {
			$this->id = $id;
		}
		$id = $this->id;
		if(isset($idParent)) $ret = $this->query("CALL deleteTreeWithParent({$id}, {$idParent})");
		else $ret = $this->query("CALL deleteTree({$id})");
		
		return (($ret === false)?false:true) ;
	}

	/**
	 * Get Tree where 'id' is root.
	 * @param integer $id		root id.
	 * @param string $userid	user. if null: no permission check (default); if '': guest user
	 * @param string $status	only objs with this status 
	 * @param array $filter		object types id (no "search" text query!!), default: false (all)
	 */
	function getAll($id = null, $userid = null, $status = null, $filter = false) {
		$fields  = " distinct * " ;

		// Setta l'id
		if (!empty($id)) {
			$this->id = $id;
		}

		// setta le condizioni di ricerca
		$conditions = array() ;
		$this->_getCondition_filterType($conditions, $filter) ;
		$this->_getCondition_userid($conditions, $userid ) ;
		$this->_getCondition_status($conditions, $status) ;
		$this->_getCondition_parentPath($conditions, $id) ;

		// Esegue la ricerca
		$db 		 =& ConnectionManager::getDataSource($this->useDbConfig);
		$sqlClausole = $db->conditions($conditions, true, true) ;

		$from = " view_trees AS Tree ";
		$records  = $this->query("SELECT {$fields} FROM {$from} {$sqlClausole}") ;

		// Costruisce l'albero
		$roots 	= array() ;
		$tree 	= array() ;
		$size	= count($records) ;

		for ($i=0; $i < $size ; $i++) {
			if(isset($records[$i]['Tree'])){
				$root = am($records[$i]['Tree'], (isset($records[$i][0])?$records[$i][0] :array())) ;
			} else {

				if(!isset($records[$i]['trees'])) $records[$i]['trees'] = "";
				if(!isset($records[$i]['objects'])) $records[$i]['objects'] = "";
				$root = am($records[$i]['trees'], $records[$i]['objects'], (isset($records[$i][0])?$records[$i][0] :array())) ;
			}

			$root['children']	= array() ;
			$roots[$root['id']] = &$root ;

			if(isset($root['parent_id']) && isset($roots[$root['parent_id']])) {
				$roots[$root['parent_id']]['children'][] = &$root ;
			} else {
				$tree[] = &$root ;
			}

			unset($root);
		}

		// scarta tutti i rami che non sono root e che non coincidono con $id
		// sono rami su cui l'utente non ha permessi sui parent
		$tmp = array() ;
		for ($i=0; $i < count($tree) ; $i++) {
			if(isset($id) && $tree[$i]['id'] == $id) {
				$tmp[] = &$tree[$i] ;
				continue ;
			}

			if(!isset($id) && empty($tree[$i]['parent_id'])) {
				$tmp[] = &$tree[$i] ;

				continue ;
			}
		}

		return $tmp ;
	}

	/**
	 * Riorganizza le posizioni dell'intero albero passato.
	 *
	 * @param array $tree	Item con l'albero.
	 * 						{0..N}:
	 * 							id			Id dell'elemento
	 * 							parent		Nuovo parent
	 * 							children	Elenco dei discendenti
	 */
	function moveAll(&$tree) {
		// Cerca tutti parent_id dei rami passati e il priority
		$IDs = array() ;
		$this->_getIDs($tree, $IDs) ;
		$this->_setPriority($tree) ;

		if(!count($IDs)) return true ;
		$IDs = implode(",", $IDs) ;
		$ret = $this->query("SELECT id, parent_id, priority FROM trees WHERE id IN ({$IDs})");

		$IDOldParents 	 = array() ;
		$IDOldPriorities = array() ;

		for($i=0; $i < count($ret) ; $i++) {
			$IDOldParents[$ret[$i]["trees"]["id"]] 		= $ret[$i]["trees"]["parent_id"] ;
			$IDOldPriorities[$ret[$i]["trees"]["id"]] 	= $ret[$i]["trees"]["priority"] ;
		}
		unset($IDs) ;
		unset($ret) ;

		// Salva le ramificazioni spostate
		$ret = $this->_moveAll($tree, $IDOldParents, $IDOldPriorities) ;

		return $ret ;
	}

	/**
	 * Preleva i path dell'oggetto passato dove al posto degli id
 	 * ci sono i nickname
 	 *
 	 * @param integer  $id
	 * @return array/string
	 */
	function getPathNickname($id) {
		if (isset($id)) {
			$this->id = $id ;
		}
		$id = $this->id ;

		if(($ret = $this->query("SELECT path FROM  trees WHERE id = {$id}")) === false) {
			return false ;
		}

		$IDs = array() ;
		$paths = array() ;
		
		// Preleva gli ID 
		if(!count($ret)) return false ;
		else if(count($ret) == 1) {
			$paths[] = $ret[0]['trees']['path'] ;
			$tmp = explode("/", $ret[0]['trees']['path']) ;
			foreach ($tmp as $id) {
				if(@empty($id)) continue ;
				$IDs[$id] = null ;
			}
		}
		else {
			$tmp = array() ;
			for($i=0; $i < count($ret) ; $i++) {
				$paths[] = $ret[$i]['trees']['path'] ;
				$tmp = explode("/", $ret[$i]['trees']['path']) ;
				foreach ($tmp as $id) {
					if(@empty($id)) continue ;
					$IDs[$id] = null ;
				}
			}
		}
		
		// Preleva i nickname
		$tmp = array() ;
		foreach ($IDs as $id => $value) {
			$tmp[] = $id ;
		}
		$tmp = implode(",", $tmp);
		if(($ret = $this->query("SELECT id, nickname FROM  objects WHERE id IN({$tmp}) ")) === false) {
			return false ;
		}
		for($i=0; $i < count($ret) ; $i++) {
			$IDs[$ret[$i]['objects']['id']] = $ret[$i]['objects']['nickname'] ;
		}
		
		// Trasforma i path
		for($i=0; $i < count($paths) ; $i++) {
			$tmp = explode("/", $paths[$i]);
		
			for($x=0; $x < count($tmp) ; $x++) {
				if(!isset($IDs[$tmp[$x]])) continue ;
				$tmp[$x] = $IDs[$tmp[$x]] ;
			}
			$paths[$i] = implode("/", $tmp) ;
		}
		
		return $paths ;
	}	

	function isParent($idParent, $id = NULL) {
		if (empty($id)) {
			$id = $this->id;
		}

		// Verifica che il nuovo parent non sia un discendente dell'albero da spostare
		$ret = $this->query("SELECT isParentTree({$idParent}, {$id}) AS parent");
		
		if(empty($ret[0][0]["parent"])) return  false ;

		return (($ret[0][0]["parent"])?true:false) ;
	}
	
	function isParentByNickname($nickname, $id = NULL) {
		if (empty($id)) {
			$id = $this->id;
		}

		// Verifica che il nuovo parent non sia un discendente dell'albero da spostare
		$ret = $this->query("SELECT id FROM objects where nickname = '{$nickname}' ");
		if(!isset($ret[0]['objects']["id"])) return  false ;

		return $this->isParent($ret[0]['objects']["id"] , $id) ;
	}
	
	
	private function _moveAll(&$tree, &$IDOldParents, &$IDOldPriorities) {

		for($i=0; $i < count($tree) ; $i++) {
			if($tree[$i]["parent"] != $IDOldParents[$tree[$i]["id"]]) {
				if(!$this->move($tree[$i]["parent"], $IDOldParents[$tree[$i]["id"]], $tree[$i]["id"])) return false ;
			}
			if($tree[$i]["priority"] != $IDOldParents[$tree[$i]["id"]] && isset($tree[$i]["parent"])) {
				if(!$this->switchChild($tree[$i]["id"], $tree[$i]["priority"], $tree[$i]["parent"])) return false ;
//				if(!$this->setPriority($tree[$i]["id"], $tree[$i]["priority"], $tree[$i]["parent"])) return false ;
			}

			if(!$this->_moveAll($tree[$i]["children"], $IDOldParents, $IDOldPriorities)) return false ;
		}

		return true ;
	}

	private function _getIDs(&$tree, &$IDs) {
		for($i=0; $i < count($tree) ; $i++) {
			$IDs[] = $tree[$i]["id"] ;

			$this->_getIDs($tree[$i]["children"], $IDs) ;
		}
	}

	private function _setPriority(&$tree) {
		for($i=0; $i < count($tree) ; $i++) {
			$tree[$i]["priority"] = $i+1 ;

			$this->_setPriority($tree[$i]["children"]) ;
		}
	}

	/**
	 * Children of id element (only 1 level in tree).
	 * If userid != null, only objects with read permissione for user, if ' ' - use guest/anonymous user,
	 * if userid = null -> no permission check.
	 * Filter: object types, search text query.
	 *
	 * @param integer $id		root id
	 * @param string $userid	user: null (default) => no permission check. ' ' => guest/anonymous user,
	 * @param string $status	object status
	 * @param array  $filter	Filter: object types, search text query, eg. array(21, 22, "search" => "text to search").
	 * 							Default: all object types
	 * @param string $order		field to order result (id, status, modified..)
	 * @param boolean $dir		true (default), ascending, otherwiese descending.
	 * @param integer $page		Page number (for pagination)
	 * @param integer $dim		Page dim (for pagination)
	 */
	function getChildren($id = null, $userid = null, $status = null, $filter = false, $order = null, $dir  = true, $page = 1, $dim = 100000) {
		return $this->_getChildren($id, $userid, $status, $filter, $order, $dir, $page, $dim, false) ;
	}

	/**
	 * Discendents of id element (all elements in tree).
	 * (see: beobject->find(), to search not using content tree ).
	 * If userid present, only objects with read permissione, if ' ' - guest/anonymous user,
	 * if userid = null -> no permission check.
	 * Filter: object types, search text query.
	 *
	 * @param integer $id		root id
	 * @param string $userid	user: null (default) => no permission check. ' ' => guest/anonymous user,
	 * @param string $status	object status
	 * @param array  $filter	Filter: object types, search text query, eg. array(21, 22, "search" => "text to search").
	 * 							Default: all object types
	 * @param string $order		field to order result (id, status, modified..)
	 * @param boolean $dir		true (default), ascending, otherwiese descending.
	 * @param integer $page		Page number (for pagination)
	 * @param integer $dim		Page dim (for pagination)
	 */
	function getDiscendents($id = null, $userid = null, $status = null, $filter = false, $order = null, $dir  = true, $page = 1, $dim = 100000) {
		return $this->_getChildren($id, $userid, $status, $filter, $order, $dir, $page, $dim, true) ;
	}

	function findCount($sqlConditions = null, $recursive = null) {
		$from = " trees AS Tree INNER JOIN objects as BEObject ON Tree.id = BEObject.id " ;
		$query = "SELECT COUNT(DISTINCT `Tree`.`id`) AS count FROM {$from}";
		if(is_array($sqlConditions)) {
			$where = " WHERE ";
			$first = true;
			foreach ($sqlConditions as $k => $v) {
				if(!$first) {
					$where .= " AND ";
				}
				$where .= " $k = $v";
				$first = false;
			}
			$query .= $where;
			
		} else if(!empty($sqlConditions)) {
			$query .= $sqlConditions;
		}

		list($data)  = $this->query($query);

		if (isset($data[0]['count'])) {
			return $data[0]['count'];
		} elseif (isset($data[$this->name]['count'])) {
			return $data[$this->name]['count'];
		}

		return false;
	}

	/**
	 * Torna l'ID delll'oggetto con un determinato nickname che ha come parent.
	 * Se ci sono + figli con lo stesso nick, torna il primo
	 * parent_id
	 *
	 * @param string $nickname
	 * @param integer $parent_id		
	 */
	function getIdFromNickname($nickname, $parent_id = null) {
		if(isset($parent_id)) {
			$sql = "SELECT trees.id FROM
					trees INNER JOIN objects ON trees.id = objects.id AND parent_id = {$parent_id}
					WHERE
					nickname = '{$nickname}' LIMIT 1
			" ;
		} else {
			$sql = "SELECT trees.* FROM
					trees INNER JOIN objects ON trees.id = objects.id AND parent_id IS NULL 
					WHERE
					nickname = '{$nickname}' LIMIT 1
			" ;
		}
		
		$tmp  	= $this->query($sql) ;
		return ((isset($tmp[0]['trees']['id'])) ? $tmp[0]['trees']['id'] : null) ;
	}
	
	////////////////////////////////////////////////////////////////////////
	/**
	 * children/discendents of id.
	 * See: getChildren/getDiscendents
	 * 
	 * @param integer $id		root id
	 * @param string $userid	user: null (default) => no permission check. ' ' => guest/anonymous user,
	 * @param string $status	object status
	 * @param array  $filter	Filter: object types, search text query, eg. array(21, 22, "search" => "text to search").
	 * 							Default: all object types
	 * @param string $order		field to order result (id, status, modified..)
	 * @param boolean $dir		true (default), ascending, otherwiese descending.
	 * @param integer $page		Page number (for pagination)
	 * @param integer $dim		Page dim (for pagination)
	 * @param boolean $all		true: all tree levels (discendents), false: only first level (children)
	 */
	private function _getChildren($id, $userid, $status, $filter, $order, $dir, $page, $dim, $all) {
		
		// Setta l'id
		if (!empty($id)) {
			$this->id = $id;
		}

		$fields  = " distinct `Tree`.*, `BEObject`.* " ;
		
		// setta le condizioni di ricerca
		$conditions = array() ;
		$this->_getCondition_filterType($conditions, $filter) ;
		$this->_getCondition_userid($conditions, $userid ) ;
		$this->_getCondition_status($conditions, $status) ;
		$this->_getCondition_current($conditions, true) ;

		if($all) $this->_getCondition_parentPath($conditions, $id) ;
		else $this->_getCondition_parentID($conditions, $id) ;

		// Costruisce i criteri di ricerca
		$db 		 =& ConnectionManager::getDataSource($this->useDbConfig);
		$sqlClausole = $db->conditions($conditions, true, true) ;
		
		$fromSearchText = "";
		$ordClausole  = "" ;
		$groupClausole  = "" ;
		$searchFields = "" ;
		$searchText = false;
		$searchClausole = ""; 
		// text search conditions?
		if(is_array($filter) && isset($filter['search'])) {
			$s = $filter['search'];
			$searchFields = ", SUM( MATCH (`SearchText`.`content`) AGAINST ('$s') * `SearchText`.`relevance` ) AS `points` ";
			$searchText = true;
			$fromSearchText = " INNER JOIN search_texts as `SearchText` ON `SearchText`.`object_id` = `BEObject`.`id` ";
			$searchClausole = " AND MATCH (`SearchText`.`content`) AGAINST ('$s')";
			$groupClausole  = "  GROUP BY `SearchText`.`object_id`";
			$ordClausole = " ORDER BY points DESC ";
		}
		
		if(is_string($order) && strlen($order)) {
			if($this->hasField($order)) {
				$order = " `Tree`.`{$order}`";
			} else {
				$order = " `BEObject`.`{$order}`";
			}
			$ordItem = "{$order} " . ((!$dir)? " DESC " : "");
			if($searchText) {
				$ordClausole .= ", ".$ordItem;
			} else {
				$ordClausole = " ORDER BY {$order} " . ((!$dir)? " DESC " : "") ;
			}
		}
		
		// costruisce il join dalle tabelle
		$from = "trees AS `Tree` INNER JOIN objects as `BEObject` ON `Tree`.`id` = `BEObject`.`id`" ;
		
		$limit 	= $this->getLimitClausole($page, $dim) ;
		$query = "SELECT {$fields} {$searchFields} FROM {$from} {$fromSearchText} {$sqlClausole} {$searchClausole} {$groupClausole} {$ordClausole} LIMIT {$limit}";
		$tmp  	= $this->query($query) ;

		// Torna il risultato
		$recordset = array(
			"items"		=> array(),
			"toolbar"	=> $this->toolbar($page, $dim, $fromSearchText.$sqlClausole.$searchClausole) );
		for ($i =0; $i < count($tmp); $i++) {
			$recordset['items'][] = $this->am($tmp[$i]);
		}

		return $recordset ;
	}

	private function _getCondition_filterType(&$conditions, $filter = false) {
		if(!$filter) 
			return;
		// exclude search query from object_type_id list
		if(is_array($filter)) {
			$types = array();
			foreach ($filter as $k => $v) {
				if($k !== "search" && $k !== "lang")
					$types[] = $v;
				elseif ($k === "lang")
					$conditions["`BEObject`.lang"] = $v;
			}
			$conditions['object_type_id'] = $types;
		} else {
			$conditions['object_type_id'] = $filter;
		}	
	}

	private function _getCondition_userid(&$conditions, $userid = null) {
		if(!isset($userid)) return ;

		$conditions[] 	= " prmsUserByID ('{$userid}', Tree.id, ".BEDITA_PERMS_READ.") > 0 " ;
	}

	private function _getCondition_status(&$conditions, $status = null) {
		if(!isset($status)) 
			return ;
		$conditions[] = array('status' => $status) ;
	}

	private function _getCondition_parentID(&$conditions, $id = null) {
		if(isset($this->id)) $conditions[] = array("parent_id" => $this->id) ;
		else $conditions[] = array("parent_id" => null);
	}

	private function _getCondition_parentPath(&$conditions, $id = null) {
		if(isset($id)) {
			$conditions[] = " path LIKE (CONCAT((SELECT path FROM trees WHERE id = {$id}), '/%')) " ;
		}
	}

	private function _getCondition_current(&$conditions, $current = true) {
		if(!$current) return ;
		$conditions[] = array("current" => 1);
	}

}


?>