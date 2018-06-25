<?php

$mod="main";
if(isset($_GET['mod'])){
    $mod=$_GET['mod'];
}

$html="";
$head=file_get_contents("parts/head.html");
$content=file_get_contents("parts/".$mod.".html");
$footer=file_get_contents("parts/footer.html");



$html=$head.$content.$footer;
echo $html;