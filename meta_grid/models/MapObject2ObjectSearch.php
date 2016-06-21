<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MapObject2Object;

/**
 * MapObject2ObjectSearch represents the model behind the search form about `app\models\MapObject2Object`.
 */
class MapObject2ObjectSearch extends MapObject2Object
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2'], 'integer'],
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
        $query = MapObject2Object::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ref_fk_object_id_1' => $this->ref_fk_object_id_1,
            'ref_fk_object_type_id_1' => $this->ref_fk_object_type_id_1,
            'ref_fk_object_id_2' => $this->ref_fk_object_id_2,
            'ref_fk_object_type_id_2' => $this->ref_fk_object_type_id_2,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
