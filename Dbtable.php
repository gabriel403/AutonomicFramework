<?php

class Autonomic_Dbtable {

	private $_tableName = null;

	public function connect($config = array()) {
		if ( count($config) == 0 )
			$config = Autonomic_Bootstrap::getConfig();
		$dbdetails = $config['database'];
		$this->dbh = new PDO("mysql:host={$dbdetails['host']};dbname={$dbdetails['dbname']}", $dbdetails['username'], $dbdetails['password']);
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
	 * array(
	 *   array('name', 'EQUALS', 'fred', 'OR'),
	 *   array('password', 'EQUALS', 'bobbles'), //assumes and
	 *   array('id', '1') //assumes =, and
	 * )
	 * conditionalType is one EQUALS, NOTEQUALS, GREATERTHAN, LESSTHAN
	 * connectionType is AND or OR
	 */
	public function select($wheres = array(), $cols = "*") {
		$connectionType = array('AND' => 1, 'OR' => 1);
		$conditionalTypes = array('EQUALS' => '=', 'NOTEQUALS' => '!=', 'GREATERTHAN' => '>', 'LESSTHAN' => '<');

		if ( !isset($this->_tableName) )
			throw new Exception("No table name set");
		if ( !is_array($wheres) )
			throw new Exception("incorrect syntax for where conditions.");

		$whereCols = array();
		$whereVals = array();

		if ( count($wheres) > 0 ) {
			if ( !is_array($wheres[0]) )
				$wheres = array($wheres);
			
			foreach ( $wheres as $where ) {
				switch ( count($where) ) {
					case 2:
						$whereCols[] = "{$where[0]} = :{$where[0]} AND";
						$whereVals[":{$where[0]}"] = "{$where[1]}";
						break;
					case 3:
						if ( !array_key_exists($where[1], $conditionalTypes) )
							throw new Exception("incorrect syntax for where conditions.");
						$whereCols[] = "{$where[0]} {$conditionalTypes[$where[1]]} :{$where[0]} AND";
						$whereVals[":{$where[0]}"] = "{$where[2]}";
						break;
					case 4:
						if ( !array_key_exists($where[1], $conditionalTypes) || !array_key_exists($where[3],
								$connectionType) )
							throw new Exception("incorrect syntax for where conditions.");
						$whereCols[] = "{$where[0]} {$conditionalTypes[$where[1]]} :{$where[0]} {$where[3]}";
						$whereVals[":{$where[0]}"] = "{$where[2]}";
						break;
					default:
						throw new Exception("incorrect syntax for where conditions.");
				}
			}
		}

		$sql = "";

		if ( count($whereCols) > 0 ) {
			$sql = implode(" ", $whereCols);

			foreach ( $connectionType as $key => $value ) {
				if ( substr_compare($sql, $key, -strlen($key), strlen($key), true) === 0 ) {
					$sql = trim(substr($sql, 0, -strlen($key)));
					break;
				}
			}
			$sql = "WHERE $sql";
		}
		$cols = is_array($cols) ? implode(", ", $cols) : $cols;

		$sql = "SELECT $cols FROM " . $this->_tableName . " $sql;";
		$sth = $this->dbh->prepare($sql,
				array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute($whereVals);
		$return = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $return;

		/*       if( count($whereCols) > 0 )
		  $whereCols = " WHERE " . $whereCols;

		  $stmt = $this->dbh->prepare("SELECT * FROM {$this->_tableName} where name = ?");
		  if( $stmt->execute($whersVals) ) {
		  while( $row = $stmt->fetch() ) {
		  print_r($row);
		  }
		  }
		 */
	}

	/**
	 * 
	 * 
	 * array(
	 *      'colName'=>'value'
	 * )
	 *
	 * @param type $keyValues 
	 */
	public function insert($keyValues) {
		$cols = array();
		$vals = array();
		foreach ( $keyValues as $key => $value ) {
			$cols[] = "`$key`";
			$vals[":$key"] = "$value";
		}

		$colsStr = implode(", ", $cols);
		$valsStr = implode(", ", array_keys($vals));

		$sql = "INSERT INTO " . $this->_tableName . " ($colsStr) VALUES ($valsStr)";

		$query = $this->dbh->prepare($sql);
		$return = $query->execute($vals);
		return $return;
	}

}

?>
