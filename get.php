<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 2018-07-05
 * Time: 0:23
 */
header('Content-type: application/json');
if(isset($_POST['token']) && $_POST['token']=='za+nt_ec-B_#-r7k_eeN1smV*Cx?s^') {
    $file=json_decode(file_get_contents('news/'.$_POST['file']));
   // error_log(json_encode($file));
    $response=array('success'=>true, 'title'=>$file->title, 'content'=>$file->content, 'kep'=>$file->kep);
}
else {
    $response=array('success'=>false);
}
echo json_encode($response);