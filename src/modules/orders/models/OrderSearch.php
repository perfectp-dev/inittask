<?php

namespace app\modules\orders\models;

use Yii;
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
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_IN_PROGRESS,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELED,
                self::STATUS_ERROR
            ]],
            [['mode'], 'in', 'range' => [self::MODE_MANUAL, self::MODE_AUTO]],
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
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Orders::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

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

    /**
     * Total Orders Counter
     * @return int
     */
    public static function allCount()
    {
        return Orders::find()->count();
    }
}
