<?php

class ArgvParser {
    public function parse(array $arguments): Input {
        $options = [];
        $args = [];
        
        foreach ($arguments as $argument){
            if (str_starts_with($argument, "--")){
                if (str_contains($argument, "=")){
                    $data = explode("=", $argument, 2);
                    $options[ltrim($data[0], '-')] = $data[1];    
                } else {
                    $options[ltrim($argument, '-')] = true; 
                }

            } else {
                $args[] = $argument;
            }             
        }

        return new Input($args, $options);
    }
}