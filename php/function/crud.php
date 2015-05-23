<?php

global $uses;
$uses=array('dbf');

define('CRUD_MODIFY',0);
define('CRUD_FORMULA',1);

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

function massUpdate($tableName, $fields, $updateData){
    $sql=sprintf('UPDATE `%s` SET', $tableName);
    $ids=array_keys($updateData);
    $addComma=false;
    foreach($fields as $field){
        if($addComma){
            $sql.=',';
        }else{
            $addComma=true;
        }
        $sql.= sprintf(' `%s` = CASE ',$field);
        foreach($updateData as $id => $data){
            $tempValue=$data[$field];
            if(is_array($tempValue)){
                if(isset($tempValue[CRUD_FORMULA])){
                    $value=$tempValue[CRUD_FORMULA];
                }elseif(isset($tempValue[CRUD_MODIFY])){
                    $absValue=abs($tempValue[CRUD_MODIFY]);
                    $sign=$tempValue[CRUD_MODIFY]<0?'-':'+';
                    $value=sprintf('(`%s` %s %s)',$field, $sign, $absValue);
                }else{
                    die('unknown update');
                }
            }else{
                $value=sprintf("'%s'",mysql_real_escape_string($tempValue));
            }
            $sql.=sprintf("WHEN `id` = '%s' THEN %s ", $id, $value);
        }
        $sql.=' END';
    }
    $sql.=' WHERE `id` IN (%s);';
    $sql=sprintf($sql, implode(', ', $ids));
    return dbQuery($sql);
}

function massCreate($tableName, $fields, $createData){
    $sql=sprintf('INSERT INTO `%s`', $tableName);
    $sql.=sprintf(' (`%s`)', implode('`,`',$fields));
    $records=array();
    foreach($createData as $data){
        $addComma=false;
        $temp='';
        foreach($fields as $field){
            if($addComma){
                $temp.=', ';
            }else{
                $addComma=true;
            }
            if(is_null($data[$field])){
                $temp.='NULL';
            }else {
                $temp .= sprintf("'%s'", mysql_real_escape_string($data[$field]));
            }
        }
        $records[]=sprintf('(%s)',$temp);
    }
    $sql.=sprintf('VALUES %s;', implode(',',$records));
    return dbQuery($sql);
}