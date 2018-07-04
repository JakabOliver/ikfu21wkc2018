<?php

$teams=['Hungary', 'Netherlands','Czech Republic', 'England',  'Taiwan','Belgium', 'China',  'Germany', 'Hong Kong','Portugal', 'Turkey'];

$mod="main";
if(isset($_GET['mod'])){
    $mod=$_GET['mod'];
}


$html="";
$head=file_get_contents("parts/head.html");
$content=file_get_contents("parts/".$mod.".html");
$footer=file_get_contents("parts/footer.html");

if($mod=='teams'){
    $teamTemplate=file_get_contents('parts/templates/teamTemplate.html');
    $teamsContent='';
    foreach($teams as $k=>$team){
        $teamDescription=file_get_contents('parts/teams/'.ekezetmentesites($team).'.txt');
        $teampic=str_replace(' ', '_', $team);
        $teamsContent.=str_replace(array('$teampic', '$team', '<teambembers>'), array($teampic, $team, $teamDescription), $teamTemplate);
    }
    //generate the content.
    $content=str_replace('<teams>', $teamsContent, $content);
}elseif($mod=='main'){
    $newsTemplate=file_get_contents('parts/templates/newsTemplate.html');
    $newsContent='';
    $scanned_directory = array_diff(scandir('news/'), array('..', '.'));
    foreach($scanned_directory as $news){
        $newsArray=json_decode(file_get_contents('news/'.$news));
        $newsContent.=str_replace(array('$title', '$content'), array($newsArray->title, $newsArray->content), $newsTemplate);
    }
    //beolvasás
    $content=str_replace('<news>', $newsContent, $content);
}


$html=$head.$content.$footer;
echo $html;
function ekezetmentesites($var){
    $search=array('á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű', ' ');
    $replace=array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U', '_');
    return str_replace($search, $replace, $var);
}