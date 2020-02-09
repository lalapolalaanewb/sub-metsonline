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

?>


<div class="trade-tradesearch">

    <?php $form = ActiveForm::begin([
        //'action' => \yii\helpers\Url::to(['/visualize/displaycountrychart'])
        'id'=>'graform'
]); ?>
<div class="form-group">
  <label>Country</label>
  <select name="yearSelection" class="form-control select2" style="width: 100%;">
    <option value="2013">2013</option>
    <option value="2014">2014</option>
  </select>
</div>


        <div class="form-group">
            <label class="col-md-3 control-label" for="singlebutton"></label>
           <div class="col-md-4">
             <button type="button" class="btn btn-default  btngraf">Submit</button>
               </div>
        </div>





    <?php ActiveForm::end(); ?>

</div><!-- trade-tradesearch -->

<div class='resultdatageo'>


</div>
<?php

$urproudcode= Url::to(['visualize/displayyearchart']);
$this->registerJs("
$(function(){

    $('.timeframeshow').hide('');
     $('.timeframeshowmoth').hide('');
    $('.loadingpopup').hide('');

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
