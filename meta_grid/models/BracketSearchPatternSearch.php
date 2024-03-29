<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BracketSearchPattern;

/**
 * BracketSearchPatternSearch represents the model behind the search form about `app\models\BracketSearchPattern`.
 */
class BracketSearchPatternSearch extends BracketSearchPattern
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_bracket_id'], 'integer'],
            [['uuid', 'searchPattern'], 'safe'],
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
        $query = BracketSearchPattern::find();

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
            'fk_object_type_id' => $this->fk_object_type_id,
            'fk_bracket_id' => $this->fk_bracket_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'searchPattern', $this->searchPattern]);

        return $dataProvider;
    }
}
