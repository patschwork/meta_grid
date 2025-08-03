<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VDataTransferTypeSearchinterface;

/**
 * DataTransferTypeSearch represents the model behind the search form about `VDataTransferTypeSearchinterface`.
 */
class DataTransferTypeSearch extends VDataTransferTypeSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['uuid', 'name', 'description', 'source_definition_language'], 'safe'],
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
        $query = VDataTransferTypeSearchinterface::find();        
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
            'fk_object_persistence_method_id' => $this->fk_object_persistence_method_id,
            'fk_datamanagement_process_id' => $this->fk_datamanagement_process_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'source_definition_language', $this->source_definition_language]);

        // {... T363
        $db = Yii::$app->db;
        $db->cache(function () use ($dataProvider) {
            $dataProvider->prepare();
        });
        // T363 ...}

        return $dataProvider;
    }
}
