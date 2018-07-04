<?php

$teams=['Taiwan','Belgium', 'China', 'Czech Republic', 'England', 'Germany', 'Hong Kong', 'Hungary', 'Netherlands', 'Portugal', 'Turkey'];

$mod="main";
if(isset($_GET['mod'])){
    $mod=$_GET['mod'];
}


$html="";
$head=file_get_contents("parts/head.html");
$content=file_get_contents("parts/".$mod.".html");
$footer=file_get_contents("parts/footer.html");

if($mod=='teams'){
    $teamTemplate=file_get_contents('parts/teamTemplate.html');
    $teamsContent='';
    foreach($teams as $k=>$team){
        $teampic=str_replace(' ', '_', $team);
        $teamsContent.=str_replace(array('$teampic', '$team'), array($teampic, $team), $teamTemplate);
    }
    //generate the content.
    $content=str_replace('<teams>', $teamsContent, $content);
}elseif($mod=='main'){
    $newsTemplate=file_get_contents('parts/newsTemplate.html');
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