<?php

namespace core;

include 'Config.php';

class FSBRDatabase {
    protected static $Conn;
    private $Operation;

    // insert()
    private $Insert;
    // update()
    private $Update;

    private $StringQuery;
    private $Table;
    private $Options;
    private $Where;
    private $Limit;
    private $OrderBy;

    // PDO
    private $Query;

    function __construct(){
        self::$Conn = new \PDO(DRIVER.":host=".HOST.";dbname=".DBNAME.";", USER, PASS);
        self::$Conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    // PDO methods
    private function preparing(){
        $this->Query = self::$Conn->prepare($this->StringQuery);
        $this->executing();
    }
    private function executing(){
        try{
            $this->Query->execute();
        }catch(PDOException $e){
            var_dump($e);
        }
    }

    // Executor
    public function buildQuery(){

        switch($this->Operation){
            case 'select':
                $this->StringQuery = "SELECT {$this->Options} FROM {$this->Table} {$this->Where} {$this->Limit} {$this->OrderBy}";
                break;

            case 'create':
                // INSERT INTO table (column1, column2) VALUES (value1, value2)
                $this->StringQuery = "INSERT INTO {$this->Table} {$this->Insert}";
                break;

            case 'update':
                // UPDATE table SET column1 = value1, column2 = value2 WHERE id = 1
                $this->StringQuery = "UPDATE {$this->Table} {$this->Update} {$this->Where} {$this->Limit} {$this->OrderBy}";
                break;

            case 'delete':
                // DELETE FROM table WHERE id = 1
                $this->StringQuery = "DELETE FROM {$this->Table} {$this->Where} {$this->Limit} {$this->OrderBy}";
                break;
        }
        $this->preparing();
    }

    // OPERATIONS
    public function select($table, $options = '*'){
        $this->reset();
        $this->Operation = 'select';
        $this->Table = $table;
        $this->Options = $options;
        return $this;
    }
    public function create($table, array $dados){
        $this->reset();
        $this->Operation = 'create';
        foreach($dados as $key => $value){
            $new_data[$key] = "'{$value}'";
        }
        $columns = implode(',',array_keys($new_data));
        $values = implode(',',array_values($new_data));
        $this->Insert = "({$columns}) VALUES ($values)";
        $this->Table = $table;
        return $this;
    }
    public function update($table, array $dados){
        $this->reset();
        $this->Operation = 'update';
        foreach($dados as $key => $value){
            $set[] = "{$key}='{$value}'";
        }
        $set = implode(',' , $set);
        $this->Table = $table;
        $this->Update = "SET {$set}";
        return $this;
    }
    public function delete($table){
        $this->reset();
        $this->Operation = 'delete';
        $this->Table = $table;
        return $this;
    }

    // Conditional
    public function where(){
        $numArgs = func_num_args();
        $args = func_get_args();
        switch($numArgs):
        case 1:
            $this->Where = "WHERE ".$args[0]." ";
            break;
        case 2:
            $this->Where = "WHERE ".$args[0]." = '".$args[1]."' ";
            break;
        case 3:
            $this->Where = "WHERE ".$args[0]." ".$args[1]." '".$args[2]."' ";
            break;
        default:
            $this->Where = "WHERE ";
        endswitch;

        return $this;
    }

    public function customWhere(){
        $numArgs = func_num_args();
        $args = func_get_args();
        switch($numArgs):
        case 1:
            $this->Where .= $args[0]." ";
            break;
        case 2:
            $this->Where .= $args[0]." = '".$args[1]."' ";
            break;
        case 3:
            $this->Where .= $args[0]." ".$args[1]." '".$args[2]."' ";
            break;
        default:
            echo "\nArgs ERROR: wrong amount of args\n";
        endswitch;

        return $this;
    }

    public function or(){
        $numArgs = func_num_args();
        $args = func_get_args();
        switch($numArgs):
        case 0:
            $this->Where .= "OR ";
            break;
        case 2:
            $this->Where .= "OR ".$args[0]." = '".$args[1]."' ";
            break;
        case 3:
            $this->Where .= "OR ".$args[0]." ".$args[1]." '".$args[2]."' ";
            break;
        default:
            echo "\nArgs ERROR: wrong amount of args\n";
        endswitch;

        return $this;
    }

    public function and(){
        $numArgs = func_num_args();
        $args = func_get_args();
        switch($numArgs):
        case 0:
            $this->Where .= "AND ";
            break;
        case 2:
            $this->Where .= "AND ".$args[0]." = '".$args[1]."' ";
            break;
        case 3:
            $this->Where .= "AND ".$args[0]." ".$args[1]." '".$args[2]."' ";
            break;
        default:
            echo "\nArgs ERROR: wrong amount of args\n";
        endswitch;

        return $this;
    }

    public function not(){
        $numArgs = func_num_args();
        $args = func_get_args();
        switch($numArgs):
        case 0:
            $this->Where .= "NOT ";
            break;
        case 2:
            $this->Where .= "NOT ".$args[0]." = '".$args[1]."' ";
            break;
        case 3:
            $this->Where .= "NOT ".$args[0]." ".$args[1]." '".$args[2]."' ";
            break;
        default:
            echo "\nArgs ERROR: wrong amount of args\n";
        endswitch;

        return $this;
    }

    public function orWhere(){
        $numArgs = func_num_args();
        $args = func_get_args();

        foreach($args as $arg){
            if(!is_array($arg)){
                throw new Exception('Args Error: '.__FUNCTION__.'() Expecting type Array for your parameters');
            }elseif(count($arg) > 3 || count($arg) < 2){
            throw new Exception('Args Error: '.__FUNCTION__."() Expecting an array with 3 values where you put {$arg}");
            }
        }

        $this->Where .= "( ";
        for($i=0;$i < $numArgs;$i++){
            if($i != ($numArgs-1) && $numArgs > 2){
                $this->Where .= $args[$i][0]." ".$args[$i][1]." '".$args[$i][2]."' OR ";
            }elseif($i != ($numArgs-1) && $numArgs == 2){
                $this->Where .= $args[$i][0]." = '".$args[$i][1]."' OR ";
            }else{
                $this->Where .= $args[$i][0]." ".$args[$i][1]." '".$args[$i][2]."' ";
            }
        }
        $this->Where .= ") ";

        return $this;
    }

    public function andWhere(){
        $numArgs = func_num_args();
        $args = func_get_args();
        
        foreach($args as $arg){
            if(!is_array($args)){
                throw new Exception('Args Error: '.__FUNCTION__.'() Expecting type Array for your parameters');
            }elseif(count($arg) > 3 || count($arg) < 2){
            throw new Exception('Args Error: '.__FUNCTION__."() Expecting an array with 3 values where you put {$arg}");
            }
        }

        $this->Where .= "( ";
        for($i=0;$i < $numArgs;$i++){
            if($i != ($numArgs-1) && $numArgs > 2){
                $this->Where .= $args[$i][0]." ".$args[$i][1]." '".$args[$i][2]."' AND ";
            }elseif($i != ($numArgs-1) && $numArgs == 2){
                $this->Where .= $args[$i][0]." = '".$args[$i][1]."' AND ";
            }else{
                $this->Where .= $args[$i][0]." ".$args[$i][1]." '".$args[$i][2]."' ";
            }
        }
        $this->Where .= ") ";

        return $this;
    }

    // Helpers
    public function limit($limit){
        if($limit){$this->Limit = "LIMIT ".$limit;}
        return $this;
    }
    public function orderBy($order, $esp = null){
        if($order){$this->OrderBy = "ORDER BY ".$order;}
        return $this;
    }


    // Result Methods
    public function all(){
        $this->buildQuery();
        return $this->Query->fetchAll();
    }

    public function single(){
        $this->buildQuery();
        return $this->Query->fetch();
    }

    public function confirm(){
        $this->buildQuery();
        return $this->Query->rowCount();
    }

    public function lastInsertId(){
        $this->buildQuery();
        return self::$Conn->lastInsertId();
    }

    // Reset method
    public function reset(){
        foreach($this as $key => $property){
            if($key != 'Query' && $key != 'Conn'){
                $this->$key = null;
            }    
        }
    }

    // Debbug methods
    public function returnWhere(){
        echo $this->StringQuery."\n";
        echo $this->Where."\n";
    }
}