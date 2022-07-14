<?php

namespace app\modules\orders\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\orders\models\Orders;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * OrderSearch represents the model behind the search form of `app\modules\orders\models\Orders`.
 */
class OrderSearch extends Orders
{

    public $search;
    public $search_type;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'safe'],
            [['status'], 'in', 'range' => [0, 1, 2, 3, 4]],
            [['mode'], 'in', 'range' => [0, 1, 2]],
            [['search'], 'safe'],
            [['search_type'], 'integer'],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
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
        $query = Orders::find()->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // Status, service & mode filters
        $query->andFilterWhere([
            'status' => $this->status,
            'service_id' => $this->service_id,
            'mode' => $this->mode,
        ]);

        // Search
        if (isset($this->search_type)) {

            switch ($this->search_type) {

                case 0:
                    // by Order ID
                    $query->andFilterWhere(['id' => (int)$this->search]);
                    break;

                case 1:
                    // by Link
                    $query->andFilterWhere(['like', 'link', $this->search]);
                    break;

                case 2:

                    $user_ids = ArrayHelper::getColumn(
                        Users::find()->where(['like', 'CONCAT(first_name, " ", last_name)', $this->search])
                            ->select('id')
                            ->asArray()
                            ->all(),
                        'id');
//

                    if (empty($user_ids)) {
                        $query->where('0=1');
                    } else {
                        $query->andFilterWhere(['user_id' => $user_ids]);
                    }
                    break;
            }
        }

        return $dataProvider;
    }
}
