<?php

namespace core;

class phprender {
    private $Html;
    private $Filename;
    // Find Vars on Html
    private $Regex_ToFindVars = '/[\s]{0}[\{]{2}[ ]*[a-zA-Z\_][\w\[\]\'\"\-\>]+[ ]*[\}]{2}[\s]{0}/';
    // Replace Vars on html for $+varname
    private $Regex_ToReplaceFound = '/[\s]{0}[^\{ ]+[a-zA-Z\_][\w\[\]\'\"\-\>]+[^\ }]+[\s]{0}/';

    // Find Vars inside php structs
    private $Regex_ToFindVars_On_structs = '/[\s]{0}\@[a-z]+\(([ $\w$]+)\)\:[\s]{0}/';

    

    private $Keywords = ['__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
                        'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif',
                        'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit',
                        'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include',
                        'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or',
                        'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch',
                        'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'true', 'false','__CLASS__', '__DIR__', '__FILE__',
                        '__FUNCTION__', '__LINE__', '__METHOD__', '__NAMESPACE__', '__TRAIT__'];


    private $Vars_Found;

    // Opening Structs
    

    function __construct($html, $file = null){
        if($file){
            $this->Html = file_get_contents($html);

        }else{
            $this->Html = $html;
        }
        $this->FindVars();
    }

    private function FindVars(){
        preg_match_all($this->Regex_ToFindVars, $this->Html, $this->Vars_Found);
        //var_dump($this->Vars_Found);die();
        $this->ReplacingVars_OutOf_Structs($this->Vars_Found[0]);
    }

    private function ReplacingVars_OutOf_Structs($outsideVars){
        $replaced = null;
        foreach($outsideVars as $key){
            preg_match($this->Regex_ToReplaceFound, $key, $replaced);
            if(!in_array($key, $this->Keywords)){
                $this->Html = str_replace($key, '<?=$'.$replaced[0].';?>', $this->Html);
            }
        }
        $this->ReplacingVars_InsideOf_Structs();
    }

    private function ReplacingVars_InsideOf_Structs(){
        $this->ReplacingStructs_Opennings();
    }

    private function ReplacingStructs_Opennings(){
        // Openners
        $this->Html = str_replace(":>", "<?php ", $this->Html);
        //if
        $this->Html = str_replace("@if(", "<?php if(", $this->Html);
        $this->Html = str_replace("@elseif(", "<?php elseif(", $this->Html);
        $this->Html = str_replace("@else",  "<?php else: ?>", $this->Html);
        //While
        $this->Html = str_replace("@while(", "<?php while(", $this->Html);
        //Foreach
        $this->Html = str_replace("@foreach(", '<?php foreach(', $this->Html);

        $this->ReplacingStructs_Closing();
    }

    private function ReplacingStructs_Closing(){
        // Closers
        $this->Html = str_replace("):", "): ?>", $this->Html);
        $this->Html = str_replace("<:", " ?>", $this->Html);
        // endif
        $this->Html = str_replace("@endif", '<?php endif; ?>', $this->Html);
        // endwhile
        $this->Html = str_replace("@endwhile", '<?php endwhile; ?>', $this->Html);
        // endforeach
        $this->Html = str_replace("@endforeach", '<?php endforeach; ?>', $this->Html);
    }

    public function getHtml(){
        return $this->Html;
    }


}