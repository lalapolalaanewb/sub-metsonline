<?php

//use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use kartik\field\FieldRange;
use \kartik\widgets\Spinner;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveField;
// use app\assets\VisualizeAsset;
//
// VisualizeAsset::register($this);

\app\assets\VisualizeAsset::register($this);

?>


<div class="trade-tradesearch">
  <div><br><br><br><br><br></div>
  <div id="containerYear" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
  <div><br><br><br><br><br></div>
  <?php

  // echo $codeDescImport[0]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[1]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[2]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[3]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[4]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[5]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[6]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[7]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[8]['TotalDataTrade'].'<br>';
  // echo $codeDescImport[9]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[0]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[1]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[2]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[3]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[4]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[5]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[6]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[7]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[8]['TotalDataTrade'].'<br>';
  // echo $codeDescExport[9]['TotalDataTrade'].'<br>';

  ?>

</div><!-- trade-tradesearch -->

<!-- style="overflow-y: scroll; height:400px;" -->
<?php
// echo $dataTradeTotalImport[0]['data_year'].' '.$dataTradeTotalImport[0]['TotalDataTrade'].'<br>';
// echo $dataTradeTotalImport[1]['data_year'].' '.$dataTradeTotalImport[1]['TotalDataTrade'].'<br>';
// echo $dataTradeTotalImport[2]['data_year'].' '.$dataTradeTotalImport[2]['TotalDataTrade'].'<br>';
// print_r($dataTradeTotalImport);
// echo '<br>'.$countryName;
//
// echo "\n";
// echo gettype($dataTradeTotalImport[2]['data_year'])."<br>";
// echo gettype($countryName)."<br>";
// echo "\n";

$data2script = array(
  'dataYear' => $year,
  'titleName' => $titleName,
  // 'ic49' => $codeDescImport[0]['TotalDataTrade'],
  // 'ic50' => $codeDescImport[1]['TotalDataTrade'],
  // 'ic51' => $codeDescImport[2]['TotalDataTrade'],
  // 'ic52' => $codeDescImport[3]['TotalDataTrade'],
  // 'ic53' => $codeDescImport[4]['TotalDataTrade'],
  // 'ic54' => $codeDescImport[5]['TotalDataTrade'],
  // 'ic55' => $codeDescImport[6]['TotalDataTrade'],
  // 'ic56' => $codeDescImport[7]['TotalDataTrade'],
  // 'ic57' => $codeDescImport[8]['TotalDataTrade'],
  // 'ic58' => $codeDescImport[9]['TotalDataTrade'],
  // 'ec49' => $codeDescExport[0]['TotalDataTrade'],
  // 'ec50' => $codeDescExport[1]['TotalDataTrade'],
  // 'ec51' => $codeDescExport[2]['TotalDataTrade'],
  // 'ec52' => $codeDescExport[3]['TotalDataTrade'],
  // 'ec53' => $codeDescExport[4]['TotalDataTrade'],
  // 'ec54' => $codeDescExport[5]['TotalDataTrade'],
  // 'ec55' => $codeDescExport[6]['TotalDataTrade'],
  // 'ec56' => $codeDescExport[7]['TotalDataTrade'],
  // 'ec57' => $codeDescExport[8]['TotalDataTrade'],
  // 'ec58' => $codeDescExport[9]['TotalDataTrade'],
);
//
// $data2script2 = array(
//   intval($dataTradeTotalImport[0]['TotalDataTrade']),
//   intval($dataTradeTotalImport[1]['TotalDataTrade']),
//   intval($dataTradeTotalImport[2]['TotalDataTrade'])
// );
//
// echo 'lalalallala : '.$data2script['sa'];

// $try = 'abcdefg';
// $tryyy = 'hujanturun lopong';
//
// if (substr($try, 0, 3) == 'abc'){
//   echo "<br>ABC LAHHH</br>";
//   echo "<br>".substr($tryyy, 3, strlen($tryyy))."</br>";
//   echo strlen($try);
// }else{
//   echo "<br>BUKAN ABC LAHHH</br>";
// }

echo "Country : ".$titleName."<br>";
echo "Year    : ".$year."<br>";
print_r('<pre>');
print_r($codeDescImport);
print_r('</pre>');
print_r('<pre>');
print_r($codeDescExport);
print_r('</pre>');

echo '<script>';
echo 'var dataTitle = ' . json_encode($data2script) . ';';
echo 'var dataPassedImport = ' . json_encode($codeDescImport) . ';';
echo 'var dataPassedExport = ' . json_encode($codeDescExport) . ';';

// echo 'var dataPassed2 = ' . json_encode($data2script2) . ';';
// echo 'var titleShow = '.$data2script['countryName'].';';
// echo 'var i13 ='.$dataTradeTotalImport[0]['TotalDataTrade'].';';
// echo 'var i14 ='.$dataTradeTotalImport[1]['TotalDataTrade'].';';
// echo 'var i15 ='.$dataTradeTotalImport[2]['TotalDataTrade'].';';
// echo 'var e14 ='.$dataTradeTotalExport[0]['TotalDataTrade'].';';
// echo 'alert(ff);';
echo '</script>';

?>
