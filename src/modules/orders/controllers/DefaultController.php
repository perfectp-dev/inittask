<?php

namespace app\modules\orders\controllers;

use Yii;
use app\modules\orders\models\OrderSearch;
use app\modules\orders\models\Services;
use app\modules\orders\models\Orders;
use yii\web\Controller;

/**
 * DefaultController - .
 */
class DefaultController extends Controller
{

    const PAGE_SIZE = 100;

    /**
     * Lists Orders.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = self::PAGE_SIZE;

        $services = Services::find()->alias('s')
            ->select(['s.id', 's.name', 'COUNT(orders.id) AS orders_cnt'])
            ->joinWith('orders', false)
            ->groupBy('s.id')
            ->orderBy(['COUNT(orders.id)' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => Orders::$statusesDictionary,
            'allOrdersCount' => Orders::find()->count(),
            'services' => $services,
            'modes' => Orders::$modesDictionary,
            'pageSize' => self::PAGE_SIZE,
        ]);
    }

    /**
     * Save Orders List.
     */
    public function actionSave()
    {
        ini_set('memory_limit', '1024M');

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false;

        $data = implode(';', $searchModel->attributeLabels()) . "\r\n";

        $model = $dataProvider->getModels();
        foreach ($model as $value) {
            $data .= $value->id .
                ';' . $value->userFullName .
                ';' . $value->link .
                ';' . $value->quantity .
                ';' . $value->service->name . '(' . $value->service->ordersCount . ')' .
                ';' . $value->statusName .
                ';' . $value->modeName .
                ';' . Yii::$app->formatter->asDateTime($value->created_at, 'php:Y-m-d H:i:s') .
                "\r\n";
        }
        Yii::$app->response->sendContentAsFile($data, 'export_' . date('d.m.Y') . '.csv');
    }
}
