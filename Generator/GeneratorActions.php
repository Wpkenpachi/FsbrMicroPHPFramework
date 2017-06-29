<?php

use core\Dbmap\DBlueprint;
use core\Dbmap\DataTable;

class GeneratorActions {

    private $DirList;
    private $Helper;

    private $DataTable;

    /*
    * @param $dir:
    * Caminho do arquivo json com o mapa de caminhos pra onde devem ser enviados os Controllers, Models, e DBMaps criados.
    */
    function __construct(string $dir){
        $this->DirList = json_decode(file_get_contents($dir), true);
        $this->DataTable = new DataTable;
    }

    //===============================================
    //              MAKE
    //===============================================
    public function make($args){
        // Variáveis locais
        {// Obtendo Caminho onde ficam os arquivos do typo pedido e atribuindo o nome escolhido para o arquivo a $nameToCheck.
            $typeToCheck = $this->DirList[$args[2]];
            $nameToCheck = $args[3];
            $type = $args[2];
            $checkedName = false;
        }//
        
        {// Fazendo a verificação, pra saber se já existe algum arquivo do tipo escolhido com o nome escolhido
            if(!file_exists(__DIR__ . $typeToCheck.$nameToCheck.'.php')):
                $checkedName = true;
            endif;
        }//

        {//
            if($checkedName):
                // setting new file configs
                $name = $nameToCheck;
                $fileName = $nameToCheck.'.php';
                $fileDir = __DIR__ . $typeToCheck . $fileName;
                // getting controller mods
                $modFile = file_get_contents(__DIR__.$this->DirList['mods'].$type);
                //var_dump($modFile);die();
                if($type != 'dbmap'){
                    $modContent = str_replace(" @name ", " {$name} ", $modFile);
                }else{
                    $modContent = str_replace("@name", "{$name}", $modFile);
                }
                // creating file and writing
                $fp = fopen($fileDir, "a");
                fwrite($fp, $modContent);
                fclose($fp);

            endif;
        }//
    }

    //===============================================
    //              SERVER
    //===============================================
    public function server(){
        
    }

    //===============================================
    //              LISTAR
    //===============================================
    public function lists($args){

        {// Variáveis locais
        // Typo do arquivo a ser listado [Controller, View, MOdel...]
        $typeToShow           = isset($args[2]) ? $args[2] : null;
        // Arquivo com o nome dos arquivos existentes do tipo procurado. [ nome de todos os controllers, models, view existentes]
        $array_with_type_list = json_decode(file_get_contents(__DIR__ . $this->DirList['typesList'].$typeToShow.'.json'), true);
        }

        {
            // Atualizando todas as listas antes de mostra-las, caso não sejam as rotas a listagem pedida
            if($typeToShow != 'route'){
                $this->update();
            }
        }

        {// Listando
            if($typeToShow != 'route'):
                echo ":============= ".ucfirst($typeToShow)."s ============:\n";
                foreach((array)$array_with_type_list as $type):
                    echo ":".$type[$typeToShow]."\n";
                endforeach;
                echo ":====================================:\n";
            elseif($typeToShow == 'route'):
                echo ":============= ".ucfirst($typeToShow)."s ============:\n";
                foreach((array)$array_with_type_list as $type):
                    echo ": VERB: ".$type['verb']."\n";
                    echo ": URL: ".$type['url']."\n";
                    if(!is_string($type['controller'])):
                        echo ": CTRL: (Closue) \n";
                    else:
                        echo ": CTRL: ".$type['controller']."\n";
                        echo ": ACTION:".$type['action']."\n";
                    endif;
                    echo ": DATA:".$type['data']."\n";
                    echo ":-------------------------------\n";
                endforeach;
                echo ":====================================:\n";        
            endif;
        }//
    }

    //===============================================
    //              MANAGE
    //===============================================
    public function manage(){
        echo 'make';
    }


    //===============================================
    //              UPDATE
    //===============================================
    function update(){
        {// Percorre os diretórios cadastrados, coletando os arquivos e salvando os nomes deles em $news[]
            $news = [];
            foreach($this->DirList as $dir => $value):
                if( $handle = opendir(__DIR__ . $value) ):
                    while($entry = readdir( $handle )):
                        $extension = strtolower( pathinfo($entry, PATHINFO_EXTENSION) );
                        if($extension == 'php') {
                            $news[$dir][]  = [$dir => $entry];
                        }
                    endwhile;
                endif;
            endforeach;
        }//
        
        
        {// Apagando os arquivos json , e os reconstruindo!
            if( $handle = opendir(__DIR__ . $this->DirList['typesList']) ):
                while($entry = readdir( $handle )):
                    $extension = strtolower( pathinfo($entry, PATHINFO_EXTENSION) );
                    if($extension == 'json') {
                        // Apagamos
                        unlink(__DIR__.$this->DirList['typesList'].$entry);
                        $fp = fopen(__DIR__ . $this->DirList['typesList'].$entry, "a");
                        fwrite($fp, '');
                        fclose($fp);        
                    }
                endwhile;
            endif;
        }// 

        {// Atualizando os arquivos json, com as informações que foram encontradas na varredura dos diretorios
            foreach($news as $new => $value):
                $fp = fopen(__DIR__ . $this->DirList['typesList'].$new.'.json', "a");
                fwrite($fp, json_encode($value));
                fclose($fp);
            endforeach;
        }//

    }

    //===============================================
    //              UPTABLES
    //===============================================
    public function up_tables($args){
        $name = isset($args[2]) ? $args[2] : die("Miss argument.\n");
        if($name && $name != 'all'):
            include(__DIR__ . $this->DirList['dbmap'].$name.'.php');
        elseif($name == 'all'):
        {//
            if( $handle = opendir(__DIR__ . $this->DirList['dbmap'] ) ):
                while($entry = readdir( $handle )):
                    $extension = strtolower( pathinfo($entry, PATHINFO_EXTENSION) );
                    if($extension == 'php'):
                        include(__DIR__ . $this->DirList['dbmap'].$entry);
                    endif;
                endwhile;
            endif;
        }// 
        endif;
    }

    //===============================================
    //              REFRESHTABLES
    //===============================================
    public function refresh_tables($args){
        $name = isset($args[2]) ? $args[2] : die("Miss argument.\n");
        if($name && $name != 'all'):
            $this->DataTable->run()->dropTable($name);
            // Depois de deletar todas as tabelas, vamos refazê-las :D.
            $this->up_tables();
        elseif($name == 'all'):
        {//
            if( $handle = opendir(__DIR__ . $this->DirList['dbmap'] ) ):
                while($entry = readdir( $handle )):
                    $extension = strtolower( pathinfo($entry, PATHINFO_EXTENSION) );
                    if($extension == 'php'):
                        $this->DataTable->run()->dropTable(basename($entry, '.php'));
                    endif;
                endwhile;
            endif;
        }// 
        // Depois de deletar todas as tabelas, vamos refazê-las :D.
        $this->up_tables();
        endif;
    }

    //===============================================
    //              DELETETABLES
    //===============================================
    public function delete_tables($args){
        $name = isset($args[2]) ? $args[2] : die("Miss argument.\n");
        if($name && $name != 'all'):
            $this->DataTable->run()->dropTable($name);
        elseif($name == 'all'):
        {//
            if( $handle = opendir(__DIR__ . $this->DirList['dbmap'] ) ):
                while($entry = readdir( $handle )):
                    $extension = strtolower( pathinfo($entry, PATHINFO_EXTENSION) );
                    if($extension == 'php'):
                        $this->DataTable->run()->dropTable(basename($entry, '.php'));
                    endif;
                endwhile;
            endif;
        }// 
        endif;
    }

}