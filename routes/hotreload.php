<?php

use lyn\helpers\Config;

[$mode] =\lyn\Request::$slugs;
if($mode==='check'){
    $hash_file_name = base_path.'/tmp/hotreload.hash';
    $hash_file = fopen( base_path.'/tmp/hotreload.hash', "r" );
    $filesize = filesize(  $hash_file_name );
    $hashText = fread( $hash_file, $filesize );
    $hash_files = (array)json_decode($hashText);
    fclose( $hash_file );
    $exclude = Config::$config['app']['hotreload']['exclude'];
    $runfiles = get_included_files();
    $reload = false;
    foreach($hash_files as $file=>$hash){
        if($hash !== hash_file('md5', $file)){
            $reload=true;
            break;
        }
    }
    return json_encode(['reload' =>  $reload]);
}
