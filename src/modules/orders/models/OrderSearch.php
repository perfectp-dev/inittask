<?php

namespace app\modules\orders\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * OrderSearch - orders list with filters
 */
class OrderSearch extends Orders
{

    public $search_type;
    public $search;

    // Search types
    const BY_ORDER_ID = 0;
    const BY_LINK = 1;
    const BY_USERNAME = 2;

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
            [['search_type'], 'in', 'range' => [self::BY_ORDER_ID, self::BY_LINK, self::BY_USERNAME]],
        ];
    }

    //Normalize GET params name
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Filter by status, service & mode
        $query->andFilterWhere([
            'status' => $this->status,
            'service_id' => $this->service_id,
            'mode' => $this->mode,
        ]);

        // Search filters
        if (isset($this->search_type) && isset($this->search)) {

            switch ($this->search_type) {

                case self::BY_ORDER_ID:
                    $query->andFilterWhere(['id' => (int)$this->search]);
                    break;

                case self::BY_LINK:
                    $query->andFilterWhere(['like', 'link', $this->search]);
                    break;

                case self::BY_USERNAME:
                    $user_ids = ArrayHelper::getColumn(
                        Users::find()
                            ->select('id')
                            ->where(['like', 'CONCAT(first_name, " ", last_name)', $this->search])
                            ->asArray()
                            ->all(),
                        'id'
                    );

                    $query->andWhere(['user_id' => $user_ids]);
                    break;
            }
        }

        return $dataProvider;
    }
}
