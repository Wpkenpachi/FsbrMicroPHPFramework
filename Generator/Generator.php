<?php
include 'GeneratorActions.php';
class FSBRGenerator {
    private $ActionsInstance;
    private $ArgsList;
    /*
    * @param $valid_argsList:
    * Caminho do arquivo json com os comandos válidos. [make, list , {...}]
    * @param $dirList:
    * Caminho do arquivo json com o mapa de caminhos pra onde devem ser enviados os Controllers, Models, e DBMaps criados.
    */
    function __construct(string $valid_argsList, string $dirList){
        $this->ActionsInstance = new GeneratorActions($dirList);
        $this->ArgsList = $valid_argsList;
    }

    /*
    * @param $args:
    * Recebe os $argv[]
    */
    public function Args(array $args){
        
        {// Obtendo os argumentos válidos
            $_valid_args = file_get_contents($this->ArgsList);
            $_valid_args = json_decode($_valid_args, true);
        }//

        
        {// Checando se o argumento passado se está dentro da lista de argumentos aceitos. E chamando a função corresponte
            if(in_array($args[1], $_valid_args['commands'])):
            $Action = $args[1];
            $this->ActionsInstance->$Action($args);
            else:
                echo "Esse comando não faz parte dos reconhecidos, segue a lista dos comandos reconhecidos.\n";
                foreach($_valid_args['commands'] as $commands):
                    echo "[ ".$commands." ] \n";
                endforeach;
            endif;
        }//

    }


}


