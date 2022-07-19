<?php

namespace app\modules\orders\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use app\modules\orders\models\OrderSearch;
use app\modules\orders\models\ServiceSearch;

/**
 * DefaultController - .
 */
class OrdersController extends Controller
{
    /**
     * Lists Orders.
     * @return string
     * @throws HttpException if search parameters not valid
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();

        $searchModel->load($this->request->queryParams, '');

        if (!$searchModel->validate()) {
            $searchModel = new OrderSearch();
        }

        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'title' => Yii::t('orders', 'page.orders'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => OrderSearch::$statusesDictionary,
            'allOrdersCount' => OrderSearch::allCount(),
            'services' => ServiceSearch::listWithOrdersCounters(),
            'modes' => OrderSearch::$modesDictionary,
            'saveURL' => array_merge(['save'], Yii::$app->request->queryParams),
        ]);
    }

    /**
     * Save Orders List.
     * @throws HttpException if search parameters not valid
     */
    public function actionSave()
    {
        ini_set('memory_limit', '1024M');

        $searchModel = new OrderSearch();

        $searchModel->load($this->request->queryParams, '');

        if (!$searchModel->validate()) {
            $searchModel = new OrderSearch();
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
