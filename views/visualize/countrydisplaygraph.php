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

$this->registerCss("

#greenButton {
  color: #333;
background-color: #e6e6e6;
border-color: #adadad;
  margin-top: 7px;
}

#greenButton:hover {
  outline: 0;
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
}

#paddingMa {
  padding-top: 7px;
  text-align: right;
}

");

?>


<div class="trade-tradesearch">

  <div><br><br><br><br><br></div>
  <?php echo "<br><br>"; print_r('<pre>'); ?>
  <div id="containerImport" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  <!-- <div><br><br><br><br><br></div> -->
  <div id="containerExport" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  <?php print_r('</pre>'); ?>
  <div><br><br></div>

  <!-- display dropdown year START -->
  <?php $form = ActiveForm::begin([
      //'action' => \yii\helpers\Url::to(['/visualize/displaycountrychart'])
      'id'=>'graform2'
]);?>
<div class="form-group">
<br>
<label class="control-label col-sm-3" id="paddingMa">YEAR</label>
<div class="col-sm-9">
  <select id="select_box_id" class="form-control select2" style="width: 100%;">
  <?php
  for($b=0; $b<count($codeDescYear); $b++)
  {?>
    <option value="<?=$codeDescYear[$b]['data_year']?>"><?=$codeDescYear[$b]['data_year']?></option>
  <?php
  }
  ?>
  </select>
</div>
</div>
<div class="form-group">
    <!-- <label class="col-md-3 control-label" for="singlebutton"></label> -->
   <!-- <div class="col-md-4"> -->
   <?php
  //  print_r('<pre>');
  //  print_r($countryPicked);
  //  print_r('</pre>');
   ?>
     <input id="countryPicked" type="hidden" name="countryPicked" value="<?=$countryPicked?>"/>
     <input id="csrf" type="hidden" name="_csrf" value="<?=$csrf?>"></input>
       <!-- </div> -->
</div>

      <!-- <div class="form-group">
          <label class="col-md-3 control-label" for="singlebutton"></label>
         <div class="col-md-4">
           <button type="button" id="greenButton" class="btn btn-default  btngraf2">Search</button>
             </div>
      </div> -->





  <?php ActiveForm::end(); ?>
  <!-- display dropdown year END -->


</div><!-- trade-tradesearch -->

<div class='resultdatageo'>
  <div><br><br></div>
  <?php echo "<br><br>"; print_r('<pre>'); ?>
  <div id="containerYear" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
  <?php print_r('</pre>'); ?>
  <div><br><br></div>

</div>

<?php
// echo $dataTradeTotalImport[0]['data_year'].' '.$dataTradeTotalImport[0]['TotalDataTrade'].'<br>';
// echo $dataTradeTotalImport[1]['data_year'].' '.$dataTradeTotalImport[1]['TotalDataTrade'].'<br>';
// echo $dataTradeTotalImport[2]['data_year'].' '.$dataTradeTotalImport[2]['TotalDataTrade'].'<br>';
// print_r($dataTradeTotalImport);
// echo $dataTradeTotalImport;
// echo '<br>'.$countryName;
//
// echo "\n";
// echo gettype($dataTradeTotalImport[2]['data_year'])."<br>";
// echo gettype($countryName)."<br>";
// echo "\n";

$urll = Url::to(['visualize/displayyearchart']);

$data2script = array(
  'titleName' => $titleName,
  'dataYear' => $year,
  'url' => $urll,
    // 'i13' => $dataTradeTotalImport[0]['TotalDataTrade'],
    // 'i14' => $dataTradeTotalImport[1]['TotalDataTrade'],
    // 'i15' => $dataTradeTotalImport[2]['TotalDataTrade'],
    // 'e14' => $dataTradeTotalExport[0]['TotalDataTrade'],
);

// echo $titleName;
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

// echo 'sasasasasasasas: '. $countryPicked;

echo '<script>';
echo 'var dataTitle = ' . json_encode($data2script) . ';';
echo 'var dataPassedImport = ' . json_encode($dataTradeTotalImport) . ';';
echo 'var dataPassedExport = ' . json_encode($dataTradeTotalExport) . ';';
echo 'var dataPassedImport2 = ' . json_encode($codeDescImport) . ';';
echo 'var dataPassedExport2 = ' . json_encode($codeDescExport) . ';';

// echo 'var dataPassed2 = ' . json_encode($data2script2) . ';';
// echo 'var titleShow = '.$data2script['countryName'].';';
// echo 'var i13 ='.$dataTradeTotalImport[0]['TotalDataTrade'].';';
// echo 'var i14 ='.$dataTradeTotalImport[1]['TotalDataTrade'].';';
// echo 'var i15 ='.$dataTradeTotalImport[2]['TotalDataTrade'].';';
// echo 'var e14 ='.$dataTradeTotalExport[0]['TotalDataTrade'].';';
// echo 'alert(ff);';
echo '</script>';

?>

<!-- style="overflow-y: scroll; height:400px;" -->
