<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VKeyfigureSearchinterface;

/**
 * KeyfigureSearch represents the model behind the search form about `VKeyfigureSearchinterface`.
 */
class KeyfigureSearch extends VKeyfigureSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id', 'fk_deleted_status_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['uuid', 'name', 'description', 'formula', 'aggregation', 'character', 'type', 'unit', 'value_range', 'source_definition', 'source_definition_language', 'source_comment'], 'safe'],
            [['cumulation_possible'], 'boolean'],
            [['fk_client_id'], 'integer'],
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
        $query = VKeyfigureSearchinterface::find();        
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
            'fk_object_type_id' => $this->fk_object_type_id,
            'fk_project_id' => $this->fk_project_id,
            'fk_client_id' => $this->fk_client_id,
            'cumulation_possible' => $this->cumulation_possible,
            'fk_deleted_status_id' => $this->fk_deleted_status_id,
            'fk_object_persistence_method_id' => $this->fk_object_persistence_method_id,
            'fk_datamanagement_process_id' => $this->fk_datamanagement_process_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'formula', $this->formula])
            ->andFilterWhere(['like', 'aggregation', $this->aggregation])
            ->andFilterWhere(['like', 'character', $this->character])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'value_range', $this->value_range])
            ->andFilterWhere(['like', 'source_definition', $this->source_definition])
            ->andFilterWhere(['like', 'source_definition_language', $this->source_definition_language])
            ->andFilterWhere(['like', 'source_comment', $this->source_comment]);

        // {... T363
        $db = Yii::$app->db;
        $db->cache(function () use ($dataProvider) {
            $dataProvider->prepare();
        });
        // T363 ...}

        return $dataProvider;
    }
}
