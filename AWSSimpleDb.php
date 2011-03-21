<?php

abstract class Autonomic_AWSSimpleDb {
    
    private $tableName = null;
    private $sdb = null;
    
    function __construct() {
        $this->sdb = new AmazonSDB();
    }
    
    function escape_string($inp) { 
        if(is_array($inp)) 
            return array_map(__METHOD__, $inp); 
    
        if(!empty($inp) && is_string($inp)) { 
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
        } 
    
        return $inp; 
    } 
    
    function select($tableName, $cols = array('*'), $wheres = false) {
        $cols = implode($cols, ", ");
        $tableName = escape_string($tableName);
        if ( $wheres ) 
        {
            foreach ( $wheres as $col=>$val)
            {
                $where[] = escape_string($col).' = "'.escape_string($val).'"';
            }
            $where = implode($where, " AND ");
            $selector = 'SELECT '.$cols.' FROM `'.$tableName.'` WHERE '.$where;
        }
        else
            $selector = 'SELECT '.$cols.' FROM `'.$tableName.'`';
            echo $selector;
    }
    
    select("beep", array("foo","bar"));
    echo "\r\n";
    select("beep", array("foo","bar"), array("id" => 6));
    echo "\r\n";
    select("beep", array("foo","bar"), array("id" => '23 OR 1=1'));
}