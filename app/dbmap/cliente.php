<?php
use core\Dbmap\DBlueprint;
use core\Dbmap\DataTable;

DataTable::run()->createTable('cliente', function(DBlueprint $table) {
        $table->int('id', 10)->increments()->primaryKey();
        $table->varchar('nome', 100);
        $table->varchar('email', 100);
        $table->varchar('senha', 15);
        $table->char('sexo', 1)->notNull();
        $table->enum('role', ['ADM', 'USER']);
        return $table;
});