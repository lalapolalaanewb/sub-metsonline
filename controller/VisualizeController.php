<?php

namespace app\controllers;
use app\models\CodeDesc;
use app\models\CodeTrade;
use app\models\CodeType;
use app\models\Country;
use app\models\DataDetail;
use app\models\DataTrade;
use app\models\DescRelation;
use app\models\Embargo;
use app\models\GroupCountry;
use app\models\GroupType;
use app\models\GroupTypeCountry;
use app\models\Trade;
use app\models\TradeNumber;
use app\models\TradeExcel;
use kartik\depdrop\DepDrop;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
// use yii\web\Response;
use yii\helpers\Url;


use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\JsExpression;
use kartik\form\ActiveField;


class VisualizeController extends \yii\web\Controller
{

public $layout = 'main_guest';


private $esHost, $esPort;

    public function init(){
        //setting esHost & esPort in config/params.php
        //$this->esHost = Yii::$app->params['esHost'];
        //$this->esPort = Yii::$app->params['esPort'];
    }



 /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'geographical-group'=> ['POST'],
                    'geo-groupresult'=> ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {


        $this->tradetab();



    }


   /********************************************************************************************** *
     * Description       : control for search Product code.
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/


     public function actionProductCode()
    {


        $listcodeobj=CodeType::find()->codetypeselect()->all();

        $model = new Trade(['scenario' => 'productcode']);




        $listcountry=Country::find()->asArray()->all();

        $listcode=ArrayHelper::map($listcodeobj,'idcode_type',function($code){
         return   $code['code_typedesc'];
        });

            $d['model']=$model;
            $d['listcode']=$listcode;
            $d['listcountry']=$listcountry;
            $request = Yii::$app->request;
            $datapost = $request->post('Trade');


                $html= $this->renderAjax('produccode',$d);
                return Json::encode($html);

    }


    /********************************************************************************************** *
     * Description      : control for get  Product code for search product Code
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/


    public function actionGetCode(){


        $request = Yii::$app->request;
        $typedigit = $request->post('typedigit');
        $search = $request->post('q');

        if (!empty($typedigit)) {

            $query = CodeDesc::find()->select("DISTINCT CONCAT([[trade_number]].[[trade_number]],[[code_desc]].[[desc_code]]) AS text,trade_number.trade_number as id")
                ->joinWith(['descRelations','descRelations.idtradeNumber']);


        $query->andFilterWhere([
            'idcode_type' => $typedigit,
        ]);

        $query->andFilterWhere(['or',['like', 'desc_code', $search],['like', 'trade_number.trade_number', $search]]);

        $query->andFilterWhere(['not', ['data_trade_id' => null]]);

        $list=$query->asArray()->all();


            $out['results'] = array_values($list);
        }
        else {
            $out['results'] = ['id' => '', 'text' => 'you have to Selecet type Code'];
        }
        return Json::encode($out);



}
        /********************************************************************************************** *
     * Description      : control for get  Code Range code for search
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/

     public function actionCoderange(){
            $request = Yii::$app->request;
            $id = $request->post('id');


    $CodeType= CodeType::find()->select('codetype_start,codetype_end')->where(['idcode_type'=>$id])->one();


       if(!empty($CodeType->codetype_end)){


       $CodeType=$CodeType;
       $model = new Trade();

 $rangecodebox= FieldRange::widget([
                                   // 'form' => $form,
                                    'model' => $model,
                                    'label' => 'CODE RANGE',
                                    'labelOptions'=>['class'=>'col-sm-3'],
                                    'widgetContainer'=>['class'=>'col-sm-9'],
                                    'errorContainer'=>['class'=>'col-sm-offset-3 col-sm-9'],
                                    'attribute1' => 'rangecode1',
                                    'attribute2' => 'rangecode2',
                                    'value1'  => '1',
                                    'value2' => '1',
                                    'options1'=>[
                                    'type'=>"number",
                                    'mix'=>$CodeType->codetype_start,
                                    'max'=>$CodeType->codetype_end,
                                    'value'=>$CodeType->codetype_start
                                    ],
                                    'options2'=>[
                                    'type'=>"number",
                                    'mix'=>$CodeType->codetype_start,
                                    'max'=>$CodeType->codetype_end,
                                    'value'=>$CodeType->codetype_end
                                    ],
                                      'separator' => 'TO',


                                    'type' => FieldRange::INPUT_HTML5_INPUT,
                                ]);

       echo  $rangecodebox;

       }else{


        $CodeType=false;
       }








}



    /********************************************************************************************** *
     * Description      : control for get  Timeframerange  for search
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/


public function actionTimeframerange(){

//  $request = Yii::$app->request;
// $typeframe = $request->post('id');
// $typedigitid = $request->post('typedigitid');
//  $model = new Trade();




//  $CodeType= CodeType::find()->select('data_detail.data_year')
//      ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])
//      ->where(['code_type.idcode_type'=>$typedigitid])->groupby(['data_detail.data_year'])->asArray()->all();

// $listyear=ArrayHelper::map($CodeType,'data_year',function($code){
//  return   $code['data_year'];
// });

// $listclear=array_filter( $listyear);

// print_r($listclear);
//  $form = ActiveForm::begin([
//         'id' => 'tradesearch',
//     'type'=>ActiveForm::TYPE_HORIZONTAL,
//     'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
// ]);

// echo FieldRange::widget([
//     'form' => $form,
//     'model' => $model,
//       'label' => 'CODE RANGE',
//       'labelOptions'=>['class'=>'col-sm-3'],
//      'widgetContainer'=>['class'=>'col-sm-9'],
// 'errorContainer'=>['class'=>'col-sm-offset-3 col-sm-9'],
//     'attribute1' => 'timeframe',
//     'attribute2' => 'timeframe2',
//      'separator' => 'TO',
//    // 'type' => FieldRange::INPUT_DROPDOWN_LIST,
//   'type' => FieldRange::INPUT_WIDGET,
//     'widgetClass' => Depdrop::classname(),
//     'widgetOptions1' => [
//      'options' => ['id'=>'year-id','placeholder' => 'Select ...'],
//      'data'=> $listclear,
//        'pluginOptions'=>[
//          'depends'=>['digitid'],
//          'placeholder' => 'Select...',
//          'url' => Url::to(['/trade/murangev2'])
//      ]
//      ],
//      'widgetOptions2' => [
//      'options' => ['id'=>'subcat-id','placeholder' => 'Select ...'],
//        'pluginOptions'=>[
//          'depends'=>['trade-timeframe'],
//          'placeholder' => 'Select...',
//          'url' => Url::to(['/trade/murangev2'])
//      ]
//     ],

// ]);




}

    /********************************************************************************************** *
     * Description      : control for get  Yearrange1  for search
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/


public function actionYearrange1(){



 $request = Yii::$app->request;

$typedigitid = $request->post('depdrop_all_params');


      $out2 = [];
    if (isset($typedigitid)) {
        $parents = $request->post('depdrop_all_params');



        if ($parents != null) {

    //.codeTrades.iddataTrade.dataDetails

         $embigo=Trade::EmbigoCurrent();
             $out= DataDetail::find()
     ->select('data_detail.data_year')
     ->joinWith(['codeTrades.idtradeNumber.idcodeType'])
     ->where(['code_type.idcode_type'=>$parents['digitid']])
     ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
     ->andFilterWhere([ 'data_detail.status'=>1 ])
      ->groupby(['data_detail.data_year'])
     ->orderBy(['data_detail.data_year'=> SORT_ASC])
     ->all();





            foreach ($out as $key => $value) {
                if(!empty($value->data_year)){
                    $out2[]=array('id'=>$value->data_year,'name'=>$value->data_year);
                }

            }





             //  $out2[]=array('id'=>2012,'name'=>'2012');





            echo Json::encode(['output'=>$out2, 'selected'=>'']);
            return;
        }
    }
    echo Json::encode(['output'=>'', 'selected'=>'']);


     }

/********************************************************************************************** *
     * Description      : control for get  Yearrange2  for search
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
     **********************************************************************************************/

     public function actionYearrange2(){



         $request = Yii::$app->request;

        $year = $request->post('depdrop_all_params');


        $traderangeyear=$year['trade-rangeyear'];
        $digitid=$year['digitid'];

$embigo=Trade::EmbigoCurrent();



            $yearmax= DataDetail::find()
             ->select('data_detail.data_year')
             ->joinWith(['codeTrades.idtradeNumber.idcodeType'])
             ->where(['code_type.idcode_type'=>$digitid])
             ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
             ->groupby(['data_detail.data_year'])
             ->orderBy(['data_detail.data_year'=> SORT_DESC])
             ->one();




$parents = $request->post('depdrop_parents');


      $out = [];
    if (isset($parents)) {

        if ($parents[0] != null) {


    $newyear =$year['trade-rangeyear'];


    $year6 = $newyear+6;
    $yearlimit=$yearmax->data_year;


    if($year6 >= $yearlimit){
        $yearfrom=$yearlimit;
    }else{
   $yearfrom=$year6;
    }

      $bill=0;
    for ($z=$newyear; $z <= $yearfrom  ; $z++) {
        $bill++;

            $out2[]=array('id'=>$z,'name'=>$z);

              if($bill == 6){
                break;
              }



    }



             echo Json::encode(['output'=>$out2, 'selected'=>'']);
         }

    }else{
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }



     }

 /********************************************************************************************** *
     * Description      : control for get  Timeframerangev2  for search
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


    public function actionTimeframerangev2(){
// $request = Yii::$app->request;
// $typeframe = $request->post('id');
// $typedigitid = $request->post('typedigitid');

// // print_r($_POST);


//      $CodeType= CodeType::find()->select('data_detail.data_year')
//      ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])
//      ->where(['code_type.idcode_type'=>$typedigitid])->groupby(['data_detail.data_year'])->asArray()->all();


// $listyear=ArrayHelper::map($CodeType,'data_year',function($code){
//  return   $code['data_year'];
// });

//  $form = ActiveForm::begin([
//         'id' => 'tradesearch',
//     'type'=>ActiveForm::TYPE_HORIZONTAL,
//     'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
// ]);


//  $model = new Trade();

// $listclear=array_filter($listyear);



// if($typeframe=='year'){


// echo $form->field($model, 'rangeyear', [

//     'addon' => [
//         'append' => ['content' => 'TO'],
//         'groupOptions' => ['class'=>'input-group-md col-sm-9'],
//         'contentAfter' => '

//         <select name="range2" class="form-control col-sm-12"  id="frame-to">
//         <option value=""> Select...</opntion>
//         </select>'
//     ]
// ])->dropDownList( $listclear,['prompt'=>'Select...', 'onchange'=>"
//                           $('#frame-to').html('');
//                            var year=$(this).val();
//                            var rangey=  $(this).val();


//     var rangese;

//     var newyear =parseInt(rangey);

//    var year6 = newyear+6;

//     if(year6 >= 2015){
//         var yearfrom=2015;
//     }else{
//    var yearfrom=year6;
//     }


//     for (z=newyear; z <= yearfrom  ; z++) {

//       $('#frame-to').append('<option value='+z+' >'+ z +'</option>');

//     }



//                                 ",

// ]);

// }else{

//     echo $form->field($model, 'rangeyear')->dropDownList(
//                                 $listclear,
//                                 ['prompt'=>'Select...'
//                             ]);


// }



}



 /********************************************************************************************** *
     * Description      : control for for tradetab
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


  public function actionTradetab()
    {

        $data = TradeExcel::find()->where(['=', 'status', 1])->all();

     $d['overexcell']= $this->renderPartial('/data-trade/overallexcelreportspublished', [
          'model' => $data,
      ]);



        return $this->render('tradeserach',$d);


    }


     /********************************************************************************************** *
     * Description      : control for for result ProductCoderesult
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


  public function actionProductCoderesult()
    {




        $request = Yii::$app->request;
        $datapost = $request->post('Trade');


        $timeframe=$datapost['timeframe'];



        if($datapost){

        $result=Trade::resultTrade($datapost);


        $d['datatrademu']=$result;






            if(!empty($d['datatrademu'])){

            if($timeframe=='year'){

                    return $this->renderAjax('tradesearchyear',$d);
                }elseif($timeframe=='month'){

                    return $this->renderAjax('tradesearchview2',$d);
                }
            }else{

                echo 'Not Record';
            }

             }else{

                 echo 'Not Record';
             }



            }



 /********************************************************************************************** *
     * Description      : control for GetCountry
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


public function actionGetCountry(){


$request = Yii::$app->request;
$typedigit = $request->post('typedigit');
$search = $request->post('q');



            $query = Country::find()->
            select(['idcountry AS id','country_descriptionBI AS text']);




         $query->andFilterWhere(['like', 'country_descriptionBI', $search]);


        $list=$query->asArray()->all();


            $out['results'] = array_values($list);

        return Json::encode($out);



}


 /********************************************************************************************** *
     * Description      : control for search PartnerCountry
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/



 public function actionPartnerCountry()
    {



$listcodeobj=CodeType::find()->codetypeselect()->all();

$model = new Trade(['scenario' => 'partnercountry']);

$listcountry=Country::find()->asArray()->all();

$listcode=ArrayHelper::map($listcodeobj,'idcode_type',function($code){
 return   $code['code_typedesc'];
});


$d['model']=$model;
$d['listcode']=$listcode;
      $request = Yii::$app->request;
$datapost = $request->post('Trade');



$html= $this->renderAjax('partnercountry',$d);
return Json::encode($html);

    }


   /********************************************************************************************** *
     * Description      : control for search PartnerCountry
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/

public function actionCountryselecet()
    {
// $request = Yii::$app->request;
// $countrymulti = $request->post('id');

// if($countrymulti =='one'){
//     $multiconfig=false;
// }else{
//     $multiconfig=true;
// }


// $url = \yii\helpers\Url::to(['/trade/get-country/']);

//  $form = ActiveForm::begin([
//         'id' => 'tradesearch',
//        // 'action'=>$urlajax ,
//     //'enableAjaxValidation' => true,
//     'validateOnChange' => false,
//     'type'=>ActiveForm::TYPE_HORIZONTAL,
//     'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
// ]);
// $model = new Trade();

// return $form->field($model, 'country')->widget(Select2::classname(), [
//    // 'initValueText' => $cityDesc, // set the initial display text
//     'options' => ['placeholder' => 'Search for a Country ...'],
//     'pluginOptions' => [
//          'allowClear' => false,
//         'minimumInputLength' => 1,
//          'multiple' => $multiconfig,
//         'language' => [
//             'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
//         ],
//         'ajax' => [
//             'url' => $url,
//             'dataType' => 'json',
//             'method'=>'post',

//             'data' => new JsExpression('function(params) {

//                   var typedigit=$("#trade-typedigit").val();

//                    return {typedigit:typedigit,q:params.term, page: params.page};

//               }')
//         ],
//         'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
//         'templateResult' => new JsExpression('function(code_idcode) { return code_idcode.text; }'),
//         'templateSelection' => new JsExpression('function (code_idcode) {

//             var str = code_idcode.text;
// var         res = str.substring(0, 60);
//             return  res;


//              }'),
//     ],
// ])->label('PARTNER COUNTRY ');





    }

   /********************************************************************************************** *
     * Description      : control for  Codetypepartner
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/

     public function actionCodetypepartner()
    {


         $request = Yii::$app->request;
        $countrypartner = $request->post('countrypartner');


         if($countrypartner=='multiple'){
            $typecode=[4,7,8,9];
            $in="in";
        }else{
            $typecode=[4,7,8,9,1];
            $in="not in";
        }



         $model = new Trade();
        $listcodeobj=CodeType::find()->codetypeselect()->where([$in,'idcode_type',$typecode])->all();


$listcode=ArrayHelper::map($listcodeobj,'idcode_type',function($code){
 return   $code['code_typedesc'];
});


$form = ActiveForm::begin([
    //     'id' => 'tradesearch',

     // 'validateOnChange' => true,
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
]);

$urlcoderange = Url::to(['trade/coderange']);



echo $form->field($model, 'typedigit', [
      'addon' => [

        'prepend' => ['content'=>'<a  class="fa fa-search fa-lg modalcode "  onclick="popupcode()" ></a>'],

    ],
    'hintType' => ActiveField::HINT_SPECIAL,
    'hintSettings' => [
        'showIcon' => true,
        'icon'=>'<i class="fa fa-star" style="font-size:50%;color:red;"></i>',
        'title' => '<i class="glyphicon glyphicon-info-sign"></i> Note'
    ]
])->dropDownList(
                               $listcode,
                                ['prompt'=>'Select...',
                                 'onchange'=>"
                                    var id=$(this).val();
                                          if(id==3 || id==2){

                                           $('#producdesc').hide('');
                                     }else{
                                        $('#producdesc').show('');

                                     }


                                $.ajax({
                                     'url':'".$urlcoderange."',
                                     'data':{'id':id},
                                     'method':'POST',
                                     'success':function(data){


                                        if(data == 'false'){
                                            $('#rangecodebox').html('');
                                            $('#rangecodebox').hide();

                                        }else{



                                            $('#rangecodebox').html(data);


                                        }


                                     }

                                });

                                ",
                                'id'=>'digitidpartner']);









 }



   /********************************************************************************************** *
     * Description      : control for  Yearrangepartner1
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


 public function actionYearrangepartner1(){

        $embigo=Trade::EmbigoCurrent();
             $out= CodeType::find()->select('data_detail.data_year AS id,data_detail.data_year AS name')
     ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])

     ->groupby(['data_detail.data_year'])
     ->orderBy(['data_detail.data_year'=> SORT_ASC])
      ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
     ->asArray()->all();

            foreach ($out as $key => $value) {
                if(!empty($value['id'])){
                    $out2[]=array('id'=>$value['id'],'name'=>$value['name']);
                }

            }

            echo Json::encode(['output'=>$out2, 'selected'=>'']);



     }

   /********************************************************************************************** *
     * Description      : control for  Yearrangepartner2
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/


     public function actionYearrangepartner2(){


 $request = Yii::$app->request;

$year = $request->post('depdrop_all_params');
 $embigo=Trade::EmbigoCurrent();


$traderangeyear=$year['trade-rangeyearpartner'];
$digitid=$year['digitidpartner'];


       $yearmax= CodeType::find()->select('MAX(data_detail.data_year) AS year')
     ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])
     ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
     ->groupby(['data_detail.data_year'])
     ->orderBy('data_detail.data_year DESC')
     ->asArray()->one();



$parents = $request->post('depdrop_parents');


      $out = [];
    if (isset($parents)) {

        if ($parents[0] != null) {


    $newyear =$year['trade-rangeyearpartner'];


    $year6 = $newyear+6;
    $yearlimit=$yearmax['year'];


    if($year6 >= $yearlimit){
        $yearfrom=$yearlimit;
    }else{
   $yearfrom=$year6;
    }

      $bill=0;
    for ($z=$newyear; $z <= $yearfrom  ; $z++) {
        $bill++;

            $out2[]=array('id'=>$z,'name'=>$z);

              if($bill == 6){
                break;
              }



    }



             echo Json::encode(['output'=>$out2, 'selected'=>'']);
         }

    }else{
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }



     }


   /********************************************************************************************** *
     * Description      : control for  result PartnerCountry
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/



 public function actionPartnerCountryresult()
    {

$model = new Trade(['scenario' => 'partnercountry']);

        $request = Yii::$app->request;
        $datapost = $request->post('Trade');
        $timeframe=$datapost['timeframe'];
        $typedigit=$datapost['typedigit'];

        $tradeflow=$datapost['tradeflow'];






if($datapost){

    if($typedigit !=2 && $typedigit !=3 ){

        $result=Trade::resultTrade($datapost);
        $d['datatrademu']=$result;


        if($timeframe=='year'){

        if(!empty($d['datatrademu'])){
            return $this->renderAjax('tradesearchyearpartner',$d);

        }else{

            return 'Tiada Data';
        }




    }elseif($timeframe=='month'){

         if(!empty($d['datatrademu'])){
             return $this->renderAjax('tradesearchview2partner',$d);

        }else{

            return 'No Record';
        }



    }



    }else{


     foreach ($tradeflow as $rowtradeflow) {
        $result=Trade::resultTradeTop($datapost,$rowtradeflow);

        $d['datatrademu']=$result;

       $top20['datatop'][$rowtradeflow][] =$this->renderPartial('tradesearchyearpartnertop',$d);

     }








     if($timeframe=='year'){

        if(!empty( $top20['datatop'])){
            return $this->renderAjax('top20productbycountry',$top20);

        }else{

             return 'No Record';
        }




    }elseif($timeframe=='month'){

           $result=Trade::resultTrade($datapost);
        $d['datatrademu']=$result;

         if(!empty($d['datatrademu'])){
             return $this->renderAjax('tradesearchview2partner',$d);

        }else{

            return 'No Record';
        }








    }




$d['datatrademu']=$result;


}





}

}


  /********************************************************************************************** *
     * Description      : control for  serach GeographicalGroup
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/




   public function actionGeographicalGroup()
    {

         $request = Yii::$app->request;
      $typegroup=$request->get('typegroup');


$listcodeobj=CodeType::find()->codetypeselect()->all();

$model = new Trade(['scenario' => 'geogroup']);

$listcountry=Country::find()->asArray()->all();

$listcode=ArrayHelper::map($listcodeobj,'idcode_type',function($code){
 return   $code['code_typedesc'];
});

if($typegroup=='economic'){
$typeid=2;
$view='economicgroup';

}else{
$typeid=1;
$view='geogroup';
}
$geogroup=GroupTypeCountry::find()->where(['idgroup_type'=>$typeid])->all();
$listgeogroup=ArrayHelper::map($geogroup,'idgroup',function($codes){
 return   $codes['group_descBI'];
});

$d['model']=$model;
$d['listcode']=$listcode;
$d['listcountry']=$listcountry;
$d['geogroup']=$listgeogroup;


$datapost = $request->post('Trade');



$html= $this->renderAjax($view,$d);
return Json::encode($html);
 }


  /********************************************************************************************** *
     * Description      : control for  serach Yearrangegroup
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/



 public function actionYearrangegroup(){


 $request = Yii::$app->request;

$typedigitid = $request->post('depdrop_all_params');
$embigo=Trade::EmbigoCurrent();


      $out = [];
    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $cat_id = $parents[0];
             $out= CodeType::find()->select('data_detail.data_year AS id,data_detail.data_year AS name')
     ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])
     ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
     ->groupby(['data_detail.data_year'])
     ->orderBy(['data_detail.data_year'=> SORT_ASC])
     ->asArray()->all();

            foreach ($out as $key => $value) {
                if(!empty($value['id'])){
                    $out2[]=array('id'=>$value['id'],'name'=>$value['name']);
                }

            }

            echo Json::encode(['output'=>$out2, 'selected'=>'']);
            return;
        }
    }
    echo Json::encode(['output'=>'', 'selected'=>'']);


     }


  /********************************************************************************************** *
     * Description      : control for  serach Yearrangegroup2
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/

          public function actionYearrangegroup2(){


 $request = Yii::$app->request;

$year = $request->post('depdrop_parents');
$embigo=Trade::EmbigoCurrent();

$traderangeyear=$year[0];
$digitid=$year[1];


       $yearmax= CodeType::find()->select('MAX(data_detail.data_year) AS year')
     ->joinWith(['tradeNumbers.codeTrades.iddataTrade.dataDetails'])
     ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
     ->groupby(['data_detail.data_year'])
     ->orderBy('data_detail.data_year DESC')
     ->asArray()->one();



$parents = $request->post('depdrop_parents');


      $out = [];
    if (isset($parents)) {

        if ($parents[0] != null) {


    $newyear =$year[0];


    $year6 = $newyear+6;
    $yearlimit=$yearmax['year'];


    if($year6 >= $yearlimit){
        $yearfrom=$yearlimit;
    }else{
   $yearfrom=$year6;
    }

      $bill=0;
    for ($z=$newyear; $z <= $yearfrom  ; $z++) {
        $bill++;

            $out2[]=array('id'=>$z,'name'=>$z);

              if($bill == 6){
                break;
              }



    }



             echo Json::encode(['output'=>$out2, 'selected'=>'']);
         }

    }else{
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }



     }


  /********************************************************************************************** *
     * Description      : control for  result GeoGroupresult
     * input            : -
     * author           : Sukor
     * Date             :
     * Modification Log : -
 **********************************************************************************************/



 public function actionGeoGroupresult()
    {

        $model = new Trade(['scenario' => 'geogroup']);




        $request = Yii::$app->request;
        $datapost = $request->post('Trade');

        $timeframe=$datapost['timeframe'];



if($datapost){

$result=Trade::resultTrade($datapost);


$d['datatrademu']=$result;


}else{
    $d['serah'] =false;
 }


if(!empty($d['datatrademu'])){

if($timeframe=='year'){

        return $this->renderAjax('tradesearchyeargeo',$d);
    }elseif($timeframe=='month'){

        return $this->renderAjax('tradesearchview2geo',$d);
    }
}else{

    echo '-';
}
}









 public function actionCheckdata()
    {



          // print_r($_POST);
        // die();

$listcodeobj=CodeType::find()->codetypeselect()->all();

$model = new Trade(['scenario' => 'productcode']);

$listcountry=Country::find()->asArray()->all();

$listcode=ArrayHelper::map($listcodeobj,'idcode_type',function($code){
 return   $code['code_typedesc'];
});
//return   $code['code_typedesc'].'-'.$code['code_typedigit'].' digit';

$d['model']=$model;
$d['listcode']=$listcode;
$d['listcountry']=$listcountry;
      $request = Yii::$app->request;
$datapost = $request->post('Trade');


$html= $this->renderAjax('produccodecheck',$d);
return Json::encode($html);



    }

    /********************************************************************************************** *
       * Description      : Visualization
       * input            : -
       * author           : Fathi
       * Date             : 10 Nov 2016
       * Modification Log : -
    **********************************************************************************************/

    public function actionCountrytotaldatatrade()
    {
      // get all country from table country
      // $Country = new Country();
      // query - get all idcountry & country_descriptionBI
      // $sql = 'SELECT idcountry, country_descriptionBI FROM country';
      // $dataCountry = Country::findBySql($sql)->asArray()->all();

      $dataCountry = Country::find()
      ->select(['idcountry', 'country_descriptionBI'])
      ->where(['!=', 'country_descriptionBI', 'CONTRIES WITH L.V.T.'])
      ->andWhere(['!=', 'country_descriptionBI', 'MALAYSIA'])
      ->andWhere(['!=', 'country_descriptionBI', 'OTHER COUNTRIES, NES.'])
      ->asArray()
      ->all();

      // // only select countries with status = 1
      // $dataCountry = Country::find()
      // ->select(['idcountry', 'country_descriptionBI'])
      // ->where(['=', 'country_status', 1])
      // ->andWhere(['!=', 'country_descriptionBI', 'CONTRIES WITH L.V.T.'])
      // ->andWhere(['!=', 'country_descriptionBI', 'MALAYSIA'])
      // ->andWhere(['!=', 'country_descriptionBI', 'OTHER COUNTRIES, NES.'])
      // ->asArray()
      // ->all();

      // get count of country
      // $countCountry = Country::find()->count();
      // store data fetch to an object
      $dataSend['countryInfo'] = $dataCountry;
      // $dataSend['countryCount'] = $countCountry;

      // print_r('<pre>');
      // print_r($dataCountry);
      // print_r('</pre>');

      // get countries group under GEO
      // $sqlgroupGEO = 'SELECT code_group, group_descBI
      // FROM group_type_country
      // WHERE idgroup_type = 1
      // EXCEPT
      // SELECT code_group, group_descBI
      // FROM group_type_country
      // WHERE group_descBI LIKE OTHER COUNTRIES, N.E.S
      // OR group_descBI LIKE OTHER COUNTRIES, NES';
      // $datagroupGEO = GroupTypeCountry::findBySql($sqlgroupGEO)->asArray()->all();

      $datagroupGEO = GroupTypeCountry::find()
      ->select(['code_group','group_descBI'])
      ->where(['=', 'idgroup_type', 1])
      ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, N.E.S.'])
      ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, NES.'])
      ->asArray()
      ->all();

      // // only select group_type_country geo status = 1
      // $datagroupGEO = GroupTypeCountry::find()
      // ->select(['code_group','group_descBI'])
      // ->where(['=', 'idgroup_type', 1])
      // ->andWhere(['=', 'status_select', 1])
      // ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, N.E.S.'])
      // ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, NES.'])
      // ->asArray()
      // ->all();

      $dataSend['datagroupGEO'] = $datagroupGEO;

      // get countries group under ECO
      // $sqlgroupECO = 'SELECT code_group, group_descBI
      // FROM group_type_country
      // WHERE idgroup_type = 2';
      // $datagroupECO = GroupTypeCountry::findBySql($sqlgroupECO)->asArray()->all();

      $datagroupECO = GroupTypeCountry::find()
      ->select(['code_group','group_descBI'])
      ->where(['=', 'idgroup_type', 2])
      ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, N.E.S.'])
      ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, NES'])
      ->asArray()
      ->all();

      // // only select group_type_country eco status = 2
      // $datagroupECO = GroupTypeCountry::find()
      // ->select(['code_group','group_descBI'])
      // ->where(['=', 'idgroup_type', 2])
      // ->andWhere(['=', 'status_select', 1])
      // ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, N.E.S.'])
      // ->andWhere(['!=', 'group_descBI', 'OTHER COUNTRIES, NES'])
      // ->asArray()
      // ->all();

      $dataSend['datagroupECO'] = $datagroupECO;

      // // get year
      // $sqlYear = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
      // FROM code_desc CD
      //   JOIN desc_relation DR
      //     ON DR.idcode_desc = CD.idcode_desc
      //   JOIN trade_number TN
      //     ON TN.idtrade_number = DR.idtrade_number
      //   JOIN code_trade CT
      //     ON CT.idtrade_number = TN.idtrade_number
      //   JOIN data_trade DT
      //     ON DT.iddata_trade = CT.iddata_trade
      //   JOIN data_detail DD
      //     ON DD.iddata_trade = DT.iddata_trade
      // WHERE TN.idcode_type = 7
      // GROUP BY DD.data_year
      // ORDER BY DD.data_year ASC';
      // $codeDescYear = TradeNumber::findBySql($sqlYear)->asArray()->all();
      //
      // $dataSend['codeDescYear'] = $codeDescYear;

      // return $this->render('countrydropdown', $dataSend);

      $html= $this->renderAjax('countrydropdown',$dataSend);
      return Json::encode($html);
    }

    public function actionYeartotaldatatrade()
    {
      $html= $this->renderAjax('yeardropdown');
      return Json::encode($html);
    }

    public function actionDisplaycountrychart()
    {

      // print_r(Yii::$app->request->post());


      //data retrieved from visualize/countrydropdown.php view
      $postData = Yii::$app->request->post('countrySelection');
      // $postData = Yii::$app->request->post();

      // chosen country
      // $dataPassedFromSelectedOption = $postData['countrySelection'];
      $dataPassedFromSelectedOption = $postData;
      $dataSend['countryPicked'] = $dataPassedFromSelectedOption;
      $dataSend['csrf'] = Yii::$app->request->post('_csrf');

      // print_r('<pre>');
      // print_r($postData);
      // print_r('</pre>');
// die();
      // chosen year
      // $dataPassedFromSelectedOption2 = Yii::$app->request->post('yearSelection');

      // get latest year
      // $latestYearSql = 'SELECT DD.data_year
      // FROM embargo E
      // WHERE status = 1';
      // $latestYear = DataDetail::findBySql($latestYearSql)->asArray()->all();

      // $embigo=Trade::EmbigoCurrent();
      // $yearmax= DataDetail::find()
      //  ->select('data_detail.data_year')
      //  ->joinWith(['codeTrades.idtradeNumber.idcodeType'])
      //  ->where(['code_type.idcode_type'=>7])
      //  ->andFilterWhere(['<=', 'data_detail.data_year' ,(int) $embigo['year']])
      //  ->groupby(['data_detail.data_year'])
      //  ->orderBy(['data_detail.data_year'=> SORT_DESC])
      //  ->one();
      //
      //  print_r('<pre>');
      //  print_r($yearmax);
      //  print_r('</pre>');

      // get year for dropdown
      $sqlYear = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
      FROM code_desc CD
        JOIN desc_relation DR
          ON DR.idcode_desc = CD.idcode_desc
        JOIN trade_number TN
          ON TN.idtrade_number = DR.idtrade_number
        JOIN code_trade CT
          ON CT.idtrade_number = TN.idtrade_number
        JOIN data_trade DT
          ON DT.iddata_trade = CT.iddata_trade
        JOIN data_detail DD
          ON DD.iddata_trade = DT.iddata_trade
      WHERE TN.idcode_type = 7
      AND DD.status = 1
      GROUP BY DD.data_year
      ORDER BY DD.data_year DESC';
      $codeDescYear = TradeNumber::findBySql($sqlYear)->asArray()->all();

      $dataSend['codeDescYear'] = $codeDescYear;

      $dataPassedFromSelectedOption2 = $codeDescYear[0]['data_year'];

      // print_r('<pre>');
      // print_r($codeDescYear[0]['data_year']);
      // print_r('</pre>');
      // print_r('<pre>');
      // print_r(count($codeDescYear));
      // print_r('</pre>');

      //data get from form post
      $dataSend['year'] = $dataPassedFromSelectedOption2;

      // $sqlImport4 = 'SELECT DD.data_month, SUM(DD.data_trade) AS TotalDataTrade
      // FROM [group_type] GT
      //   JOIN group_type_country GTC
      //     ON GT.idgroup_type = GTC.idgroup_type
      //   JOIN group_country GC
      //     ON GTC.idgroup = GC.idgroup
      //   JOIN country C
      //     ON GC.idcountry = C.idcountry
      //   JOIN data_trade DT
      //     ON C.idcountry = DT.idcountry
      //   JOIN data_detail DD
      //     ON DT.iddata_trade = DD.iddata_trade
      // WHERE DD.tradeClass IN (1,4)
      // AND DD.status = 1
      // GROUP BY DD.data_month
      // ORDER BY DD.data_month ASC';
      // $dataTradeTotalImport4 = DataDetail::findBySql($sqlImport4)->asArray()->all();
      //
      // print_r('<pre>');
      // print_r($dataTradeTotalImport4);
      // print_r('</pre>');

      if($dataPassedFromSelectedOption == 'all'){

        $dataSend['titleName'] = 'All Countries';

        // sql query to retrieve data trade import (tradeclass : 1 & 4)
        $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
        FROM [group_type] GT
          JOIN group_type_country GTC
            ON GT.idgroup_type = GTC.idgroup_type
          JOIN group_country GC
            ON GTC.idgroup = GC.idgroup
          JOIN country C
            ON GC.idcountry = C.idcountry
          JOIN data_trade DT
            ON C.idcountry = DT.idcountry
          JOIN data_detail DD
            ON DT.iddata_trade = DD.iddata_trade
        WHERE DD.tradeClass IN (1,4)
        -- AND DD.status = 1
        GROUP BY DD.data_year
        ORDER BY DD.data_year ASC';
        $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

        $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

        // sql query to retrive data trade export (tradeclass : 2,3,5 & 6)
        $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
        FROM [group_type] GT
          JOIN group_type_country GTC
            ON GT.idgroup_type = GTC.idgroup_type
          JOIN group_country GC
            ON GTC.idgroup = GC.idgroup
          JOIN country C
            ON GC.idcountry = C.idcountry
          JOIN data_trade DT
            ON C.idcountry = DT.idcountry
          JOIN data_detail DD
            ON DT.iddata_trade = DD.iddata_trade
        WHERE DD.tradeClass IN (2,3,5,6)
        -- AND DD.status = 1
        GROUP BY DD.data_year
        ORDER BY DD.data_year ASC';
        $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

        $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

        // print_r('<pre>');
        // print_r($dataTradeTotalImport);
        // print_r('</pre>');
        //
        // print_r('<pre>');
        // print_r($dataTradeTotalExport);
        // print_r('</pre>');

        // bar chart START
        //import
        $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
        FROM [data_detail] DD
          JOIN data_trade DT
            ON DD.iddata_trade = DT.iddata_trade
          JOIN code_trade CT
            ON DT.iddata_trade = CT.iddata_trade
          JOIN trade_number TN
            ON CT.idtrade_number = TN.idtrade_number
          JOIN country C
            ON DT.idcountry = C.idcountry
          JOIN group_country GC
            ON C.idcountry = GC.idcountry
          JOIN group_type_country GTC
            ON GC.idgroup = GTC.idgroup
        WHERE TN.idcode_type = 7
        AND GTC.idgroup_type IN (1,2)
        AND DD.tradeClass IN (1,4)
        AND DD.data_year = '.$dataPassedFromSelectedOption2.'
        GROUP BY TN.idtrade_number
        ORDER BY TN.idtrade_number ASC';
        $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

        //export
        $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
        FROM [data_detail] DD
          JOIN data_trade DT
            ON DD.iddata_trade = DT.iddata_trade
          JOIN code_trade CT
            ON DT.iddata_trade = CT.iddata_trade
          JOIN trade_number TN
            ON CT.idtrade_number = TN.idtrade_number
          JOIN country C
            ON DT.idcountry = C.idcountry
          JOIN group_country GC
            ON C.idcountry = GC.idcountry
          JOIN group_type_country GTC
            ON GC.idgroup = GTC.idgroup
        WHERE TN.idcode_type = 7
        AND GTC.idgroup_type IN (1,2)
        AND DD.tradeClass IN (2,3,5,6)
        AND DD.data_year = '.$dataPassedFromSelectedOption2.'
        GROUP BY TN.idtrade_number
        ORDER BY TN.idtrade_number ASC';
        $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
        // bar chart END

        // print_r('<pre>');
        // print_r($codeDescImport);
        // print_r('</pre>');
        //
        // print_r('<pre>');
        // print_r($codeDescExport);
        // print_r('</pre>');
      }
        else if($dataPassedFromSelectedOption == 'groupGeo'){

          $dataGeo = 1;
          $dataSend['titleName'] = 'Group Geo';

          // sql query to retrieve data trade import (tradeclass : 1 & 4)
          $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
          FROM [group_type] GT
            JOIN group_type_country GTC
              ON GT.idgroup_type = GTC.idgroup_type
            JOIN group_country GC
              ON GTC.idgroup = GC.idgroup
            JOIN country C
              ON GC.idcountry = C.idcountry
            JOIN data_trade DT
              ON C.idcountry = DT.idcountry
            JOIN data_detail DD
              ON DT.iddata_trade = DD.iddata_trade
          WHERE GT.idgroup_type = '.$dataGeo.'
          AND DD.tradeClass IN (1,4)
          AND DD.status = 1
          GROUP BY DD.data_year
          ORDER BY DD.data_year ASC';
          $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

          $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

          // sql query to retrive data trade export (tradeclass : 2,3,5 & 6)
          $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
          FROM [group_type] GT
            JOIN group_type_country GTC
              ON GT.idgroup_type = GTC.idgroup_type
            JOIN group_country GC
              ON GTC.idgroup = GC.idgroup
            JOIN country C
              ON GC.idcountry = C.idcountry
            JOIN data_trade DT
              ON C.idcountry = DT.idcountry
            JOIN data_detail DD
              ON DT.iddata_trade = DD.iddata_trade
          WHERE GT.idgroup_type = '.$dataGeo.'
          AND DD.tradeClass IN (2,3,5,6)
          AND DD.status = 1
          GROUP BY DD.data_year
          ORDER BY DD.data_year ASC';
          $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

          $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

          // print_r('<pre>');
          // print_r($dataTradeTotalImport);
          // print_r('</pre>');
          //
          // print_r('<pre>');
          // print_r($dataTradeTotalExport);
          // print_r('</pre>');

          // bar chart START
          //import
          $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
          FROM [data_detail] DD
            JOIN data_trade DT
              ON DD.iddata_trade = DT.iddata_trade
            JOIN code_trade CT
              ON DT.iddata_trade = CT.iddata_trade
            JOIN trade_number TN
              ON CT.idtrade_number = TN.idtrade_number
            JOIN country C
              ON DT.idcountry = C.idcountry
            JOIN group_country GC
              ON C.idcountry = GC.idcountry
            JOIN group_type_country GTC
              ON GC.idgroup = GTC.idgroup
          WHERE TN.idcode_type = 7
          AND GTC.idgroup_type = '.$dataGeo.'
          AND DD.tradeClass IN (1,4)
          AND DD.data_year = '.$dataPassedFromSelectedOption2.'
          GROUP BY TN.idtrade_number
          ORDER BY TN.idtrade_number ASC';
          $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

          //export
          $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
          FROM [data_detail] DD
            JOIN data_trade DT
              ON DD.iddata_trade = DT.iddata_trade
            JOIN code_trade CT
              ON DT.iddata_trade = CT.iddata_trade
            JOIN trade_number TN
              ON CT.idtrade_number = TN.idtrade_number
            JOIN country C
              ON DT.idcountry = C.idcountry
            JOIN group_country GC
              ON C.idcountry = GC.idcountry
            JOIN group_type_country GTC
              ON GC.idgroup = GTC.idgroup
          WHERE TN.idcode_type = 7
          AND GTC.idgroup_type = '.$dataGeo.'
          AND DD.tradeClass IN (2,3,5,6)
          AND DD.data_year = '.$dataPassedFromSelectedOption2.'
          GROUP BY TN.idtrade_number
          ORDER BY TN.idtrade_number ASC';
          $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
          // bar chart END

        }
          else if($dataPassedFromSelectedOption == 'groupEco'){

            $dataEco = 2;
            $dataSend['titleName'] = 'Group Eco';

            // sql query to retrieve data trade import (tradeclass : 1 & 4)
            $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
            FROM [group_type] GT
              JOIN group_type_country GTC
                ON GT.idgroup_type = GTC.idgroup_type
              JOIN group_country GC
                ON GTC.idgroup = GC.idgroup
              JOIN country C
                ON GC.idcountry = C.idcountry
              JOIN data_trade DT
                ON C.idcountry = DT.idcountry
              JOIN data_detail DD
                ON DT.iddata_trade = DD.iddata_trade
            WHERE GT.idgroup_type = '.$dataEco.'
            AND DD.tradeClass IN (1,4)
            AND DD.status = 1
            GROUP BY DD.data_year
            ORDER BY DD.data_year ASC';
            $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

            $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

            // sql query to retrive data trade export (tradeclass : 2,3,5 & 6)
            $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
            FROM [group_type] GT
              JOIN group_type_country GTC
                ON GT.idgroup_type = GTC.idgroup_type
              JOIN group_country GC
                ON GTC.idgroup = GC.idgroup
              JOIN country C
                ON GC.idcountry = C.idcountry
              JOIN data_trade DT
                ON C.idcountry = DT.idcountry
              JOIN data_detail DD
                ON DT.iddata_trade = DD.iddata_trade
            WHERE GT.idgroup_type = '.$dataEco.'
            AND DD.tradeClass IN (2,3,5,6)
            AND DD.status = 1
            GROUP BY DD.data_year
            ORDER BY DD.data_year ASC';
            $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

            $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

            // print_r('<pre>');
            // print_r($dataTradeTotalImport);
            // print_r('</pre>');
            //
            // print_r('<pre>');
            // print_r($dataTradeTotalExport);
            // print_r('</pre>');

            // bar chart START
            //import
            $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
            FROM [data_detail] DD
              JOIN data_trade DT
                ON DD.iddata_trade = DT.iddata_trade
              JOIN code_trade CT
                ON DT.iddata_trade = CT.iddata_trade
              JOIN trade_number TN
                ON CT.idtrade_number = TN.idtrade_number
              JOIN country C
                ON DT.idcountry = C.idcountry
              JOIN group_country GC
                ON C.idcountry = GC.idcountry
              JOIN group_type_country GTC
                ON GC.idgroup = GTC.idgroup
            WHERE TN.idcode_type = 7
            AND GTC.idgroup_type = '.$dataEco.'
            AND DD.tradeClass IN (1,4)
            AND DD.data_year = '.$dataPassedFromSelectedOption2.'
            GROUP BY TN.idtrade_number
            ORDER BY TN.idtrade_number ASC';
            $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

            //export
            $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
            FROM [data_detail] DD
              JOIN data_trade DT
                ON DD.iddata_trade = DT.iddata_trade
              JOIN code_trade CT
                ON DT.iddata_trade = CT.iddata_trade
              JOIN trade_number TN
                ON CT.idtrade_number = TN.idtrade_number
              JOIN country C
                ON DT.idcountry = C.idcountry
              JOIN group_country GC
                ON C.idcountry = GC.idcountry
              JOIN group_type_country GTC
                ON GC.idgroup = GTC.idgroup
            WHERE TN.idcode_type = 7
            AND GTC.idgroup_type = '.$dataEco.'
            AND DD.tradeClass IN (2,3,5,6)
            AND DD.data_year = '.$dataPassedFromSelectedOption2.'
            GROUP BY TN.idtrade_number
            ORDER BY TN.idtrade_number ASC';
            $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
            // bar chart END
          }
            else if(substr($dataPassedFromSelectedOption, 0, 3) == 'geo'){

              //geo group name
              $codeGroup = substr($dataPassedFromSelectedOption, 3, strlen($dataPassedFromSelectedOption));

              $GroupTypeCountry = new GroupTypeCountry();

              $dataName = GroupTypeCountry::find()->where([
                'code_group' => $codeGroup
                ])->one();

              $dataSend['titleName'] = $dataName->group_descBI;

              // print_r('<pre>');
              // print_r($dataSend['titleName']);
              // print_r('</pre>');

              if($codeGroup == '99'){
                $dataGeo = 1;
                $dataSend['titleName'] = 'Geographical Grouping';

                // sql query to retrieve data trade import (tradeclass : 1 & 4)
                $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                FROM [group_type] GT
                  JOIN group_type_country GTC
                    ON GT.idgroup_type = GTC.idgroup_type
                  JOIN group_country GC
                    ON GTC.idgroup = GC.idgroup
                  JOIN country C
                    ON GC.idcountry = C.idcountry
                  JOIN data_trade DT
                    ON C.idcountry = DT.idcountry
                  JOIN data_detail DD
                    ON DT.iddata_trade = DD.iddata_trade
                WHERE GT.idgroup_type = '.$dataGeo.'
                AND DD.tradeClass IN (1,4)
                AND DD.status = 1
                GROUP BY DD.data_year
                ORDER BY DD.data_year ASC';
                $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

                $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

                // sql query to retrive data trade export (tradeclass : 2,3,5 & 6)
                $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                FROM [group_type] GT
                  JOIN group_type_country GTC
                    ON GT.idgroup_type = GTC.idgroup_type
                  JOIN group_country GC
                    ON GTC.idgroup = GC.idgroup
                  JOIN country C
                    ON GC.idcountry = C.idcountry
                  JOIN data_trade DT
                    ON C.idcountry = DT.idcountry
                  JOIN data_detail DD
                    ON DT.iddata_trade = DD.iddata_trade
                WHERE GT.idgroup_type = '.$dataGeo.'
                AND DD.tradeClass IN (2,3,5,6)
                AND DD.status = 1
                GROUP BY DD.data_year
                ORDER BY DD.data_year ASC';
                $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

                $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

                // print_r('<pre>');
                // print_r($dataTradeTotalImport);
                // print_r('</pre>');
                //
                // print_r('<pre>');
                // print_r($dataTradeTotalExport);
                // print_r('</pre>');

                // bar chart START
                //import
                $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                FROM [data_detail] DD
                  JOIN data_trade DT
                    ON DD.iddata_trade = DT.iddata_trade
                  JOIN code_trade CT
                    ON DT.iddata_trade = CT.iddata_trade
                  JOIN trade_number TN
                    ON CT.idtrade_number = TN.idtrade_number
                  JOIN country C
                    ON DT.idcountry = C.idcountry
                  JOIN group_country GC
                    ON C.idcountry = GC.idcountry
                  JOIN group_type_country GTC
                    ON GC.idgroup = GTC.idgroup
                WHERE TN.idcode_type = 7
                AND GTC.idgroup_type = '.$dataGeo.'
                AND DD.tradeClass IN (1,4)
                AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                GROUP BY TN.idtrade_number
                ORDER BY TN.idtrade_number ASC';
                $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

                //export
                $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                FROM [data_detail] DD
                  JOIN data_trade DT
                    ON DD.iddata_trade = DT.iddata_trade
                  JOIN code_trade CT
                    ON DT.iddata_trade = CT.iddata_trade
                  JOIN trade_number TN
                    ON CT.idtrade_number = TN.idtrade_number
                  JOIN country C
                    ON DT.idcountry = C.idcountry
                  JOIN group_country GC
                    ON C.idcountry = GC.idcountry
                  JOIN group_type_country GTC
                    ON GC.idgroup = GTC.idgroup
                WHERE TN.idcode_type = 7
                AND GTC.idgroup_type = '.$dataGeo.'
                AND DD.tradeClass IN (2,3,5,6)
                AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                GROUP BY TN.idtrade_number
                ORDER BY TN.idtrade_number ASC';
                $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
                // bar chart END
              }
              else{
                // sql query to retrive data trade import (tradeclass : 1 & 4)
                $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                FROM [group_type_country] GTC
                  JOIN group_country GC
                    ON GTC.idgroup = GC.idgroup
                  JOIN country C
                    ON GC.idcountry = C.idcountry
                  JOIN data_trade DT
                    ON C.idcountry = DT.idcountry
                  JOIN data_detail DD
                    ON DT.iddata_trade = DD.iddata_trade
                WHERE GTC.code_group = '.$codeGroup.'
                AND DD.tradeClass IN (1,4)
                AND DD.status = 1
                GROUP BY DD.data_year
                ORDER BY DD.data_year ASC';
                $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

                $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

                // print_r('<pre>');
                // print_r($dataTradeTotalImport);
                // print_r('</pre>');

                // sql query to retrive data trade export (tradeclass : 2, 3, 5 & 6)
                $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                FROM [group_type_country] GTC
                  JOIN group_country GC
                    ON GTC.idgroup = GC.idgroup
                  JOIN country C
                    ON GC.idcountry = C.idcountry
                  JOIN data_trade DT
                    ON C.idcountry = DT.idcountry
                  JOIN data_detail DD
                    ON DT.iddata_trade = DD.iddata_trade
                WHERE GTC.code_group = '.$codeGroup.'
                AND DD.tradeClass IN (2,3,5,6)
                AND DD.status = 1
                GROUP BY DD.data_year
                ORDER BY DD.data_year ASC';
                $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

                $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

                // print_r('<pre>');
                // print_r($dataTradeTotalExport);
                // print_r('</pre>');

                // bar chart START
                //import
                $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                FROM [data_detail] DD
                  JOIN data_trade DT
                    ON DD.iddata_trade = DT.iddata_trade
                  JOIN code_trade CT
                    ON DT.iddata_trade = CT.iddata_trade
                  JOIN trade_number TN
                    ON CT.idtrade_number = TN.idtrade_number
                  JOIN country C
                    ON DT.idcountry = C.idcountry
                  JOIN group_country GC
                    ON C.idcountry = GC.idcountry
                  JOIN group_type_country GTC
                    ON GC.idgroup = GTC.idgroup
                WHERE TN.idcode_type = 7
                AND GTC.code_group = '.$codeGroup.'
                AND DD.tradeClass IN (1,4)
                AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                GROUP BY TN.idtrade_number
                ORDER BY TN.idtrade_number ASC';
                $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

                //export
                $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                FROM [data_detail] DD
                  JOIN data_trade DT
                    ON DD.iddata_trade = DT.iddata_trade
                  JOIN code_trade CT
                    ON DT.iddata_trade = CT.iddata_trade
                  JOIN trade_number TN
                    ON CT.idtrade_number = TN.idtrade_number
                  JOIN country C
                    ON DT.idcountry = C.idcountry
                  JOIN group_country GC
                    ON C.idcountry = GC.idcountry
                  JOIN group_type_country GTC
                    ON GC.idgroup = GTC.idgroup
                WHERE TN.idcode_type = 7
                AND GTC.code_group = '.$codeGroup.'
                AND DD.tradeClass IN (2,3,5,6)
                AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                GROUP BY TN.idtrade_number
                ORDER BY TN.idtrade_number ASC';
                $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
                // bar chart END
              }

            }
              else if(substr($dataPassedFromSelectedOption, 0, 3) == 'eco'){

                //eco group name
                $codeGroup = substr($dataPassedFromSelectedOption, 3, strlen($dataPassedFromSelectedOption));

                $GroupTypeCountry = new GroupTypeCountry();

                $dataName = GroupTypeCountry::find()->where([
                  'code_group' => $codeGroup
                  ])->one();

                $dataSend['titleName'] = $dataName->group_descBI;

                // print_r('<pre>');
                // print_r($dataSend['titleName']);
                // print_r('</pre>');

                if($codeGroup == '99'){
                  $dataEco = 2;
                  $dataSend['titleName'] = 'Economic Grouping';

                  // sql query to retrieve data trade import (tradeclass : 1 & 4)
                  $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [group_type] GT
                    JOIN group_type_country GTC
                      ON GT.idgroup_type = GTC.idgroup_type
                    JOIN group_country GC
                      ON GTC.idgroup = GC.idgroup
                    JOIN country C
                      ON GC.idcountry = C.idcountry
                    JOIN data_trade DT
                      ON C.idcountry = DT.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE GT.idgroup_type = '.$dataEco.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

                  $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

                  // sql query to retrive data trade export (tradeclass : 2,3,5 & 6)
                  $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [group_type] GT
                    JOIN group_type_country GTC
                      ON GT.idgroup_type = GTC.idgroup_type
                    JOIN group_country GC
                      ON GTC.idgroup = GC.idgroup
                    JOIN country C
                      ON GC.idcountry = C.idcountry
                    JOIN data_trade DT
                      ON C.idcountry = DT.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE GT.idgroup_type = '.$dataEco.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

                  $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

                  // print_r('<pre>');
                  // print_r($dataTradeTotalImport);
                  // print_r('</pre>');
                  //
                  // print_r('<pre>');
                  // print_r($dataTradeTotalExport);
                  // print_r('</pre>');

                  // bar chart START
                  //import
                  $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                    JOIN group_country GC
                      ON C.idcountry = GC.idcountry
                    JOIN group_type_country GTC
                      ON GC.idgroup = GTC.idgroup
                  WHERE TN.idcode_type = 7
                  AND GTC.idgroup_type = '.$dataEco.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

                  //export
                  $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                    JOIN group_country GC
                      ON C.idcountry = GC.idcountry
                    JOIN group_type_country GTC
                      ON GC.idgroup = GTC.idgroup
                  WHERE TN.idcode_type = 7
                  AND GTC.idgroup_type = '.$dataEco.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
                  // bar chart END
                }else{
                  // sql query to retrive data trade import (tradeclass : 1 & 4)
                  $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [group_type_country] GTC
                    JOIN group_country GC
                      ON GTC.idgroup = GC.idgroup
                    JOIN country C
                      ON GC.idcountry = C.idcountry
                    JOIN data_trade DT
                      ON C.idcountry = DT.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE GTC.code_group = '.$codeGroup.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

                  $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

                  // print_r('<pre>');
                  // print_r($dataTradeTotalImport);
                  // print_r('</pre>');

                  // sql query to retrive data trade export (tradeclass : 2, 3, 5 & 6)
                  $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [group_type_country] GTC
                    JOIN group_country GC
                      ON GTC.idgroup = GC.idgroup
                    JOIN country C
                      ON GC.idcountry = C.idcountry
                    JOIN data_trade DT
                      ON C.idcountry = DT.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE GTC.code_group = '.$codeGroup.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

                  $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;

                  // print_r('<pre>');
                  // print_r($dataTradeTotalExport);
                  // print_r('</pre>');

                  // bar chart START
                  //import
                  $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                    JOIN group_country GC
                      ON C.idcountry = GC.idcountry
                    JOIN group_type_country GTC
                      ON GC.idgroup = GTC.idgroup
                  WHERE TN.idcode_type = 7
                  AND GTC.code_group = '.$codeGroup.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

                  //export
                  $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                    JOIN group_country GC
                      ON C.idcountry = GC.idcountry
                    JOIN group_type_country GTC
                      ON GC.idgroup = GTC.idgroup
                  WHERE TN.idcode_type = 7
                  AND GTC.code_group = '.$codeGroup.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
                  // bar chart END
                }

              }
                else{

                  // retrieve country name from table country
                  // $Country = new Country();

                  // $countryName = Country::find()->where([
                  //   'idcountry' => $dataPassedFromSelectedOption
                  //   ])->one();
                  //
                  // $dataSend['titleName'] = $countryName->country_descriptionBI;

                  $countryNameSQL = 'SELECT *
                  FROM country
                  WHERE idcountry = '.$dataPassedFromSelectedOption.'';
                  $countryName = Country::findBySql($countryNameSQL)->one();

                  $dataSend['titleName'] = $countryName->country_descriptionBI;

                  /* getting import (tradeclass 1 & 4) total data trade START */

                  // $DataTrade = new DataTrade();
                  // query iddata_trade where idcountry = $dummyIDcountry
                  $sql = 'SELECT iddata_trade FROM data_trade WHERE idcountry = '.$dataPassedFromSelectedOption.'';
                  $data_iddataTrade = DataTrade::findBySql($sql)->asArray()->all();

                  // object holding queried DataTrade
                  $dataSend['iddata_trade_fetch'] = $data_iddataTrade;

                  // print_r('<pre>');
                  // print_r($dataSend['iddata_trade_fetch']);
                  // print_r('</pre>');

                  /* single country queries start */
                  /* query for import start */

                  // query : country=China, tradeClass=1(import), year=2015
                  $sqlImport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [country] C
                    JOIN data_trade DT
                      ON DT.idcountry = C.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE C.idcountry = '.$dataPassedFromSelectedOption.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalImport = DataDetail::findBySql($sqlImport)->asArray()->all();

                  $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;

                  /* query for import end */
                  /* single country queries end */

                  // print_r('<pre>');
                  // print_r($dataSend['dataTradeTotalImport']);
                  // print_r('</pre>');
                  // print_r('<pre>');
                  // print_r($dataSend['dataTradeTotal14']);
                  // print_r('</pre>');

                  /* getting import (tradeclass 1 & 4) total data trade END */

                  /* getting export (tradeclass 2,3,5 & 6) total data trade START */
                  /* query for import start */
                  // query : country=China, tradeClass=1(import), year=2015
                  $sqlExport = 'SELECT DD.data_year, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [country] C
                    JOIN data_trade DT
                      ON DT.idcountry = C.idcountry
                    JOIN data_detail DD
                      ON DT.iddata_trade = DD.iddata_trade
                  WHERE C.idcountry = '.$dataPassedFromSelectedOption.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.status = 1
                  GROUP BY DD.data_year
                  ORDER BY DD.data_year ASC';
                  $dataTradeTotalExport = DataDetail::findBySql($sqlExport)->asArray()->all();

                  $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;
                  /* query for import end */

                  // print_r('<pre>');
                  // print_r($dataSend['dataTradeTotalExport']);
                  // print_r('</pre>');
                  /* getting export (tradeclass 2,3,5 & 6) total data trade END */

                  // bar chart START
                  //import
                  $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                  WHERE TN.idcode_type = 7
                  AND C.idcountry = '.$dataPassedFromSelectedOption.'
                  AND DD.tradeClass IN (1,4)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

                  //export
                  $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
                  FROM [data_detail] DD
                    JOIN data_trade DT
                      ON DD.iddata_trade = DT.iddata_trade
                    JOIN code_trade CT
                      ON DT.iddata_trade = CT.iddata_trade
                    JOIN trade_number TN
                      ON CT.idtrade_number = TN.idtrade_number
                    JOIN country C
                      ON DT.idcountry = C.idcountry
                  WHERE TN.idcode_type = 7
                  AND C.idcountry = '.$dataPassedFromSelectedOption.'
                  AND DD.tradeClass IN (2,3,5,6)
                  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
                  GROUP BY TN.idtrade_number
                  ORDER BY TN.idtrade_number ASC';
                  $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
                  // bar chart END
                }

      /* do checking whether the selected option is single country/all/group end */

      // echo json_encode($dataSend);
      // return $this->render('displayGraph', $dataSend);

      /* check data availability and if null give value NONE START */
      $arrayDataTradeZERO = array(
        array(
          "data_year" => "NONE",
          "TotalDataTrade" => 0
        )
      );

      if($dataTradeTotalImport == NULL){
        $dataSend['dataTradeTotalImport'] = $arrayDataTradeZERO;
      }
      else{
        $dataSend['dataTradeTotalImport'] = $dataTradeTotalImport;
      }

      if($dataTradeTotalExport == NULL){
        $dataSend['dataTradeTotalExport'] = $arrayDataTradeZERO;
      }
      else{
        $dataSend['dataTradeTotalExport'] = $dataTradeTotalExport;
      }

      // print_r('<pre>');
      // print_r($arrayDataTradeZERO);
      // print_r('</pre>');

      /* check data availability and if null give value NONE END */

      /* store import & export data fetch in new array (bar chart) start */
      // import
      $array1 = array();
      // fill empty arrays with value 0
      for($w = 0; $w < 10; $w++){
        $array1[$w] = 0;
      }

      // print_r('<pre>');
      // print_r($array1);
      // print_r('</pre>');

      if($codeDescImport == NULL){
        $dataSend['codeDescImport'] = $array1;
      }else{
        // place 0 to any idtrade_number which are not in the array
        if(count($codeDescImport) == 10){
          // $dataSend['codeDescImport'] = $codeDescImport;
          for($u = 0; $u < 10; $u++){
            $array1[$u] = $codeDescImport[$u]['TotalDataTrade'];
          }
          $dataSend['codeDescImport'] = $array1;
        }else{
          for($x = 0; $x < count($codeDescImport); $x++){
            if($codeDescImport[$x]['idtrade_number'] == 7649){
              $array1[0] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7650){
              $array1[1] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7651){
              $array1[2] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7652){
              $array1[3] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7653){
              $array1[4] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7654){
              $array1[5] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7655){
              $array1[6] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7656){
              $array1[7] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            if($codeDescImport[$x]['idtrade_number'] == 7657){
              $array1[8] = $codeDescImport[$x]['TotalDataTrade'];
            }else
            {
              $array1[9] = $codeDescImport[$x]['TotalDataTrade'];
            }
          }
          $dataSend['codeDescImport'] = $array1;
        }
        // $dataSend['codeDescImport'] = $codeDescImport;
      }

      // print_r('<pre>');
      // print_r($dataSend['codeDescImport']);
      // print_r('</pre>');

      // export

      $array2 = array();
      // fill empty arrays with value 0
      for($v = 0; $v < 10; $v++){
        $array2[$v] = 0;
      }

      if($codeDescExport == NULL){
          $dataSend['codeDescExport'] = $array2;
      }
      else{
        if(count(codeDescExport) == 10){
          for($t = 0; $t < 10; $t++){
            $array2[$t] = $codeDescExport[$t]['TotalDataTrade'];
          }
          $dataSend['codeDescExport'] = $array2;
        }else{
          for($x = 0; $x < count($codeDescExport); $x++){
            if($codeDescExport[$x]['idtrade_number'] == 7649){
              $array2[0] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7650){
              $array2[1] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7651){
              $array2[2] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7652){
              $array2[3] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7653){
              $array2[4] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7654){
              $array2[5] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7655){
              $array2[6] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7656){
              $array2[7] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            if($codeDescExport[$x]['idtrade_number'] == 7657){
              $array2[8] = $codeDescExport[$x]['TotalDataTrade'];
            }else
            {
              $array2[9] = $codeDescExport[$x]['TotalDataTrade'];
            }
          }
          $dataSend['codeDescExport'] = $array2;
        }
      }

      // print_r('<pre>');
      // print_r($dataSend['codeDescExport']);
      // print_r('</pre>');

      /* store import & export data fetch in new array (bar chart) end */

      $html= $this->renderAjax('countrydisplaygraph',$dataSend);
      echo $html;
    }

    public function actionDisplayyearchart()
    {
      //data retrieved from trypage.php view
      $postData = Yii::$app->request->post();

      if(isset($postData['key'])){
        $key = $postData['key'];
      }else{
        $key = 0;
      }

      $dataSend['csrf'] = $postData['_csrf'];
      $dataPassedFromSelectedOption = $postData['countryPicked'];
      // $dataPassedFromSelectedOption = 'all';

      // print_r('<br><br><br><br><br><br>');
      // print_r('<pre>');
      // print_r($dataPassedFromSelectedOption);
      // print_r('</pre>');

      // $postData2 = Yii::$app->request->post('yearSelection');
      $dataPassedFromSelectedOption2 = $postData['yearSelection'];
      // $dataPassedFromSelectedOption2 = 2014;

      //data get from form post
      $dataSend['year'] = $dataPassedFromSelectedOption2;

      // print_r('<pre>');
      // print_r($dataSend['year']);
      // print_r('</pre>');

      // die();

///////////////////////////////////////////1st//////////////////////////////////

      // $sqlImport = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
      // FROM code_desc CD
      //   JOIN desc_relation DR
      //     ON DR.idcode_desc = CD.idcode_desc
      //   JOIN trade_number TN
      //     ON TN.idtrade_number = DR.idtrade_number
      //   JOIN code_trade CT
      //     ON CT.idtrade_number = TN.idtrade_number
      //   JOIN data_trade DT
      //     ON DT.iddata_trade = CT.iddata_trade
      //   JOIN data_detail DD
      //     ON DD.iddata_trade = DT.iddata_trade
      // WHERE TN.idcode_type = 7
      // AND DD.tradeClass IN (1,4)
      // AND DD.data_year = '.$dataPassedFromSelectedOption2.'
      // GROUP BY TN.idtrade_number
      // ORDER BY TN.idtrade_number ASC';
      // $codeDescImport = TradeNumber::findBySql($sqlImport)->asArray()->all();
      //
      // // $dataSend['codeDescImport'] = $codeDescImport;
      //
      // $array1 = array();
      // // fill empty arrays with value 0
      // for($w = 0; $w < 10; $w++){
      //   $array1[$w] = 0;
      // }
      //
      // // print_r('<pre>');
      // // print_r($array1);
      // // print_r('</pre>');
      //
      // if($codeDescImport == NULL){
      //   $dataSend['codeDescImport'] = 0;
      // }else{
      //   // place 0 to any idtrade_number which are not in the array
      //   if(count($codeDescImport) == 10){
      //     // $dataSend['codeDescImport'] = $codeDescImport;
      //     for($u = 0; $u < 10; $u++){
      //       $array1[$u] = $codeDescImport[$u]['TotalDataTrade'];
      //     }
      //     $dataSend['codeDescImport'] = $array1;
      //   }else{
      //     for($x = 0; $x < count($codeDescImport); $x++){
      //       if($codeDescImport[$x]['idtrade_number'] == 7649){
      //         $array1[0] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7650){
      //         $array1[1] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7651){
      //         $array1[2] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7652){
      //         $array1[3] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7653){
      //         $array1[4] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7654){
      //         $array1[5] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7655){
      //         $array1[6] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7656){
      //         $array1[7] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescImport[$x]['idtrade_number'] == 7657){
      //         $array1[8] = $codeDescImport[$x]['TotalDataTrade'];
      //       }else
      //       {
      //         $array1[9] = $codeDescImport[$x]['TotalDataTrade'];
      //       }
      //     }
      //     $dataSend['codeDescImport'] = $array1;
      //   }
      //   // $dataSend['codeDescImport'] = $codeDescImport;
      // }
      //
      // // print_r('<pre>');
      // // print_r($dataSend['codeDescImport']);
      // // print_r('</pre>');
      //
      // $sqlExport = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
      // FROM code_desc CD
      //   JOIN desc_relation DR
      //     ON DR.idcode_desc = CD.idcode_desc
      //   JOIN trade_number TN
      //     ON TN.idtrade_number = DR.idtrade_number
      //   JOIN code_trade CT
      //     ON CT.idtrade_number = TN.idtrade_number
      //   JOIN data_trade DT
      //     ON DT.iddata_trade = CT.iddata_trade
      //   JOIN data_detail DD
      //     ON DD.iddata_trade = DT.iddata_trade
      // WHERE TN.idcode_type = 7
      // AND DD.tradeClass IN (2,3,5,6)
      // AND DD.data_year = '.$dataPassedFromSelectedOption2.'
      // GROUP BY TN.idtrade_number
      // ORDER BY TN.idtrade_number ASC';
      // $codeDescExport = TradeNumber::findBySql($sqlExport)->asArray()->all();
      //
      // $array2 = array();
      // // fill empty arrays with value 0
      // for($v = 0; $v < 10; $v++){
      //   $array2[$v] = 0;
      // }
      //
      // if($codeDescExport == NULL){
      //     $dataSend['codeDescExport'] = 0;
      // }
      // else{
      //   if(count(codeDescExport) == 10){
      //     for($t = 0; $t < 10; $t++){
      //       $array2[$t] = $codeDescExport[$t]['TotalDataTrade'];
      //     }
      //     $dataSend['codeDescExport'] = $array2;
      //   }else{
      //     for($x = 0; $x < count($codeDescExport); $x++){
      //       if($codeDescExport[$x]['idtrade_number'] == 7649){
      //         $array2[0] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7650){
      //         $array2[1] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7651){
      //         $array2[2] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7652){
      //         $array2[3] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7653){
      //         $array2[4] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7654){
      //         $array2[5] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7655){
      //         $array2[6] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7656){
      //         $array2[7] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       if($codeDescExport[$x]['idtrade_number'] == 7657){
      //         $array2[8] = $codeDescExport[$x]['TotalDataTrade'];
      //       }else
      //       {
      //         $array2[9] = $codeDescExport[$x]['TotalDataTrade'];
      //       }
      //     }
      //     $dataSend['codeDescExport'] = $array2;
      //   }
      // }

      // print_r('<pre>');
      // print_r($dataSend['codeDescExport']);
      // print_r('</pre>');

///////////////////////////////////////////1st//////////////////////////////////

///////////////////////////////////////////2nd//////////////////////////////////
if($dataPassedFromSelectedOption == 'all'){

  $dataSend['titleName'] = 'All Countries';

  // bar chart START
  //import
  $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
  FROM [data_detail] DD
    JOIN data_trade DT
      ON DD.iddata_trade = DT.iddata_trade
    JOIN code_trade CT
      ON DT.iddata_trade = CT.iddata_trade
    JOIN trade_number TN
      ON CT.idtrade_number = TN.idtrade_number
    JOIN country C
      ON DT.idcountry = C.idcountry
    JOIN group_country GC
      ON C.idcountry = GC.idcountry
    JOIN group_type_country GTC
      ON GC.idgroup = GTC.idgroup
  WHERE TN.idcode_type = 7
  AND GTC.idgroup_type IN (1,2)
  AND DD.tradeClass IN (1,4)
  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
  GROUP BY TN.idtrade_number
  ORDER BY TN.idtrade_number ASC';
  $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

  //export
  $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
  FROM [data_detail] DD
    JOIN data_trade DT
      ON DD.iddata_trade = DT.iddata_trade
    JOIN code_trade CT
      ON DT.iddata_trade = CT.iddata_trade
    JOIN trade_number TN
      ON CT.idtrade_number = TN.idtrade_number
    JOIN country C
      ON DT.idcountry = C.idcountry
    JOIN group_country GC
      ON C.idcountry = GC.idcountry
    JOIN group_type_country GTC
      ON GC.idgroup = GTC.idgroup
  WHERE TN.idcode_type = 7
  AND GTC.idgroup_type IN (1,2)
  AND DD.tradeClass IN (2,3,5,6)
  AND DD.data_year = '.$dataPassedFromSelectedOption2.'
  GROUP BY TN.idtrade_number
  ORDER BY TN.idtrade_number ASC';
  $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
  // bar chart END
}
  else if($dataPassedFromSelectedOption == 'groupGeo'){

    $dataGeo = 1;
    $dataSend['titleName'] = 'Group Geo';

    // bar chart START
    //import
    $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
    FROM [data_detail] DD
      JOIN data_trade DT
        ON DD.iddata_trade = DT.iddata_trade
      JOIN code_trade CT
        ON DT.iddata_trade = CT.iddata_trade
      JOIN trade_number TN
        ON CT.idtrade_number = TN.idtrade_number
      JOIN country C
        ON DT.idcountry = C.idcountry
      JOIN group_country GC
        ON C.idcountry = GC.idcountry
      JOIN group_type_country GTC
        ON GC.idgroup = GTC.idgroup
    WHERE TN.idcode_type = 7
    AND GTC.idgroup_type = '.$dataGeo.'
    AND DD.tradeClass IN (1,4)
    AND DD.data_year = '.$dataPassedFromSelectedOption2.'
    GROUP BY TN.idtrade_number
    ORDER BY TN.idtrade_number ASC';
    $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

    //export
    $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
    FROM [data_detail] DD
      JOIN data_trade DT
        ON DD.iddata_trade = DT.iddata_trade
      JOIN code_trade CT
        ON DT.iddata_trade = CT.iddata_trade
      JOIN trade_number TN
        ON CT.idtrade_number = TN.idtrade_number
      JOIN country C
        ON DT.idcountry = C.idcountry
      JOIN group_country GC
        ON C.idcountry = GC.idcountry
      JOIN group_type_country GTC
        ON GC.idgroup = GTC.idgroup
    WHERE TN.idcode_type = 7
    AND GTC.idgroup_type = '.$dataGeo.'
    AND DD.tradeClass IN (2,3,5,6)
    AND DD.data_year = '.$dataPassedFromSelectedOption2.'
    GROUP BY TN.idtrade_number
    ORDER BY TN.idtrade_number ASC';
    $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
    // bar chart END

  }
    else if($dataPassedFromSelectedOption == 'groupEco'){

      $dataEco = 2;
      $dataSend['titleName'] = 'Group Eco';

      // bar chart START
      //import
      $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
      FROM [data_detail] DD
        JOIN data_trade DT
          ON DD.iddata_trade = DT.iddata_trade
        JOIN code_trade CT
          ON DT.iddata_trade = CT.iddata_trade
        JOIN trade_number TN
          ON CT.idtrade_number = TN.idtrade_number
        JOIN country C
          ON DT.idcountry = C.idcountry
        JOIN group_country GC
          ON C.idcountry = GC.idcountry
        JOIN group_type_country GTC
          ON GC.idgroup = GTC.idgroup
      WHERE TN.idcode_type = 7
      AND GTC.idgroup_type = '.$dataEco.'
      AND DD.tradeClass IN (1,4)
      AND DD.data_year = '.$dataPassedFromSelectedOption2.'
      GROUP BY TN.idtrade_number
      ORDER BY TN.idtrade_number ASC';
      $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

      //export
      $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
      FROM [data_detail] DD
        JOIN data_trade DT
          ON DD.iddata_trade = DT.iddata_trade
        JOIN code_trade CT
          ON DT.iddata_trade = CT.iddata_trade
        JOIN trade_number TN
          ON CT.idtrade_number = TN.idtrade_number
        JOIN country C
          ON DT.idcountry = C.idcountry
        JOIN group_country GC
          ON C.idcountry = GC.idcountry
        JOIN group_type_country GTC
          ON GC.idgroup = GTC.idgroup
      WHERE TN.idcode_type = 7
      AND GTC.idgroup_type = '.$dataEco.'
      AND DD.tradeClass IN (2,3,5,6)
      AND DD.data_year = '.$dataPassedFromSelectedOption2.'
      GROUP BY TN.idtrade_number
      ORDER BY TN.idtrade_number ASC';
      $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
      // bar chart END
    }
      else if(substr($dataPassedFromSelectedOption, 0, 3) == 'geo'){

        //geo group name
        $codeGroup = substr($dataPassedFromSelectedOption, 3, strlen($dataPassedFromSelectedOption));

        $GroupTypeCountry = new GroupTypeCountry();

        $dataName = GroupTypeCountry::find()->where([
          'code_group' => $codeGroup
          ])->one();

        $dataSend['titleName'] = $dataName->group_descBI;

        // print_r('<pre>');
        // print_r($dataSend['titleName']);
        // print_r('</pre>');

        // bar chart START
        //import
        $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
        FROM [data_detail] DD
          JOIN data_trade DT
            ON DD.iddata_trade = DT.iddata_trade
          JOIN code_trade CT
            ON DT.iddata_trade = CT.iddata_trade
          JOIN trade_number TN
            ON CT.idtrade_number = TN.idtrade_number
          JOIN country C
            ON DT.idcountry = C.idcountry
          JOIN group_country GC
            ON C.idcountry = GC.idcountry
          JOIN group_type_country GTC
            ON GC.idgroup = GTC.idgroup
        WHERE TN.idcode_type = 7
        AND GTC.code_group = '.$codeGroup.'
        AND DD.tradeClass IN (1,4)
        AND DD.data_year = '.$dataPassedFromSelectedOption2.'
        GROUP BY TN.idtrade_number
        ORDER BY TN.idtrade_number ASC';
        $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

        //export
        $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
        FROM [data_detail] DD
          JOIN data_trade DT
            ON DD.iddata_trade = DT.iddata_trade
          JOIN code_trade CT
            ON DT.iddata_trade = CT.iddata_trade
          JOIN trade_number TN
            ON CT.idtrade_number = TN.idtrade_number
          JOIN country C
            ON DT.idcountry = C.idcountry
          JOIN group_country GC
            ON C.idcountry = GC.idcountry
          JOIN group_type_country GTC
            ON GC.idgroup = GTC.idgroup
        WHERE TN.idcode_type = 7
        AND GTC.code_group = '.$codeGroup.'
        AND DD.tradeClass IN (2,3,5,6)
        AND DD.data_year = '.$dataPassedFromSelectedOption2.'
        GROUP BY TN.idtrade_number
        ORDER BY TN.idtrade_number ASC';
        $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
        // bar chart END
      }
        else if(substr($dataPassedFromSelectedOption, 0, 3) == 'eco'){

          //eco group name
          $codeGroup = substr($dataPassedFromSelectedOption, 3, strlen($dataPassedFromSelectedOption));

          $GroupTypeCountry = new GroupTypeCountry();

          $dataName = GroupTypeCountry::find()->where([
            'code_group' => $codeGroup
            ])->one();

          $dataSend['titleName'] = $dataName->group_descBI;

          // print_r('<pre>');
          // print_r($dataSend['titleName']);
          // print_r('</pre>');

          // bar chart START
          //import
          $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
          FROM [data_detail] DD
            JOIN data_trade DT
              ON DD.iddata_trade = DT.iddata_trade
            JOIN code_trade CT
              ON DT.iddata_trade = CT.iddata_trade
            JOIN trade_number TN
              ON CT.idtrade_number = TN.idtrade_number
            JOIN country C
              ON DT.idcountry = C.idcountry
            JOIN group_country GC
              ON C.idcountry = GC.idcountry
            JOIN group_type_country GTC
              ON GC.idgroup = GTC.idgroup
          WHERE TN.idcode_type = 7
          AND GTC.code_group = '.$codeGroup.'
          AND DD.tradeClass IN (1,4)
          AND DD.data_year = '.$dataPassedFromSelectedOption2.'
          GROUP BY TN.idtrade_number
          ORDER BY TN.idtrade_number ASC';
          $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

          //export
          $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
          FROM [data_detail] DD
            JOIN data_trade DT
              ON DD.iddata_trade = DT.iddata_trade
            JOIN code_trade CT
              ON DT.iddata_trade = CT.iddata_trade
            JOIN trade_number TN
              ON CT.idtrade_number = TN.idtrade_number
            JOIN country C
              ON DT.idcountry = C.idcountry
            JOIN group_country GC
              ON C.idcountry = GC.idcountry
            JOIN group_type_country GTC
              ON GC.idgroup = GTC.idgroup
          WHERE TN.idcode_type = 7
          AND GTC.code_group = '.$codeGroup.'
          AND DD.tradeClass IN (2,3,5,6)
          AND DD.data_year = '.$dataPassedFromSelectedOption2.'
          GROUP BY TN.idtrade_number
          ORDER BY TN.idtrade_number ASC';
          $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
          // bar chart END
        }
          else{

            // retrieve country name from table country
            // $Country = new Country();

            // $countryName = Country::find()->where([
            //   'idcountry' => $dataPassedFromSelectedOption
            //   ])->one();
            //
            // $dataSend['titleName'] = $countryName->country_descriptionBI;

            $countryNameSQL = 'SELECT *
            FROM country
            WHERE idcountry = '.$dataPassedFromSelectedOption.'';
            $countryName = Country::findBySql($countryNameSQL)->one();

            $dataSend['titleName'] = $countryName->country_descriptionBI;

            /* getting import (tradeclass 1 & 4) total data trade START */

            $DataTrade = new DataTrade();
            // query iddata_trade where idcountry = $dummyIDcountry
            $sql = 'SELECT iddata_trade FROM data_trade WHERE idcountry = '.$dataPassedFromSelectedOption.'';
            $data_iddataTrade = DataTrade::findBySql($sql)->asArray()->all();

            // object holding queried DataTrade
            $dataSend['iddata_trade_fetch'] = $data_iddataTrade;

            // print_r('<pre>');
            // print_r($dataSend['iddata_trade_fetch']);
            // print_r('</pre>');

            // bar chart START
            //import
            $sqlImport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
            FROM [data_detail] DD
              JOIN data_trade DT
                ON DD.iddata_trade = DT.iddata_trade
              JOIN code_trade CT
                ON DT.iddata_trade = CT.iddata_trade
              JOIN trade_number TN
                ON CT.idtrade_number = TN.idtrade_number
              JOIN country C
                ON DT.idcountry = C.idcountry
            WHERE TN.idcode_type = 7
            AND C.idcountry = '.$dataPassedFromSelectedOption.'
            AND DD.tradeClass IN (1,4)
            AND DD.data_year = '.$dataPassedFromSelectedOption2.'
            GROUP BY TN.idtrade_number
            ORDER BY TN.idtrade_number ASC';
            $codeDescImport = DataDetail::findBySql($sqlImport2)->asArray()->all();

            //export
            $sqlExport2 = 'SELECT TN.idtrade_number, SUM(DD.data_trade) AS TotalDataTrade
            FROM [data_detail] DD
              JOIN data_trade DT
                ON DD.iddata_trade = DT.iddata_trade
              JOIN code_trade CT
                ON DT.iddata_trade = CT.iddata_trade
              JOIN trade_number TN
                ON CT.idtrade_number = TN.idtrade_number
              JOIN country C
                ON DT.idcountry = C.idcountry
            WHERE TN.idcode_type = 7
            AND C.idcountry = '.$dataPassedFromSelectedOption.'
            AND DD.tradeClass IN (2,3,5,6)
            AND DD.data_year = '.$dataPassedFromSelectedOption2.'
            GROUP BY TN.idtrade_number
            ORDER BY TN.idtrade_number ASC';
            $codeDescExport = DataDetail::findBySql($sqlExport2)->asArray()->all();
            // bar chart END
          }

/* do checking whether the selected option is single country/all/group end */

// echo json_encode($dataSend);
// return $this->render('displayGraph', $dataSend);

/* store import & export data fetch in new array (bar chart) start */
// import
$array1 = array();
// fill empty arrays with value 0
for($w = 0; $w < 10; $w++){
  $array1[$w] = 0;
}

// print_r('<pre>');
// print_r($array1);
// print_r('</pre>');

if($codeDescImport == NULL){
  $dataSend['codeDescImport'] = 0;
}else{
  // place 0 to any idtrade_number which are not in the array
  if(count($codeDescImport) == 10){
    // $dataSend['codeDescImport'] = $codeDescImport;
    for($u = 0; $u < 10; $u++){
      $array1[$u] = $codeDescImport[$u]['TotalDataTrade'];
    }
    $dataSend['codeDescImport'] = $array1;
  }else{
    for($x = 0; $x < count($codeDescImport); $x++){
      if($codeDescImport[$x]['idtrade_number'] == 7649){
        $array1[0] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7650){
        $array1[1] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7651){
        $array1[2] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7652){
        $array1[3] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7653){
        $array1[4] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7654){
        $array1[5] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7655){
        $array1[6] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7656){
        $array1[7] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      if($codeDescImport[$x]['idtrade_number'] == 7657){
        $array1[8] = $codeDescImport[$x]['TotalDataTrade'];
      }else
      {
        $array1[9] = $codeDescImport[$x]['TotalDataTrade'];
      }
    }
    $dataSend['codeDescImport'] = $array1;
  }
  // $dataSend['codeDescImport'] = $codeDescImport;
}

// print_r('<pre>');
// print_r($dataSend['codeDescImport']);
// print_r('</pre>');

// export

$array2 = array();
// fill empty arrays with value 0
for($v = 0; $v < 10; $v++){
  $array2[$v] = 0;
}

if($codeDescExport == NULL){
    $dataSend['codeDescExport'] = 0;
}
else{
  if(count($codeDescExport) == 10){
    for($t = 0; $t < 10; $t++){
      $array2[$t] = $codeDescExport[$t]['TotalDataTrade'];
    }
    $dataSend['codeDescExport'] = $array2;
  }else{
    for($x = 0; $x < count($codeDescExport); $x++){
      if($codeDescExport[$x]['idtrade_number'] == 7649){
        $array2[0] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7650){
        $array2[1] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7651){
        $array2[2] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7652){
        $array2[3] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7653){
        $array2[4] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7654){
        $array2[5] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7655){
        $array2[6] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7656){
        $array2[7] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      if($codeDescExport[$x]['idtrade_number'] == 7657){
        $array2[8] = $codeDescExport[$x]['TotalDataTrade'];
      }else
      {
        $array2[9] = $codeDescExport[$x]['TotalDataTrade'];
      }
    }
    $dataSend['codeDescExport'] = $array2;
  }
}
///////////////////////////////////////////2nd//////////////////////////////////

      // return $this->render('displayGraph2', $dataSend);
      // return $this->render('yeardisplaygraph', $dataSend);
      $html= $this->renderAjax('yeardisplaygraph',$dataSend);
      // // return Json::encode($html);
      // echo $html;

      if($key == 0){
          return $this->render('yeardisplaygraph', $dataSend);
      }
      else{
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $dataSend;
      }
    }


    public function actionCheckdataresult()
    {




        $request = Yii::$app->request;
        $datapost = $request->post('Trade');


        $timeframe=$datapost['timeframe'];



if($datapost){

$result=Trade::datafordatatradecheck($datapost);


$d['d']=$result;



}else{
    $d['serah'] =false;
 }


if(!empty($d['d'])){

if($timeframe=='year'){

        return $this->renderAjax('tradesearchviewcheckdata',$d);
    }elseif($timeframe=='month'){

        return $this->renderAjax('tradesearchviewcheckdata',$d);
    }
}else{

    echo 'Tiada Data';
}



}


 public  function actionDownloadexcelltrade()
    {




        $request = Yii::$app->request;
        $datapost =json_decode($request->post('Trade'),true);


if($datapost){


        $data=Trade::DataExcellTrade($datapost);

          $d['datatrademu']=$data;

          // echo "<pre>";
          // print_r($data);

          // echo "</pre>";



$filename = "Trade.xlsx";

if($datapost['timeframe']=='year'){


$table    =  $this->renderPartial('excelltrade',$d);
}else{



 $table    =  $this->renderPartial('excelltrademonth',$d);



}


// echo  $table;
// die();

// save $table inside temporary file that will be deleted later
$tmpfile = tempnam(sys_get_temp_dir(), 'html');
file_put_contents($tmpfile, $table);

// insert $table into $objPHPExcel's Active Sheet through $excelHTMLReader
$objPHPExcel     = new PHPExcel();
$excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
$objPHPExcel->getActiveSheet()->setTitle('any name you want'); // Change sheet's title if you want

unlink($tmpfile); // delete temporary file because it isn't needed anymore

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
header('Content-Disposition: attachment;filename='.$filename); // specify the download file name
header('Cache-Control: max-age=0');

// Creates a writer to output the $objPHPExcel's content
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$writer->save('php://output');
exit;



}





    }

public  function actionDownloadexcelltradetop()
    {




        $request = Yii::$app->request;
        $datapost =json_decode($request->post('Trade'),true);
        $trade =$request->post('Tradeclass');


if($datapost){


        $data=Trade::resultTradeTop($datapost,$trade);

          $d['datatrademu']=$data;






$filename = "Trade.xlsx";
 $table    =  $this->renderPartial('excelltradetop',$d);




// save $table inside temporary file that will be deleted later
$tmpfile = tempnam(sys_get_temp_dir(), 'html');
file_put_contents($tmpfile, $table);

// insert $table into $objPHPExcel's Active Sheet through $excelHTMLReader
$objPHPExcel     = new PHPExcel();
$excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
$objPHPExcel->getActiveSheet()->setTitle('any name you want'); // Change sheet's title if you want

unlink($tmpfile); // delete temporary file because it isn't needed anymore

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
header('Content-Disposition: attachment;filename='.$filename); // specify the download file name
header('Cache-Control: max-age=0');

// Creates a writer to output the $objPHPExcel's content
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$writer->save('php://output');
exit;



}





    }


public  function actionCsvtrade()
    {




        $request = Yii::$app->request;
        $datapost =json_decode($request->post('Trade'),true);
        $trade =$request->post('Tradeclass');

        //$SearchTradev2=  $this->SearchTradev2($datapost);

       // echo "<pre>";
       //  print_r($SearchTradev2);
       //  echo "</pre>";


       //  die();


if($datapost){


        $data=Trade::DataExcellTrade($datapost);


        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // // die();






        // die();

       $datav= $data['data'];
       $datayear= $data['header']['data'];

          $d['datatrademu']=$data;






        $headcsv=['Code','Desc'];


        if( $data['header']['option']!='all'  ){

            array_push($headcsv,'Country');

        }

        foreach ($datayear as $key => $rowyear) {
            foreach ($rowyear as $keyyear => $rowyear2) {
              array_push($headcsv,$key.' '.$rowyear2);
            }



        }




// echo "<pre>";
// print_r($data['data_des']);
// echo "</pre>";
// echo "<pre>";
// print_r($datav);
// echo "</pre>";
// die();
//         echo "<pre>";
// print_r($datayear);
// echo "</pre>";
// die();

$csv=[];

  array_push($csv,$headcsv);

$bil=0;
  foreach ($data['data_des'] as $key => $value) {


    foreach ($value as $key2 => $value2) {

        $keyvalue=$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['data_year'];



       //  array_push($csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']][$keyvalue],$data['des_value'][$value2['trade_number']]['desc_code'] );

          if( $data['header']['option']!='all'  ){

         //   array_push($csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][$keyvalue],$value2['country_descriptionBI'] );

    //   $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']]['name_country']=$value2['country_descriptionBI'];

 $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']]['trade_number']=$value2['trade_number'];
$csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']]['desc_code']=$data['des_value'][$value2['trade_number']]['desc_code'];


        }else{


 $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']]['trade_number']=$value2['trade_number'];
$csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']]['desc_code']=$data['des_value'][$value2['trade_number']]['desc_code'];

        }


        foreach ($datayear as $yearkey => $rowyear) {
             foreach ($rowyear as $yearkey2 => $rowyear3) {
                $keyvalue2=$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$yearkey;

          if( $data['header']['option']!='all'  ){

           //'$datav['.$key.']['.$value2['data_detail_type'].']['.$key2.']['.$yearkey.']['.$rowyear3.']'

            // array_push($csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][$keyvalue],
            //     $datav[$key][$value2['data_detail_type']][$key2][$yearkey][$rowyear3]);



           //$csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][]='$datav['.$key.']['.$value2['data_detail_type'].']['.$key2.']['.$value2['data_year'].']['.$yearkey.']['.$rowyear3.']';
   if($datapost['timeframe']=='year'){
     $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][]=$datav[$key][$value2['data_detail_type']][$key2][$yearkey][$rowyear3];

   }else{

    $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][]=$datav[$key][$value2['data_detail_type']][$key2][$value2['data_year']][$yearkey][$rowyear3];

   }




        }else{
           // array_push($csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']][$keyvalue],'$datav['.$key.']['.$value2['data_detail_type'].']['.$yearkey.']['.$rowyear3.']');

          //  $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']][$keyvalue2.$rowyear3]='$datav['.$key.']['.$value2['data_detail_type'].']['.$yearkey.']['.$rowyear3.']';

           if($datapost['timeframe']=='year'){
    $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']][$keyvalue2.$rowyear3]=$datav[$key][$value2['data_detail_type']][$yearkey][$rowyear3];

   }else{
    $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename']][$keyvalue2.$rowyear3]=$datav[$key][$value2['data_detail_type']][$value2['data_year']][$yearkey][$rowyear3];

   // $csv[$value2['trade_number'].$value2['data_detail_type'].$value2['code_typename'].$value2['name_country']][]=$datav[$key][$value2['data_detail_type']][$key2][$value2['data_year']][$yearkey][$rowyear3];

   }


               }


   // $csvs[]=$datav[$key][$key2][$yearkey][$rowyear3]
            ;


        }
        }

       // $a=array("red","green");


      // array_push($csv,$csvs);


    }

  }




// echo "<pre>";
// print_r($csv);
// echo "</pre>";
// // // echo implode( ',', $csv );
// // //  //echo $this-> arrayToCsv($csv);
//  die();
// // //  // $r= $this->arrayToCsv($data);

 // print_r($r);

   $filename = "Trade";

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$filename}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

   $this-> outputCSV($csv);


// $filename = "Trade.xlsx";
//  $table    =  $this->renderPartial('excelltradetop',$d);




// // save $table inside temporary file that will be deleted later
// $tmpfile = tempnam(sys_get_temp_dir(), 'html');
// file_put_contents($tmpfile, $table);

// // insert $table into $objPHPExcel's Active Sheet through $excelHTMLReader
// $objPHPExcel     = new PHPExcel();
// $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
// $excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
// $objPHPExcel->getActiveSheet()->setTitle('any name you want'); // Change sheet's title if you want

// unlink($tmpfile); // delete temporary file because it isn't needed anymore

// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
// header('Content-Disposition: attachment;filename='.$filename); // specify the download file name
// header('Cache-Control: max-age=0');

// // Creates a writer to output the $objPHPExcel's content
// $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $writer->save('php://output');
// exit;



}





    }






function arrayToCsv( array $fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $outputString = "";
    foreach($fields as $tempFields) {
        $output = array();
        foreach ( $tempFields as $field ) {
            // ADDITIONS BEGIN HERE
            if (gettype($field) == 'integer' || gettype($field) == 'double') {
                $field = strval($field); // Change $field to string if it's a numeric type
            }
            // ADDITIONS END HERE
            if ($field === null && $nullToMysqlNull) {
                $output[] = 'NULL';
                continue;
            }

            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                $field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            }
            $output[] = $field." ";
        }
        $outputString .= implode( $delimiter, $output )."\r\n";
    }
    return $outputString;
}



 function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        foreach($data as $val) {
            fputcsv($outputBuffer, $val);
        }
        fclose($outputBuffer);
    }







    public function SearchTradev2($datapost){

        $geogroup = empty($datapost['geogroup'])?'':$datapost['geogroup'];


        $typedigit =empty($datapost['typedigit'])?'':$datapost['typedigit'];
        $data_detail_type=CodeType::find()->where(['idcode_type'=>$typedigit])->one();
        $code_idcode =empty($datapost['code_idcode'])?'':$datapost['code_idcode'];

      if(!empty($datapost['code_idcode'])){
         $code_idcode=$datapost['code_idcode'];

      }elseif(!empty($datapost['code_idcodedigit9'])){

        $code_idcode=$datapost['code_idcodedigit9'];
      }else{
         $code_idcode='';
      }


        $tradeflow =empty($datapost['tradeflow'])?'':$datapost['tradeflow'];
        $timeframe =empty($datapost['timeframe'])?'':$datapost['timeframe'];
        if (!empty($datapost['rangeyear'])) {
            $rangeyear[] = $datapost['rangeyear'];
        }

        if (!empty($datapost['rangeyear2'])) {
            $rangeyear[] = $datapost['rangeyear2'];
        }


        if (!empty($datapost['rangeyeargeo'])) {
            $rangeyear[] = $datapost['rangeyeargeo'];
        }

        if (!empty($datapost['rangeyeargeo2'])) {
            $rangeyear[] = $datapost['rangeyeargeo2'];
        }

        if (!empty($datapost['rangeyearpartner'])) {
            $rangeyear[] = $datapost['rangeyearpartner'];
        }

        if (!empty($datapost['rangeyearpartner2'])) {
            $rangeyear[] = $datapost['rangeyearpartner2'];
        }

        if (!empty($datapost['rangeyeareco'])) {
            $rangeyear[] = $datapost['rangeyeareco'];
        }

        if (!empty($datapost['rangeyeareco2'])) {
            $rangeyear[] = $datapost['rangeyeareco2'];
        }


        $Pagescustom = Yii::$app->request->post('Page');
        $rangecode1 =empty($datapost['rangecode1'])?'':$datapost['rangecode1'];
        $rangecode2 =empty($datapost['rangecode2'])?'':$datapost['rangecode2'];
        $mothdata =empty($datapost['mothdata'])?'':$datapost['mothdata'];


         if(!empty($datapost['country'])){

         $country =empty($datapost['country'])?'':$datapost['country'];

      }elseif(!empty($datapost['country2'])){

        $country =empty($datapost['country2'])?'':$datapost['country2'];
      }else{
         $country ='';
      }















        $url = "http://" . $this->esHost . ":" . $this->esPort . "/trades/trade/_search?size=5000";
//set url
        $request = Yii::$app->request;
        $datapost = $request->post('Trade');


        //dapatkan type
        $codeType = \app\models\CodeType::find()
                        ->where(["=","idcode_type",$typedigit])
                        ->asArray()
                        ->all();



       /* print_r($datapost);
        die();
*/
        $aCountry = [];
        $hasCountry = "no";
        $bCountryAll = false;
        if(!empty($country)){
            if(strtolower($country[0])=="all")
                $bCountryAll=true;

            $hasCountry = "yes";

            if(!$bCountryAll){
                $queryCountry = \app\models\Country::find();
                $queryCountry->where(["in","idcountry",$country]);
                $aCountry = $queryCountry->asArray()
                            ->all();
            }
        }

        $tradeClass = \app\models\Tradeclass::find()
                        ->asArray()
                        ->all();

        $atradeClass = [];
        $atradeClass[] = "null";

        foreach ($tradeClass as $trc) {
             $atradeClass[] = $trc["trade"];
        }


        /*print_r($datapost);
        die();*/
        //$timeframe=$datapost['timeframe'];

        $keySearch = 0;

        $searchData = [];

        $code = $codeType[0]["code_typename"].$codeType[0]["code_typedigit"];

        if(!empty($datapost['code_idcode'])){
            $aCode = $code_idcode;
            //if(sizeof($aC))
            foreach ($aCode as $curCode) {
                $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['bool']['should'][]['match'][$code] = $curCode;
            }
            $keySearch++;
        }
        else{
            $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['range'][$code] = ["gte" => $rangecode1 ,
                                                                                                             "lte" => $rangecode2 ,
                                                                                                             "boost" => 2.0];
            $keySearch++;
        }

        if(sizeof($aCountry) > 0){
            if(!$bCountryAll){
                foreach ($aCountry as $curCountry) {
                    $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['bool']['should'][]['match']['country'] = $curCountry["name_country"];
                }
                $keySearch++;
            }
        }

        if($timeframe=="month"){
            $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['term']['year'] = $mothdata;
            $keySearch++;
        }else{

                for($i=$rangeyear[0]; $i <= $rangeyear[1]; $i++){
                    $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['bool']['should'][]['match']['year'] = $i;
                }

            $keySearch++;
        }

        if(sizeof($tradeflow)<2){
            for($i=1; $i < sizeof($atradeClass); $i++){
                if($atradeClass[$i]!=$tradeflow[0])
                    continue;

                $searchData['query']['constant_score']['filter']['bool']['must'][$keySearch]['bool']['should'][]['match']['tradeClass'] = $i;
            }
            $keySearch++;
        }


          echo "<pre>";
          //print_r($searchData);
           print_r($searchData);
          echo "</pre>";


          //   die();
        $data = $this->curlPost($url, $searchData);
        $details = $data->hits;
           echo "<pre>";
          //print_r($searchData);
           print_r($data->hits);
          echo "</pre>";


             die();
        if($details->total > 0){
            //ada data baru looping
            $trades = $details->hits;

            $dataEs = [];

            $trade = [];
            foreach ($trades as $rowtrade) {
                $trade = (array)$rowtrade->_source;

                $aCodeDesc = (array)$code_desc;
                /*print_r($aCodeDesc);
                die();*/

                if($timeframe!="month"){
                    $start = 1;
                    $end = 1;
                }else{
                    $start = 1;
                    $end = 12;
                }

                for($i=$start; $i <= $end; $i++){
                    $constData = [];
                    $constData['Product Code'] = $trade[$code];
                    //$constData['Type No'] = $data_type;
                    $constData['Trade'] = ucwords($atradeClass[$trade['tradeClass']]);
                    $constData['Country'] = $trade['country'];
                    $constData['Code Description'] = $aCodeDesc[$code]->desc;
                    if($timeframe!="month"){
                        $constData['Year'] =  $trade['year'];
                        $constData['Value'] = $trade['vYear'];
                    }else{
                        $constData['Month'] =  $i;
                        $constData['Value'] = $trade['v'.$i];
                    }

                    array_push($dataEs, $constData);
                }
            }


                  echo "<pre>";
          print_r($dataEs);

          echo "</pre>";
                die();


            return ["timeframe"=>$timeframe, "hasCountry"=> $hasCountry, "data"=>$dataEs];
            //return $dataEs;
        }else{
            return [];
        }
    }




public function curlPost($url, $data){
        $res = Yii::$app->response;
        $res->format = Response::FORMAT_JSON;
        $res->formatters = [
            Response::FORMAT_JSON => [
                'class' => 'yii\web\JsonResponseFormatter',
                'prettyPrint' => true,
            ]
        ];

        $search_json = \GuzzleHttp\json_encode($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $search_json);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);
        curl_close($curl);
        return \GuzzleHttp\json_decode($data);
    }




    public  function actionDownloadexcelltradegroup()
    {




        $request = Yii::$app->request;
        $datapost =json_decode($request->post('Trade'),true);


if($datapost){


        $data=Trade::DataExcellTrade($datapost);

          $d['datatrademu']=$data;

          // echo "<pre>";
          // print_r($data);

          // echo "</pre>";



$filename = "Trade.xlsx";

if($datapost['timeframe']=='year'){


$table    =  $this->renderPartial('excelltrade',$d);
}else{



 $table    =  $this->renderPartial('excelltrademonth',$d);



}


// echo  $table;
// die();

// save $table inside temporary file that will be deleted later
$tmpfile = tempnam(sys_get_temp_dir(), 'html');
file_put_contents($tmpfile, $table);

// insert $table into $objPHPExcel's Active Sheet through $excelHTMLReader
$objPHPExcel     = new PHPExcel();
$excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
$objPHPExcel->getActiveSheet()->setTitle('any name you want'); // Change sheet's title if you want

unlink($tmpfile); // delete temporary file because it isn't needed anymore

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
header('Content-Disposition: attachment;filename='.$filename); // specify the download file name
header('Cache-Control: max-age=0');

// Creates a writer to output the $objPHPExcel's content
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$writer->save('php://output');
exit;



}





    }


}
