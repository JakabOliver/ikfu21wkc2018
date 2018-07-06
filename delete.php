<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 2018-07-05
 * Time: 0:08
 */
header('Content-type: application/json');
if(isset($_POST['token']) && $_POST['token']=='za+nt_ec-B_#-r7k_eeN1smV*Cx?s^') {
    unlink('news/' . $_POST['file']);
    $isSuccess=true;
}
else {
    $isSuccess=false;
}
echo json_encode(array('success'=>$isSuccess));