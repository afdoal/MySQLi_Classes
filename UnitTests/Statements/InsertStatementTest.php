<?php
use MySQLi_Classes\Exceptions\Server\UnknownErrorException;
use MySQLi_Classes\Statements\InsertStatement;
use MySQLi_Classes\Statements\SelectStatement;
use \Glucose\Entity as Entity;
use \Glucose\EntityEngine as EntityEngine;

class InsertStatementTest extends TableComparisonTestCase {
	
	private static $mysqli;
	
	protected function setUp() {
		$this->comparisonSchema = $GLOBALS['comparisonSchema'];
		$this->actualSchema = $GLOBALS['schema'];
		self::$mysqli = $GLOBALS['mysqli'];
		
		self::$mysqli->query('START TRANSACTION;');
	}
	
	protected function getConnection() {
		return self::$mysqli;
	}
	
	public function test_P_StandardInsert() {
		$stmt = new InsertStatement("INSERT INTO `{$this->actualSchema}`.`cities`
			(`country`, `name`, `postal_code`)
			VALUES (?, ?, ?)", 'isi');
		$countryID = 1;
		$name = 'Esbjerg';
		$postalCode = 6700;
		$stmt->bindAndExecute($values = array($countryID, $name, $postalCode));
		$insertID = $this->insertInto('cities', array('country' => $countryID, 'name' => $name, 'postal_code' => $postalCode));
		$this->assertTablesEqual('cities');
		$this->assertEquals($insertID, $stmt->insertID);
	}
	
	protected function tearDown() {
		self::$mysqli->query('ROLLBACK;');
	}
}
