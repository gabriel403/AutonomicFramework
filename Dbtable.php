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

    public function setTable($tableName) {
        $this->_tableName = $tableName;
    }

    /**
     * wheres in the form 
     * array(
     *   array('columnName', 'conditionalType', 'condition', 'connectionType'),
     *   array('columnName', 'conditionalType', 'condition'), //assumes and
     *   array('columnName', 'condition') //assumes =, and
     * )
     * conditionalType is one EQUALS, NOTEQUALS, GREATERTHAN, LESSTHAN
     * connectionType is AND or OR
     */
    public function select( $wheres = array(), $cols = "*" ) {
        $connectionType = array('AND'=>1,'OR'=>1);
        $conditionalTypes = array('EQUALS'=>'=','NOTEQUALS'=>'!=','GREATERTHAN'=>'>','LESSTHAN'=>'<');
        if ( !isset($this->_tableName) )
            throw new Exception("No table name set");
        if (!is_array($wheres))
            throw new Exception("incorrect syntax for where conditions.");
            
        $whereCols = array();
        $whereVals = array();

        if ( count($wheres) > 0 ) {
            foreach ($wheres as $where) {
                switch (count($where)) {
                    case 2:
                        $whereCols[] = "{$where[0]} = :{$where[0]}";
                        $whereVals[] = "{$where[1]}";
                        break;
                    case 3:
                        if (!array_key_exists($where[1], $conditionalTypes))
                            throw new Exception("incorrect syntax for where conditions.");
                        $whereCols[] = "{$where[0]} {$conditionalTypes[$where[1]]} :{$where[0]}";
                        $whereVals[] = "{$where[2]}";
                        break;
                    case 4:
                        if (!array_key_exists($where[1], $conditionalTypes) || !array_key_exists($where[3], $connectionType))
                            throw new Exception("incorrect syntax for where conditions.");
                        $whereCols[] = "{$where[3]} {$where[0]} {$conditionalTypes[$where[1]]} :{$where[0]} ";
                        $whereVals[] = "{$where[2]}";
                        break;
                    default:
                        throw new Exception("incorrect syntax for where conditions.");
                }
            }
        }       
$sql = "SELECT name, colour, calories
    FROM fruit
    WHERE calories < :calories AND colour = :colour";
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':calories' => 150, ':colour' => 'red'));
$red = $sth->fetchAll();

        if( count($whereCols) > 0 )
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
