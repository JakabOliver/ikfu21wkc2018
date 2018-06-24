<?php

$mod="main";
if(isset($_GET['mod'])){
    $mod=$_GET['mod'];
}

$html="";
$html.=file_get_contents("parts/head.html");
$html.=file_get_contents("parts/".$mod.".html");
$html.=file_get_contents("parts/footer.html");

echo $html;