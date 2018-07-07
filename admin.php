<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 2018-07-03
 * Time: 22:41
 */
DEFINE('ROOT', $_SERVER['DOCUMENT_ROOT'].'/ikfu21wkc/');
session_start();

if(!empty($_POST)){
    if(isset($_POST['login'])) {
        if ($_POST['username'] == 'media' && $_POST['password'] == 'Almafa') {
            $_SESSION['loggedin'] = true;
            echo 'sikeres login';
        } else {
            echo 'sikertelen belépés';
        }
    }
	else if(isset($_POST['upload'])){
		if(empty($_POST['title'])){
				echo 'A cím nem lehet üres<br/>';
		}else if(empty($_POST['content'])){
				echo 'A tartalom nem lehet üres<br/>';
		}else{
		    $datetime=new DateTime();
            $_POST['kep'] = 'pictureForNews/ball.jpg';
			if (!empty($_FILES) && $_FILES['picture']['error']<4) {
            //error_log(json_encode($_FILES));
            move_uploaded_file($_FILES['picture']['tmp_name'], ROOT.'pictureForNews/'.$_FILES['picture']['name']);
            $_POST['kep']='pictureForNews/'.$_FILES['picture']['name'];

            }
            file_put_contents(ROOT.'news/' .$datetime->format('Y-m-d-H-i-s').'_'.ekezetmentesites($_POST['title']).'.json', json_encode($_POST));
            echo 'sikeres feltöltés';
        }
    }
    else if(isset($_POST['sendResult'])){
    $json=file_get_contents('schedule/'.$_POST['date'].'.json');
    $array=json_decode($json);
    $matches=$array->matches;
    foreach($matches as $match){
            $match->A_score=$_POST[ekezetmentesites($match->A)];
            if(isset($match->B))
                $match->B_score=$_POST[ekezetmentesites($match->B)];
        }
        $array->matches=$matches;
    $json=json_encode($array);
    file_put_contents('schedule/'.$_POST['date'].'.json', $json);
//        print_r($_POST);
  //  print_r($matches);
    }
}
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
    $html=file_get_contents(ROOT."parts/upload.html");
    $newsListTemplate=file_get_contents(ROOT."parts/templates/newsListTemplate.html");
    $newsList='';
    $scanned_directory = array_diff(scandir(ROOT.'news/'), array('..', '.'));
    foreach($scanned_directory as $news){
        $newsArray=json_decode(file_get_contents(ROOT.'news/'.$news));
        $newsList.=str_replace(array('$title','$ekezetmentesTitle', '$filename'), array($newsArray->title, ekezetmentesites($newsArray->title), $news), $newsListTemplate);
    }
    $html=str_replace('<news>', $newsList, $html);
    //matches
    $date=new DateTime();
    $scanned_directory =array_diff(scandir('schedule/'), array('..','.'));
    if(!empty($_GET['day'])){
        $key=$_GET['day'];
    }
    else{
        $key=array_search($date->format('m.d').'.json', $scanned_directory);
    }
    if($key>2)
        $prev=$key-1;
    else
        $prev=2;
    if($key<9)
        $next=$key+1;
    else
        $next=9;
    $meccsek="";
    $json=file_get_contents('schedule/'.$scanned_directory[$key]);
    $array=json_decode($json);

    foreach($array->matches as $meccs){
        //print_r($meccs);
        $meccsTemplate=file_get_contents('parts/templates/scoreTemplate.html');
        if(empty($meccs->B)) $meccs->B='';
        if(empty($meccs->A_score)) $meccs->A_score='';
        if(empty($meccs->B_score)) $meccs->B_score='';
        $meccsek.=str_replace(array('$time', '$Score_A', '$Score_B', '$A', '$B', '$ekezetmentesA', '$ekezetmentesB'), array($meccs->time, $meccs->A_score, $meccs->B_score, $meccs->A, $meccs->B, ekezetmentesites($meccs->A), ekezetmentesites($meccs->B)), $meccsTemplate);
    }
    $html=str_replace(array('$prev', '$next', '$meccsek', '$date', '$day'), array($prev, $next, $meccsek, $array->date, $array->day), $html);
}else{
    $html=file_get_contents(ROOT."parts/login.html");
}
echo $html;

function ekezetmentesites($var){
     $search=array('á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű', ' ', "'");
    $replace=array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U', '_', "-");
    return str_replace($search, $replace, $var);
}
