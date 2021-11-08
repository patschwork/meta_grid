<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VMapObject2ObjectSearchinterface;

/**
 * MapObject2ObjectSearch represents the model behind the search form about `VMapObject2ObjectSearchinterface`.
 */
class MapObject2ObjectSearch extends VMapObject2ObjectSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2', 'fk_mapping_qualifier_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['uuid'], 'safe'],
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
        $query = VMapObject2ObjectSearchinterface::find();        
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
            'ref_fk_object_id_1' => $this->ref_fk_object_id_1,
            'ref_fk_object_type_id_1' => $this->ref_fk_object_type_id_1,
            'ref_fk_object_id_2' => $this->ref_fk_object_id_2,
            'ref_fk_object_type_id_2' => $this->ref_fk_object_type_id_2,
            'fk_mapping_qualifier_id' => $this->fk_mapping_qualifier_id,
            'fk_object_persistence_method_id' => $this->fk_object_persistence_method_id,
            'fk_datamanagement_process_id' => $this->fk_datamanagement_process_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
