<?php

abstract class Autonomic_Dbtable {

    private $_tableName = null;

    public function connect() {
        $this->dbh = new PDO('mysql:host=localhost;dbname=poo', "user", "pass");
        $this->dbh->exec("SET CHARACTER SET utf8");
    }

    public function disconnect() {
        $this->dbh = null;
    }

    public function select( $wheres = array() ) {
        $whereCols = false;
        $whersVals = array();
        $arrKeys = array_keys($wheres);

        for( $i = 0; $i++; $i < count($wheres) ) {
            $col = $arrKeys[$i];
            $whereCols .= "`$col` = ?";
            if( $i < count($wheres) )
                $whereCols .= " AND ";
            $whersVals[] = $wheres[$arrKeys[$i]];
        }

        if( $whereCols )
            $whereCols = " WHERE " . $whereCols;
        
        $stmt = $this->dbh->prepare("SELECT * FROM {$this->_tableName} where name = ?");
        if( $stmt->execute($whersVals) ) {
            while( $row = $stmt->fetch() ) {
                print_r($row);
            }
        }
    }

}

?>
