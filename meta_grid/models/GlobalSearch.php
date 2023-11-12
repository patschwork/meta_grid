<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VAllObjectsUnion;
use yii\helpers\VarDumper;

/**
 * GlobalSearch represents the model behind the search form about `VAllObjectsUnion`.
 */
class GlobalSearch extends VAllObjectsUnion 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id'], 'integer'],
            [['name', 'object_type_name', 'listvalue_1', 'listvalue_2', 'listkey', 'fk_client_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dependency = new \yii\caching\DbDependency();
        $dependency->sql="SELECT max(log_datetime) FROM v_LastChangesLog_List";
        

		$query = VAllObjectsUnion::find()->cache(NULL, $dependency);        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->limit(1000),
			        // 'pagination' => [
					// 	'pageSize' => 100,
					// ]
                    'pagination' => false
        ]);

        $this->load($params);

        if (count($params)<=1)
        {
            $Utils = new \vendor\meta_grid\helper\Utils();
            if ($Utils->get_app_config("globalsearch_init_empty") === 1)
            {
                $query->where('0=1');
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $search_term = '%'.str_replace("*", "%", $this->name).'%';        
        $query->orFilterWhere(['like', 'name', $search_term, false])
              ->orFilterWhere(['like', 'detail_1_content', $search_term, false])
              ->orFilterWhere(['like', 'detail_2_content', $search_term, false])
              ->orFilterWhere(['like', 'detail_3_content', $search_term, false])
              ->orFilterWhere(['like', 'detail_4_content', $search_term, false])
              ->orFilterWhere(['like', 'detail_5_content', $search_term, false])
              ->orFilterWhere(['like', 'description', $search_term, false])
            ;


        $query->andFilterWhere([
            'id' => $this->id,
            'fk_object_type_id' => $this->fk_object_type_id,
            'fk_project_id' => $this->fk_project_id,
        ]);


        $query
            ->andFilterWhere(['like', 'listvalue_1', $this->listvalue_1])
            ->andFilterWhere(['like', 'listvalue_2', $this->listvalue_2])
            ->andFilterWhere(['like', 'listkey', $this->listkey])
            ->andFilterWhere(['like', 'fk_client_id', $this->fk_client_id])
            ;


        $query->andFilterWhere(['in', 'object_type_name', $this->object_type_name]);
        
        return $dataProvider;
    }
}
