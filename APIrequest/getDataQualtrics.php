
<?php
header('Content-Type:text/html; charset=UTF-8');
error_reporting(0);
$data = file_get_contents('https://survey.qualtrics.com/WRAPI/ControlPanel/api.php?API_SELECT=ControlPanel&Version=2.3&Request=getLegacyResponseData&User=impactanalytics&Token=KY36aUD5kwuLA4Nf0CZekXaNyTjHGk75y7wOJche&Format=HTML&SurveyID=SV_7QaO1i9Am2cngzP&Labels=1&ExportTags=0');

$dom = new domDocument;

@$dom->loadHTML('<?xml encoding="UTF-8">' . $data);

$tables = $dom->getElementsByTagName('table');
if ($tables->length == 0) {
  throw new Exception('No tables found');
}
$rows = $tables->item(0)->getElementsByTagName('tr');
$head = $rows->item(0)->getElementsByTagName('th');



$cont = 0;
$char = 0;
$charpos = array();
$charpos4 = array();
$charpos5 = array();
$charpos6 = array();
$safe = array();

foreach($head as $names) {
  if($cont > 0) {
    $strtemp = $names->nodeValue;
    $strtemp = preg_replace( "/\r|\n/", "", $strtemp);
    if(preg_match('/(.*Pre.*High.*)|(.*Post.*High.*)/i', $strtemp)) {
      $charpos[] = $char;
    }
    else if(preg_match('/Group/', $strtemp)) {
      $groupIndex = array($char);
    }
    else if(preg_match('/.*How many years have you been coming to After Schools Academy.*/i', $strtemp)) {
      $charpos4[] = $char;
    }
    else if (preg_match('/(.*High.*)/i', $strtemp)) {
      $charpos5[] = $char;
      $safe[] = $strtemp;
    } else if (preg_match('/What grade are you in/i', $strtemp)) {
      $charpos6[] = $char;

    }
  }
  $char++;
  $cont++;
}



$array = array();
$keys = array();
$array2 = array();
$array3 = array();
$array4 = array();
$array5 = array();
$cont2 = 0;

foreach($rows as $row ) {
  $temp = $row->getElementsByTagName('td');
  $tds = array();
  $tds3[] = array();
  $tds4 = array();
  $tds5[] = array();
  $keys[] = $temp->item(0)->nodeValue;
  foreach( $temp as $td ) {
    $x = $td->nodeValue;
    if(in_array($cont2, $charpos)) {
      if($x != "1")
        $x = "0";
      $tds[] = (int) $x;
    }
    if(in_array($cont2, $groupIndex)) {
      $tds[] = $x;
    }
    if($cont2 == $charpos4[0]) {
      $tds3[] = $x;
    }
    if(in_array($cont2, $charpos5)) {
       if($x != "1")
        $x = "0";
       $tds4[] = (int) $x;
    }
    if(in_array($cont2, $charpos6)) {
      if(preg_match('/(Kindergarten)|(1st grade)|(2nd grade)|(3rd grade)|(4th grade)|(5th grade)|(6th grade)/i', $x)){
        $tds5[] = 'Elementary';
      } else if (preg_match('/(7th grade)|(8th grade)|(9th grade)/i', $x)) {
        $tds5[] = 'Middle/Jr. High';
      } else {
        $tds5[] = 'High School';
      }
    }
    $cont2++;
  }
  $array[] = $tds;
  $array3[] = end($tds3);
  $array4[] = $tds4;
  $array5[] = end($tds5);
  $cont2 = 0;
}
array_shift($array);
array_shift($keys);
$result = array_combine($keys, $array);


array_shift($array3);

$result3 = array_combine($keys, $array3);

array_shift($array4);



$result4 = array_combine($keys, $array4);

array_shift($array5);

$result5 = array_combine($keys, $array5);




$groupId = array();
$groupCount = 0;
foreach ($result as $rows) {
  $cont = 0;
  foreach($rows as $cell) {
    if (preg_match('/Older/i', $cell)) {
      $groupId[] = "Older";
      $groupCount++;
    } else if(preg_match('/Younger/i', $cell)){
      $groupId[] = "Younger";
    }
    $cont ++;
  }
}


$groupCountYoung = count($result) - $groupCount;



$current = 0;
$cont = 0;
$cells = array();

foreach($result as $array){

  foreach(array_keys($array) as $cell){

    if(preg_match('/Older/i', $groupId[$current]) & $cell != 8) {
      $cells[$cell] += $array[$cell];
    }
  }
  $current++;
}



$cellsPerPre = array();
$cellsPerPost = array();
$cont = 0;
foreach($cells as $cell) {
  if($cont % 2 == 0) {
    $cellsPerPre[] = $cell/$groupCount;
  }
  else {
    $cellsPerPost[] = $cell/$groupCount;
  }
  $cont++;
}


$current = 0;
$cont = 0;
$cellsYoung = array();
foreach($result as $array){
  // loop through each array
  foreach(array_keys($array) as $cell){
    if(preg_match('/Younger/i', $groupId[$current]) & $cell != 8) {
      $cellsYoung[$cell] += $array[$cell];
    }
  }
  $current++;
}

$cellsPerYoungPre = array();
$cellsPerYoungPost = array();
$cont = 0;
foreach($cellsYoung as $cell) {
  if($cont % 2 == 0) {
    $cellsPerYoungPre[] = $cell/$groupCountYoung;
  }
  else {
    $cellsPerYoungPost[] = $cell/$groupCountYoung;
  }
  $cont++;
}


$outCat = array(0 => 'Character', 1 => 'Social Skills', 2 => 'Learning', 3 => 'Creativity');


$array = array();
$array['cols'][] = array('type' => 'string', 'label' => 'Outcome categories');
$array['cols'][] = array('type' => 'number', 'label' => 'Before DreamCamp this summer');
$array['cols'][] = array('type' => 'number', 'label' => 'After DreamCamp this summer');

$array2 = array();
$array2['cols'][] = array('type' => 'string', 'label' => 'Outcome categories');
$array2['cols'][] = array('type' => 'number', 'label' => 'Before DreamCamp this summer');
$array2['cols'][] = array('type' => 'number', 'label' => 'After DreamCamp this summer');



for ($j = 0; $j < 4; $j++) {
  $array['rows'][]['c'] = array(array('v' => $outCat[$j]), array('v' => $cellsPerYoungPre[$j]), array('v' => $cellsPerYoungPost[$j]));
}

for ($k = 0; $k < 4; $k++) {
  $array2['rows'][]['c'] = array(array('v' => $outCat[$k]), array('v' => $cellsPerPre[$k]), array('v' => $cellsPerPost[$k]));
}


$json_array =  json_encode($array);
$json_array2 =  json_encode($array2);

file_put_contents("json/slide31.json", $json_array);
file_put_contents("json/slide32.json", $json_array2);



$array3 = array();
$array3['cols'][] = array('type' => 'string', 'label' => 'Years attending ESF Dream Camp');
$array3['cols'][] = array('type' => 'number', 'label' => 'Years');





$freqs = array_count_values($result3);
ksort($freqs);

$freqsper = array();
foreach($freqs as $freq) {
  $freqsper[] = $freq/array_sum($freqs);
}


$max = count($freqsper);

for ($j = 0; $j < $max; $j++) {
  $temp = (string) array_keys($freqsper)[$j] + 1;
  $array3['rows'][]['c'] = array(array('v' => $temp), array('v' => $freqsper[$j]));
}

$json_array3 =  json_encode($array3);
file_put_contents("json/slide27.json", $json_array3);





$cont = 0;
$exps = array();
foreach($result4 as $array){
  foreach(array_keys($array) as $cell){
      $exps[$cell] += $array[$cell];
  }
}

$expsperschool = array();
$cont = 0;
foreach($exps as $exp) {
  if($cont % 2 == 0){
    $expsperschool[] = $exp/count($result4);
  }
  $cont++;
}

$expsperafter = array();
$cont = 0;
foreach($exps as $exp) {
  if($cont % 2 != 0){
    $expsperafter[] = $exp/count($result4);
  }
  $cont++;
}


//$outCat = array(0 => 'Students provided safe environment  to participate in camp activities', 1 => 'Students experience supportive \n relationships from other students  and counselors', 2 => 'Students involved in decision-making \n and leadership', 3 => 'Students provided support /  oportunities for skill development');

$outCat = array(0 => 'Safe Environment', 1 => 'Supportive Relationships', 2 => 'Leadership', 3 => 'Skill Development');

$array4 = array();
$array4['cols'][] = array('type' => 'string', 'label' => 'Experience');
$array4['cols'][] = array('type' => 'number', 'label' => 'School');
$array4['cols'][] = array('type' => 'number', 'label' => 'DreamCamp');


$max = count($expsperschool);

for ($j = 0; $j < $max; $j++) {
  $array4['rows'][]['c'] = array(array('v' => $outCat[$j]), array('v' => $expsperschool[$j]), array('v' => $expsperafter[$j]));
}

$json_array4 =  json_encode($array4);
file_put_contents("json/slide29.json", $json_array4);


$freqs = array_count_values($result5);
ksort($freqs);

$freqsper = array();
foreach($freqs as $freq) {
  $freqsper[] = $freq/array_sum($freqs);
}

$freqsper = array_combine(array_keys($freqs), $freqsper);

$array5 = array();
$array5['cols'][] = array('type' => 'string', 'label' => 'Education');
$array5['cols'][] = array('type' => 'number', 'label' => 'Frequence');

$max = count($freqsper);

for ($j = 0; $j < $max; $j++) {
  $temp = (string) array_keys($freqsper)[$j];
  $array5['rows'][]['c'] = array(array('v' => $temp), array('v' => $freqsper[$temp]));
}

$json_array5 =  json_encode($array5);
file_put_contents("json/slide26.json", $json_array5);


?>


