<?php
namespace core\Dbmap;

use core\Dbmap\DBlueprint;
include __DIR__ . '/../Config.php';
class DataTable {
    private static $Instance;
    private static $Conn;
    private static $DBlueprint;
    private $Query;
    private $QueryString;

    function __construct(){
        self::$Conn = new \PDO(DRIVER.":host=".HOST.";dbname=".DBNAME.";", USER, PASS);
        self::$Conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$DBlueprint = new DBlueprint;
    }

    public static function run(){
        if(is_null(self::$Instance)){
            self::$Instance = new self();
        }
        return self::$Instance;
    }

    public function checkTable($tableName = 'users'){
        $queryString = "SHOW TABLES LIKE '{$tableName}'";
        $query = self::$Conn->prepare($queryString);
        $query->execute();
        $tableExists = $query->rowCount();
        return $tableExists;
    }

    public function createTable(string $tableName, $closure){
            $tableName = trim($tableName);
            $checkTable = $this->checkTable($tableName);// Check if this table already exists
            if(!$checkTable){
                $result = $closure(self::$DBlueprint)->returnAll();
                $this->QueryString = "CREATE TABLE {$tableName}(".
                implode("," ,array_values((array)$result['qstring'])).
                ( isset($result['index'])  ?  (','.implode("," ,array_values((array)$result['index'])).')')   : ')');
                $this->preparing();
            }else{
                echo "tabela >> {$tableName} << já existente\n";
            }
    }

    public function dropTable(string $tableName){
            $checkTable = $this->checkTable($tableName);// Check if this table already exists
            if($checkTable){
                $this->QueryString = "DROP TABLE {$tableName}";
                $this->preparing();
            }else{
                echo "tabela >> {$tableName} << não existente\n";
            }
    }

    private function preparing(){
        $this->Query = self::$Conn->prepare($this->QueryString);
        $this->executing();
    }
    private function executing(){
        $this->Query->execute();
        $this->Done = $this->Query->rowCount();
    }
}
