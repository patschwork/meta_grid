<?php 
namespace vendor\meta_grid\bracket_list;

use yii;
use yii\base\Widget;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use kartik\select2\Select2;

use app\models\VBracketDefinitions;
use yii\helpers\VarDumper;
use app\models\base\DbDatabase;

use yii\grid\GridView;
use yii\data\ArrayDataProvider;

class BracketListWidget extends Widget
{
    public $object_type;
    public $fk_object_type_id;
    public $fk_object_id;
    public $show_Matches; 
	public $title_head;
    
    private function getBracketList()
    {
    	$model = new VBracketDefinitions();

		$items = $model::find()->all();
		return $items;
    }
    
    private function getBracketMatches($items, $object_type, $fk_object_id, $fk_object_type_id)
    {
    	$itemList = array();
    	foreach($items as $item)
    	{
    		if ($item[$object_type.'_id'] == $fk_object_id && $item[$object_type.'_fk_object_type_id'] == $fk_object_type_id)
    		{
    			$itemList[$item->fk_bracket_id]['bracket_name']=$item->bracket_name;
    			$itemList[$item->fk_bracket_id]['bracket_searchpattern']=$item->bracket_searchpattern;
    		}
    	}
    	return $itemList;
    }
    
    private function getOtherItems($items, $bracketMatchesItems)
    {
    	$itemList = array();
    	foreach($bracketMatchesItems as $key => $item)
    	{
    		$fk_bracket_id = $key;
    		foreach($items as $item)
    		{
    			if ($item['fk_bracket_id'] == $fk_bracket_id)
    			{
    				$itemList[$item->db_table_field_fk_object_type_id][$item->db_table_field_id]=$item->db_table_name.";".$item->db_table_field_name.";".$item->db_table_id;
    			}
    		}
    	}
    	
    	return $itemList;
	}
        
//     private $_assetBundle;
    
    public function init()
    {
        parent::init();
        if (!$this->object_type === null) {
        	$this->object_type = -99;
        }
    }

    public function run()
    {
    	parent::run();
    	BracketListAsset::register($this->getView());
    	
    	$object_type=$this->object_type;
    	$fk_object_type_id=$this->fk_object_type_id;
    	$fk_object_id=$this->fk_object_id;
    	
    	$items = $this->getBracketList();
    	
    	$bracketMatchesItems = $this->getBracketMatches($items, $object_type, $fk_object_id, $fk_object_type_id);
    	
     	$getOtherItems = $this->getOtherItems($items, $bracketMatchesItems);
		
     	
     	if ($this->title_head!="")
     	{
     		echo "<h1>".$this->title_head."</h1>";
     	}
		
     	$bracket_matches_arr = array();
     	
     	if (sizeof($bracketMatchesItems)>0)
     	{
     		echo "<br>";
     		foreach ($bracketMatchesItems as $id => $bracket)
     		{
     			$bracket_title = $bracket['bracket_name']. " (".$bracket['bracket_searchpattern'].")";
     			 
     			echo Html::a(Yii::t('app', $bracket_title), ['bracket/view', 'id' => $id], ['class' => 'btn btn-default']);
     			echo " ";
     		}
     		echo "<br>";
     		echo "<br>";
     		 
     		$show_Matches=false;
     		if (isset($this->show_Matches))
     		{
     			$show_Matches=$this->show_Matches;
     		}
     		
     		
     		if ($show_Matches == true)
     		{
     			foreach ($getOtherItems as $fk_object_type_id => $items)
     			{
     				$object_type_name = "";
     				if ($fk_object_type_id == 5) $object_type_name="dbtablefield";
     		
     				foreach ($items as $fk_object_id => $item)
     				{
     					$url_title = explode(";",$item)[0]." / ".explode(";",$item)[1];

						 if ($fk_object_type_id == 5)
						{
							$url_title = '<a href="?r=dbtable/view&id='.explode(";",$item)[2].'">' .explode(";",$item)[0].'</a>'." / ".explode(";",$item)[1];
						}
								 
     					$bracket_matches_arr[$fk_object_id.$fk_object_type_id]
 							= [
 									'object_type_name' => $object_type_name,
 									'item' => $url_title,
 									'url' => [$object_type_name.'/view', 'id' => $fk_object_id]
 							]; 
     				}
     			}
     			
     			$provider = new ArrayDataProvider([
     					'allModels' => $bracket_matches_arr,
     					'sort' => [
     							'attributes' => ['object_type_name', 'item'],
     					],
     					'pagination' => [
     							'pageSize' => 250,
     					],
     			]);

     			echo GridView::widget([
     					'dataProvider' => $provider,
     					'columns' => 
     					[
     							[
	     							'class' => 'yii\grid\ActionColumn',
	     							'template' => '{view}',
	     							'buttons' => [
	     									'info' => function ($url, $model) {
	     										return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', $url, [
	     												'title' => Yii::t('app', 'Info'),
	     										]);
	     									}
	     							],
	     							'urlCreator' => function ($action, $model, $key, $index) {
	     								if ($action === 'view') {
	     									$url = $model['url'];
	     									return $url;
	     								}
	     							}
     							],
     							'object_type_name', 
     							'item:raw',
     					]
     			]);
     		}
     	}
    }

}
?>