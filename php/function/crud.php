<?php

global $uses;
$uses=array('dbf');

function create($tableName, $data){
	$query='INSERT INTO `%s` (%s) VALUES (%s);';
	$fields='';
	$values='';
	foreach ($data as $field => $value) {
		$fields.= ($fields===''?'`':', `'). $field. '`';
		if(is_null($value)){
			$values.= ($values===''?'':', '). 'NULL';
		}else{
			$values.= ($values===''?'\'':', \''). mysql_real_escape_string($value). '\'';
		}
	}
	$sql=sprintf($query, $tableName, $fields, $values);

	if(dbQuery($sql)===false){
		return false;
	}

	return mysql_insert_id();

}

function read($tableName, $id){
	$id=(int) $id;
	$result=listRecords($tableName, sprintf('`id`=\'%s\'', $id));
	if($result===false || count($result)!==1){
		return false;
	}
	return $result[0];
}

function update($tableName, $data, $condition){

}

function delete($tableName, $id){

}

function listRecords($tableName, $condition=null, $order=null, $page=null, $forupdate=false){
	$query='SELECT * FROM `%s`';
	if(!is_null($condition)){
		$query.=' WHERE %s';
	}

	if(!is_null($order)){
		//@TODO add order by clause
	}

	if(!is_null($page)){
		//@TODO add limit by clause
	}

	if($forupdate){
		$query.=' FOR UPDATE';
	}

	$query.=';';


	$sql=sprintf($query, $tableName, $condition);
        //die($sql);
        
	$resultSet=dbQuery($sql);
	if($resultSet===false){
		return false;
	}

	$records=array();
	while(($row=mysql_fetch_assoc($resultSet))!==false){
		$records[]=$row;
	}
	mysql_free_result($resultSet);

	return $records;
}