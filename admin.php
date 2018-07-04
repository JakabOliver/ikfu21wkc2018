<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 2018-07-03
 * Time: 22:41
 */
session_start();
if(!empty($_POST)){
    if(isset($_POST['login'])) {
        if ($_POST['username'] == 'media' && $_POST['password'] == 'Almafa') {
            $_SESSION['loggedin'] = true;
            echo 'sikeres login';
        } else {
            echo 'sikertelen belépés';
        }
    }elseif(isset($_POST['upload'])){
        if(empty(trim($_POST['title'])))
            echo 'A cím nem lehet üres<br/>';
        elseif(empty(trim($_POST['content'])))
            echo 'A tartalom nem lehet üres<br/>';
        else{
            file_put_contents('news/'.ekezetmentesites(trim($_POST['title'])).'.json', json_encode($_POST));
            echo 'sikeres feltöltés';
        }
    }
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
    $html=file_get_contents("parts/upload.html");
    $newsListTemplate=file_get_contents("parts/templates/newsListTemplate.html");
    $newsList='';
    $scanned_directory = array_diff(scandir('news/'), array('..', '.'));
    foreach($scanned_directory as $news){
        $newsArray=json_decode(file_get_contents('news/'.$news));
        $newsList.=str_replace(array('$title','$ekezetmentesTitle'), array($newsArray->title, ekezetmentesites($newsArray->title)), $newsListTemplate);
    }
    $html=str_replace('<news>', $newsList, $html);
}else{
    $html=file_get_contents("parts/login.html");
}
echo $html;


function ekezetmentesites($var){
     $search=array('á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű', ' ');
    $replace=array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U', '_');
    return str_replace($search, $replace, $var);
}