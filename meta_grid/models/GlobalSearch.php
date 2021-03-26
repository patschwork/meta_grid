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
		$query = VAllObjectsUnion::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			        'pagination' => [
						'pageSize' => 100,
					]
        ]);

        $this->load($params);

        if (count($params)<=1)
        {
            if (\vendor\meta_grid\helper\Utils::get_app_config("globalsearch_init_empty") === 1)
            {
                $query->where('0=1');
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_object_type_id' => $this->fk_object_type_id,
            'fk_project_id' => $this->fk_project_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'listvalue_1', $this->listvalue_1])
            ->andFilterWhere(['like', 'listvalue_2', $this->listvalue_2])
            ->andFilterWhere(['like', 'listkey', $this->listkey])
            ->andFilterWhere(['like', 'fk_client_id', $this->fk_client_id]);


        $query->andFilterWhere(['in', 'object_type_name', $this->object_type_name]);
            
        return $dataProvider;
    }
}
