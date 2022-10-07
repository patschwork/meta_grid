<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\DbTableField */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Fields'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="db-table-field-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
	<?= Yii::$app->user->identity->isAdmin || (Yii::$app->User->can('create-dbtablefield'))  ? Html::a(Yii::t('app', 'Update table and fields'), ['dbtablefieldmultipleedit/update', 'id' => $model->fk_db_table_id, '#' => $model->id], ['class' => 'btn btn-primary']) : "" ?>	

		<?php
		    $Utils = new \vendor\meta_grid\helper\Utils();
			$db_table_show_buttons_for_different_object_type_updates = $Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");
			if ($db_table_show_buttons_for_different_object_type_updates == 1) 
			{
				echo Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtablefield')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "";
			}
		?>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-dbtablefield')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : "" ?>
	
    </p>

<?php
$TagsWidget = \vendor\meta_grid\tag_select\TagSelectWidget::widget(
	[
		'object_id' => $model->id,
		'object_type_id' => $model->fk_object_type_id,
		'user_id' => \Yii::$app->getUser()->id,
		'project_id' => $model->fkProject->id
	]);
?>	

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
             'label' => Yii::t('app', 'Tags'),
             'value' => $TagsWidget,
             'format' => 'raw',
            ],
            'id',
            'uuid:ntext',
            'fk_object_type_id',
            [
             'label' => Yii::t('app', 'Client'),
             'value' =>              		    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject->fkClient === NULL ? Yii::t('app', "Can't lookup the client name (for project {fk_project_id})", ['fk_project_id' => $model->fk_project_id]) : $model->fkProject->fkClient->name)
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>         	    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkProject', 'this_id' => $model->fk_project_id]) : $model->fkProject->name)
            ],
            'name:ntext',
            'description:html',
            [
             'label' => Yii::t('app', 'Db Table'),
             'value' => Html::a($model->fk_db_table_id == "" ? $model->fk_db_table_id : $model->fkDbTable->name, ['dbtable/view', 'id' => $model->fk_db_table_id], ['class' => 'btn btn-default']),
             'format' => 'raw'
            ],
            'datatype:ntext',
            'bulk_load_checksum:ntext',
            'is_PrimaryKey:boolean',
            'is_BusinessKey:boolean',
            'is_GDPR_relevant:boolean',
        ],
    ]) ?>

	
	
	
	
	
    	<?php
	
			$bracket_widget = \vendor\meta_grid\bracket_list\BracketListWidget::widget(
					    	[
				  				'object_type' => 'db_table_field',
					    		'fk_object_type_id' => $model->fk_object_type_id,
					    		'fk_object_id' => $model->id,
					    		'show_Matches' => true  
					    	]);
        
		// Wenn keine Bracket Elemente gefunden werden, liefert das Widget nichts zurück.
		// In diesem Fall soll der Tab deaktivert werden, um kenntlich zu machen, das keine Einträge existieren
        $bracket_tab_disabled = "";
        if ($bracket_widget=="") $bracket_tab_disabled = 'disabled';
			
			// {... Kommentierung pro Object
		// 		autogeneriert ueber gii/CRUD
		$objectcommentSearchModel = new app\models\ObjectcommentSearch();
        
        $query = app\models\Objectcomment::find();
        $objectcommentDataProvider = new ActiveDataProvider([
        		'query' => $query,
				'pagination' => false,
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $model->id,
        		'ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
				'pagination' => false,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $model->id,
        		'filter_ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);

		     
        
		echo Tabs::widget([
			'items' => 
			[
				[
					'label' => Yii::t('app', 'Comments'),
					'content' => $this->render('../objectcomment/_index_external', [
								    'searchModel' => $objectcommentSearchModel,
            						'dataProvider' => $objectcommentDataProvider,
								    ]),
					'active' => true,
					'options' => ['id' => 'tabComments']  // important for shortcut
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapper/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false,
					'options' => ['id' => 'tabMapping']  // important for shortcut
				],
					[
				'headerOptions' => [
					'class'=> $bracket_tab_disabled
				],
				
				'label' => Yii::t('app', 'Brackets'),
				'content' => $bracket_widget,
				'options' => ['id' => 'tabBracket']  // important for shortcut
			],
				],
		]);
		// Kommentierung pro Object ...}
	
	    		
	?>  
    
    
</div>
