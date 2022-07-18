<?php

namespace app\modules\orders\controllers;

use Yii;
use app\modules\orders\models\OrderSearch;
use app\modules\orders\models\ServiceSearch;
use yii\web\Controller;

/**
 * DefaultController - .
 */
class OrdersController extends Controller
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

        $searchModel->load($this->request->queryParams, '');

        if (!$searchModel->validate()) {
            throw new \yii\web\HttpException(404, Yii::t('orders', 'Invalid parameters'));
        }

        $dataProvider = $searchModel->search();

        $dataProvider->pagination->pageSize = self::PAGE_SIZE;

        $services = ServiceSearch::listWithOrdersCounters();

        return $this->render('index', [
            'title' => Yii::t('orders', 'Orders'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => OrderSearch::$statusesDictionary,
            'allOrdersCount' => OrderSearch::allCount(),
            'services' => $services,
            'modes' => OrderSearch::$modesDictionary,
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

        $searchModel->load($this->request->queryParams, '');

        if (!$searchModel->validate()) {
            throw new \yii\web\HttpException(404, Yii::t('orders', 'Invalid parameters'));
        }

        $dataProvider = $searchModel->search();

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
