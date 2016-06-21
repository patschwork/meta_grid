<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DataDeliveryObjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Data Delivery Objects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-delivery-object-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
// Das ist nicht der Yii2-Way, ... @ToDo
if (isset($_GET["searchShow"]))
{
	echo $this->render('_search', ['model' =>$searchModel]);
}
else
{
	echo "<a class='btn btn-default' href='index.php?r=".$_GET["r"]."&searchShow=1'>Advanced Search</a></br></br>";
}
?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Data Delivery Object',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'label' => 'Client',
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name;
             		},
            ],
            [
             'label' => 'Project',
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name;
             		},
            ],
            'name:ntext',
            'description:html',
            [
             'label' => 'Tool',
             'value' => function($model) {
             		return $model->fk_tool_id == "" ? $model->fk_tool_id : isset($_GET["searchShow"]) ? $model->fkTool->tool_name . ' [' . $model->fk_tool_id . ']' : $model->fkTool->tool_name;
             		},
            ],
            [
             'label' => 'Data Delivery Type',
             'value' => function($model) {
             		return $model->fk_data_delivery_type_id == "" ? $model->fk_data_delivery_type_id : isset($_GET["searchShow"]) ? $model->fkDataDeliveryType->name . ' [' . $model->fk_data_delivery_type_id . ']' : $model->fkDataDeliveryType->name;
             		},
            ],
/*            [
             'label' => 'Contact Group As Data Owner',
             'value' => function($model) {
             		return $model->fk_contact_group_id_as_data_owner == "" ? $model->fk_contact_group_id_as_data_owner : isset($_GET["searchShow"]) ? $model->fkContactGroupAsDataOwner->name . ' [' . $model->fk_contact_group_id_as_data_owner . ']' : $model->fkContactGroupAsDataOwner->name;
             		},
            ],
*/
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
