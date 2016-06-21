<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ToolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tools');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tool-index">

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
    'modelClass' => 'Tool',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'label' => 'Tool Type',
             'value' => function($model) {
             		return $model->fk_tool_type_id == "" ? $model->fk_tool_type_id : isset($_GET["searchShow"]) ? $model->fkToolType->name . ' [' . $model->fk_tool_type_id . ']' : $model->fkToolType->name;
             		},
            ],
            'tool_name:ntext',
            'vendor:ntext',
            'version:ntext',
            'comment:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
