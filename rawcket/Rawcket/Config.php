<?php

namespace Rawcket;

class Config{
	static $debug = true;
    static $database=array(
        'default'=>array(
            'host'=>'local.dev',
            'database'=>'jeunesseduprintemps',
            'login'=>'root',
            'password'=>'',
        ),
        'online'=>array(
                'host'=>'',
                'database'=>'',
                'login'=>'',
                'password'=>'',
        ),
    );
}