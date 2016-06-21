<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DBTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dbtables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dbtable-index">

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
    'modelClass' => 'Dbtable',
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
            'location:ntext',
            [
             'label' => 'Db Table Context',
             'value' => function($model) {
             		return $model->fk_db_table_context_id == "" ? $model->fk_db_table_context_id : (isset($_GET["searchShow"]) ? $model->fkDbTableContext->name . ' [' . $model->fk_db_table_context_id . ']' : $model->fkDbTableContext->name);
             		},
            ],
/*            [
             'label' => 'Db Table Type',
             'value' => function($model) {
             		return $model->fk_db_table_type_id == "" ? $model->fk_db_table_type_id : isset($_GET["searchShow"]) ? $model->fkDbTableType->name . ' [' . $model->fk_db_table_type_id . ']' : $model->fkDbTableType->name;
             		},
            ],
*/
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
