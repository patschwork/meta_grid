<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VToolSearchinterface;

/**
 * ToolSearch represents the model behind the search form about `VToolSearchinterface`.
 */
class ToolSearch extends VToolSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_tool_type_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['uuid', 'tool_name', 'vendor', 'version', 'comment'], 'safe'],
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
        $query = VToolSearchinterface::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			        'pagination' => [
						'pageSize' => 100,
					]
        ]);

		// this is the case, when the user makes his own filter criteria.
		if (array_key_exists(\yii\helpers\StringHelper::basename(get_class($this)),$params) === true)
		{
			$this->load($params);
		}
		else
		{
			$this->load(array_replace_recursive(\vendor\meta_grid\helper\PerspectiveHelper::SearchModelFilter($this), $params));
		}		
		
		// If select2-multiple option is true, the validation fails... 
        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        $query->andFilterWhere([
            'id' => $this->id,
            'fk_tool_type_id' => $this->fk_tool_type_id,
            'fk_object_persistence_method_id' => $this->fk_object_persistence_method_id,
            'fk_datamanagement_process_id' => $this->fk_datamanagement_process_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'tool_name', $this->tool_name])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        // {... T363
        $db = Yii::$app->db;
        $db->cache(function () use ($dataProvider) {
            $dataProvider->prepare();
        });
        // T363 ...}

        return $dataProvider;
    }
}
