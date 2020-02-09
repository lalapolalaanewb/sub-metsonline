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

$this->registerCss("

.bbold {
    font-weight:bold;
}

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

#paddingTitle {
  margin-left: 111px;
  margin-top: -15px;
  margin-bottom: 15px;
}

");
?>


<div class="trade-tradesearch">
<!-- <br> -->
  <!-- Choose Chart :&nbsp;&nbsp;&nbsp;&nbsp;

  <label><input id="rdb1" type="radio" name="toggler" value="1" />&nbsp;&nbsp; Exports, Imports, Total Trade and Trade Balance </label>&nbsp;&nbsp;&nbsp;&nbsp;
  <label><input id="rdb2" type="radio" name="toggler" value="2" />&nbsp;&nbsp; Exports and Imports By SITC-1-Digit </label> -->

  <!-- <div id="blk-1" style="display:none">
      first div text
  </div>
  <div id="blk-2" style="display:none">
      div number 2 text
  </div> -->

<!-- <div class="rad" id="blk-1" style="display:none"> -->
    <?php $form = ActiveForm::begin([
        //'action' => \yii\helpers\Url::to(['/visualize/displaycountrychart'])
        'id'=>'graform'
]);?>

<!-- <h5><strong>Exports and Imports by Country of Origin & Destination, Geographical and Economic Grouping<strong></h5> -->
<!-- <label class="col-sm-12" id="paddingTitle">Exports and Imports by Country of Origin & Destination, Geographical and Economic Grouping</label> -->
<label class="col-sm-12" id="paddingTitle">EXPORTS AND IMPORTS BY COUNTRY OF ORIGIN & DESTINATION, GEOGRAPHICAL AND ECONOMIC GROUPING</label>
<!-- <br> -->
<div class="form-group">
  <!-- <br> -->
  <label class="control-label col-sm-3" id="paddingMa">PARTNER COUNTRY</label>
  <div class="col-sm-9">
    <select name="countrySelection" class="form-control select2" style="width: 100%;">
      <option class="bbold" value="all"><b>All Countries</b></option>
      <?php
      for($y=0; $y<count($countryInfo); $y++)
      {?>
        <option value="<?=$countryInfo[$y]['idcountry']?>"><?=$countryInfo[$y]['country_descriptionBI']?></option>
      <?php
      }
      ?>
      <option class="bbold" value="groupGeo">Geographical Grouping</option>
      <?php
      for($z=0; $z<count($datagroupGEO); $z++)
      {?>
        <option value="geo<?=$datagroupGEO[$z]['code_group']?>">&nbsp;&nbsp;&nbsp;<?=$datagroupGEO[$z]['group_descBI']?></option>
      <?php
      }
      ?>
      <option class="bbold" value="groupEco">Economic Grouping</option>
      <?php
      for($a=0; $a<count($datagroupECO); $a++)
      {?>
        <option value="eco<?=$datagroupECO[$a]['code_group']?>">&nbsp;&nbsp;&nbsp;<?=$datagroupECO[$a]['group_descBI']?></option>
      <?php
      }
      ?>
    </select>
  </div>
</div>


        <div class="form-group">
            <label class="col-md-3 control-label" for="singlebutton"></label>
           <div class="col-md-4">
             <button type="button" id="greenButton" class="btn btn-default  btngraf">Search</button>
               </div>
        </div>





    <?php ActiveForm::end(); ?>
<!-- </div> -->

</div><!-- trade-tradesearch -->

<div class='resultdatageo'>


</div>
<?php

$urproudcode= Url::to(['visualize/displaycountrychart']);
$this->registerJs("
$(function(){

$('.btngraf').click(function(){



        var dataform = $('#graform').serializeArray();
    $.ajax({
                                     'url':'".$urproudcode."',
                                     'data':dataform,
                                     'method':'POST',
                                                                    beforeSend: function (xhr) {
                $.blockUI({
                    message: 'Processing...',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
            },
                                     'success':function(data){

                                        $('.resultdatageo').html('');
                                        if(data == 'false'){
                                               $.unblockUI();


                                        }else{

                                               $.unblockUI();
                                            $('.resultdatageo').html(data);




                                        }


                                     }

                                });




});

  });


");
?>




<!-- style="overflow-y: scroll; height:400px;" -->
