<?php

namespace app\modules\orders\models;

use yii\db\ActiveRecord;

/**
 * ServiceSearch
 */
class ServiceSearch extends Services
{
    /**
     * Services with orders counters
     * @return array|ActiveRecord[]
     */
    public static function listWithOrdersCounters()
    {
        return Services::find()->alias('s')
            ->select(['s.id', 's.name', 'COUNT(orders.id) AS orders_cnt'])
            ->joinWith('orders', false)
            ->groupBy('s.id')
            ->orderBy(['COUNT(orders.id)' => SORT_DESC])
            ->all();
    }
}