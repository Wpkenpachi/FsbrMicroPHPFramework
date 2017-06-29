<?php

namespace core\Dbmap;

class DBlueprint {

    public $QString;
    public $Index;
    public $Name;

    public function char($name ,$size){
        $this->Name = $name;
        $this->QString[$name] = $name . " CHAR({$size})";
        return $this;
    }
    public function varchar($name ,$size){
        $this->Name = $name;
        $this->QString[$name] = $name . " VARCHAR({$size})";
        return $this;
    }
    public function text($name){
        $this->Name = $name;
        $this->QString[$name] = $name . " TEXT";
        return $this;
    }
    public function blob($name){
        $this->Name = $name;
        $this->QString[$name] = $name . " BLOB";
        return $this;
    }
    public function longText($name){
        $this->Name = $name;
        $this->QString[$name] = $name . " LONGTEXT";
        return $this;
    }
    public function longBlob($name){
        $this->Name = $name;
        $this->QString[$name] = $name . " LONGBLOB";
        return $this;
    }

    public function enum($name, array $enums){
        $this->Name = $name;
        foreach($enums as $e => $value){
            $enums[$e] = "'{$value}'";
        }
        $this->QString[$name] = $name .' ENUM('.implode(',', array_values($enums)).')';
        return $this;
    }

    public function tinyInt($name, $size){
        $this->Name = $name;
        $this->QString[$name] = $name . ' TINYINT('.$size.')';
        return $this;
    }
    public function smallInt($name, $size){
        $this->Name = $name;
        $this->QString[$name] = $name . ' SMALLINT('.$size.')';
        return $this;
    }
    public function int($name, $size){
        $this->Name = $name;
        $this->QString[$name] = $name . ' INT('.$size.')';
        return $this;
    }
    public function bigInt($name, $size){
        $this->Name = $name;
        $this->QString[$name] = $name . ' BIGINT('.$size.')';
        return $this;    
    }
    public function float($name, $size, $d){
        $this->Name = $name;
        $this->QString[$name] = $name . ' FLOAT('.$size.','.$d.')';
        return $this;
    }
    public function double($name, $size, $d){
        $this->Name = $name;
        $this->QString[$name] = $name . ' DOUBLE('.$size.','.$d.')';
        return $this;
    }
    public function decimal($name, $size, $d){
        $this->Name = $name;
        $this->QString[$name] = $name . ' DECIMAL('.$size.','.$d.')';
        return $this;
    }
    public function dateType($name){
        $this->Name = $name;
        $this->QString[$name] = $name . ' DATE';
        return $this;
    }
    public function datetimeType($name){
        $this->Name = $name;
        $this->QString[$name] = $name . ' DATETIME';
        return $this;
    }
    public function timestampType($name){
        $this->Name = $name;
        $this->QString[$name] = $name . ' TIMESTAMP';
        return $this;
    }
    public function timeType($name){
        $this->Name = $name;
        $this->QString[$name] = $name . ' TIME';
        return $this;
    }
    public function yearType($name){
        $this->Name = $name;
        $this->QString[$name] = $name . ' YEAR';
        return $this;
    }

    // MODIFICADORES
    public function unique(){
        $this->QString[$this->Name] .= ' UNIQUE ';
        return $this;
    }

    public function notNull(){
        $this->QString[$this->Name] .= ' NOT NULL ';
        return $this;
    }

    public function increments(){
        $this->QString[$this->Name] .= ' NOT NULL AUTO_INCREMENT ';
        return $this;
    }

    public function primaryKey(){
        $this->QString[$this->Name] .= ' PRIMARY KEY ';
        return $this;
    }

    public function onUpdate($arg){
        $arg = strtoupper($arg);
        if(is_null($this->Index[$this->Name]) || empty($this->Index[$this->Name])){
            $this->Index[$this->Name] = '';
            $this->Index[$this->Name] = " ON UPDATE {$arg}";
        }else{
            $this->Index[$this->Name] .= " ON UPDATE {$arg}";
        }
        return $this;
    }
    public function onDelete($arg){
        $arg = strtoupper($arg);
        if(is_null($this->Index[$this->Name]) || empty($this->Index[$this->Name])){
            $this->Index[$this->Name] = '';
            $this->Index[$this->Name] = " ON DELETE {$arg}";
        }else{
            $this->Index[$this->Name] .= " ON DELETE {$arg}";
        }
        return $this;
    }
    public function foreign($fk_name ,$tb_field, $target_table, $target_field){
        $this->Name = $tb_field;
        if($this->Index[$tb_field] == null){
            $this->Index[$this->Name] = " FOREIGN KEY {$fk_name}({$tb_field})
         REFERENCES {$target_table}({$target_field})";
        }else{
            $this->Index[$this->Name] .= " FOREIGN KEY {$fk_name}({$tb_field})
         REFERENCES {$target_table}({$target_field})";
        }
        return $this;
    }

    public function returnAll(){
        $qstring = $this->QString;
        $index = $this->Index;
        return compact('result', ['qstring', 'index']);
    }
    
}

