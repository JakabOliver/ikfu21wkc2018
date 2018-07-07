<?php
DEFINE('ROOT', $_SERVER['DOCUMENT_ROOT'].'/ikfu21wkc/');
$teams=array('Hungary', 'Netherlands','Czech Republic', 'England',  'Chinese Taipei','Belgium', 'China',  'Germany', 'Hong Kong China','Portugal', 'Turkey');

$mod="main";
if(isset($_GET['mod'])) {
    $mod = $_GET['mod'];
}

$html="";
$head=file_get_contents(ROOT."parts/head.html");
$content=file_get_contents(ROOT."parts/".$mod.".html");
$footer=file_get_contents(ROOT."parts/footer.html");

if($mod=='teams'){
    $teamTemplate=file_get_contents('parts/templates/teamTemplate.html');
    $teamsContent='';
    foreach($teams as $k=>$team){
        $teamDescription=file_get_contents(ROOT.'parts/teams/'.ekezetmentesites($team).'.txt');
        $teampic=str_replace(' ', '_', $team);
        $teamsContent.=str_replace(array('$teampic', '$team', '<teambembers>'), array($teampic, $team, $teamDescription), $teamTemplate);
    }
    //generate the content.
    $content=str_replace('<teams>', $teamsContent, $content);
}else if($mod=='main'){
    $newsTemplate=file_get_contents('parts/templates/newsTemplate.html');
    $newsContent='';
    $scanned_directory = array_diff(scandir('news/',1), array('..', '.'));
    foreach($scanned_directory as $news){
        $newsArray=json_decode(file_get_contents('news/'.$news));
        $newsContent.=str_replace(array('$title', '$content', '$ekezetmentestitle', '$kep'), array($newsArray->title, $newsArray->content, ekezetmentesites($newsArray->title), $newsArray->kep), $newsTemplate);
    }
    //beolvasás
    $content=str_replace('<news>', $newsContent, $content);
}else if($mod=='program'){
    $scanned_directory =array_diff(scandir('schedule/'), array('..','.'));
    $days='';
    foreach($scanned_directory as $k=>$day){
        $dayTemplate=file_get_contents('parts/templates/dayTemplate.html');
        $matchDay=json_decode(file_get_contents('schedule/'.$day));
        $dayContent='';
        $dayTemplate=str_replace('$day', $matchDay->day, $dayTemplate);
        //Egy nap!
        $matches='';
        foreach($matchDay->matches as $match){
            $matchTemplate=file_get_contents('parts/templates/matchTemplate.html');
            if(empty($match->B)) $match->B='';
            if(!empty($match->A_score)) $match->A.=':'.$match->A_score;
            if(!empty($match->B_score)) $match->B.=':'.$match->B_score;
            $matches.=str_replace(array('$time', '$A', '$B'), array($match->time, $match->A, $match->B), $matchTemplate);
        }
        $dayContent=str_replace('<matches>', $matches, $dayTemplate);
        if($k%2==0){
            $days.='</section><section class="row">';
        }
        $days.=$dayContent;
    }
    $content=str_replace('<days>', $days, $content);
}


$html=$head.$content.$footer;
echo $html;
function ekezetmentesites($var){
    $search=array('á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű', ' ');
    $replace=array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U', '_');
    return str_replace($search, $replace, $var);
}