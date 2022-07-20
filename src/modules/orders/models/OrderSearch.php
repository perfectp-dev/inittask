<?php

namespace app\modules\orders\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * OrderSearch - orders list with filters
 */
class OrderSearch extends Orders
{
    public $service_orders_cnt;
    public $service_name;
    public $user_full_name;
    public $orders_cnt;

    public $search_type;
    public $search;

    // Search types
    const BY_ORDER_ID = 0;
    const BY_LINK = 1;
    const BY_USERNAME = 2;

    const PAGE_SIZE = 100;

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
            [['search_type'], 'integer'],
            [['search_type'], 'in', 'range' => [self::BY_ORDER_ID, self::BY_LINK, self::BY_USERNAME]],
            ['search', 'safe'],
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
        $query = self::find()->alias('o')
            ->select([
                'o.*',
                'service_name' => 's.service_name',
                'service_orders_cnt' => 's.orders_cnt',
                'user_full_name' => 'CONCAT(users.first_name, " ", users.last_name)'
            ])
            ->innerJoin('users', 'users.id = o.user_id')
            ->orderBy('o.id DESC');

        $query = $this->addFilters($query);

        $subQuery = self::find()->alias('o')
            ->select([
                'services.id',
                'service_name' => 'services.name',
                'COUNT(*) AS orders_cnt'
            ])
            ->innerJoin('services', 'services.id = o.service_id')
            ->innerJoin('users', 'users.id = o.user_id');

        $subQuery = $this->addFilters($subQuery);

        $subQuery->groupBy('services.id');

        $query = $query->leftJoin(['s' => $subQuery], 's.id = o.service_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getServicesWithOrdersCount()
    {
        $query = self::find()->alias('o')
            ->select(['service_id' => 'services.id', 'service_name' => 'services.name', 'COUNT(*) AS orders_cnt'])
            ->innerJoin('services', 'services.id = o.service_id')
            ->innerJoin('users', 'users.id = o.user_id');

        $query = $this->addFilters($query, true);

        $query->groupBy('service_id')
            ->orderBy(['orders_cnt'=>SORT_DESC]);

        return $query->all();
    }

    /**
     * Add filters to query
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function addFilters($query, $withoutService = false)
    {
        // Filter by status, service & mode
        $query->andFilterWhere([
            'status' => $this->status,
            'mode' => $this->mode,
        ]);
        if (!$withoutService) {
            $query->andFilterWhere(['service_id' => $this->service_id]);
        }

        // Search filters
        if (isset($this->search_type) && isset($this->search)) {

            switch ($this->search_type) {

                case self::BY_ORDER_ID:
                    $query->andFilterWhere(['o.id' => (int)$this->search]);
                    break;

                case self::BY_LINK:
                    $query->andFilterWhere(['like', 'link', $this->search]);
                    break;

                case self::BY_USERNAME:
                    $query->andFilterWhere(['like', 'CONCAT(users.first_name, " ", users.last_name)', $this->search]);
                    break;
            }
        }
        return $query;
    }

    /**
     * Date of created_at
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatedDateOnly()
    {
        return Yii::$app->formatter->asDateTime($this->created_at, 'php:Y-m-d');
    }

    /**
     * Time of created_at
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatedTimeOnly()
    {
        return Yii::$app->formatter->asDateTime($this->created_at, 'php:H:i:s');
    }

    /**
     * Related user
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * Related Service
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }


    /**
     * Modes name from dictionary
     * @return string
     */
    public function getModeName()
    {
        return $this->modes[$this->mode] ?: Yii::t('orders', 'search.mode.unknown');
    }

    /**
     * Statuses name from dictionary
     * @return string
     */
    public function getStatusName()
    {
        return $this->statuses[$this->status] ?: Yii::t('orders', 'search.status.unknown');
    }
}
