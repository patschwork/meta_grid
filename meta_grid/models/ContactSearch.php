<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VContactSearchinterface;

/**
 * ContactSearch represents the model behind the search form about `VContactSearchinterface`.
 */
class ContactSearch extends VContactSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_contact_group_id', 'fk_client_id', 'fk_deleted_status_id'], 'integer'],
            [['uuid', 'givenname', 'surname', 'email', 'phone', 'mobile', 'ldap_cn', 'description'], 'safe'],
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
        $query = VContactSearchinterface::find();        
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
            'fk_contact_group_id' => $this->fk_contact_group_id,
            'fk_client_id' => $this->fk_client_id,
            'fk_deleted_status_id' => $this->fk_deleted_status_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'givenname', $this->givenname])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'ldap_cn', $this->ldap_cn])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
