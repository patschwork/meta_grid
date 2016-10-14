<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BracketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Brackets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-index">

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
    'modelClass' => 'Bracket',
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
             		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name);
             		},
            ],
            'name:ntext',
            'description:html',
            [
             'label' => 'Attribute',
             'value' => function($model) {
             		return $model->fk_attribute_id == "" ? $model->fk_attribute_id : (isset($_GET["searchShow"]) ? $model->fkAttribute->name . ' [' . $model->fk_attribute_id . ']' : $model->fkAttribute->name);
             		},
            ],
            [
             'label' => 'Object Type As Search Filter',
             'value' => function($model) {
             		return $model->fk_object_type_id_as_searchFilter == "" ? $model->fk_object_type_id_as_searchFilter : (isset($_GET["searchShow"]) ? $model->fkObjectTypeAsSearchFilter->name . ' [' . $model->fk_object_type_id_as_searchFilter . ']' : $model->fkObjectTypeIdAsSearchFilter->name);
             		},
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
