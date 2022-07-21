<?php

namespace orders\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use orders\models\OrderSearch;

/**
 * OrdersController - .
 */
class OrdersController extends Controller
{
    /**
     * Lists Orders.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();

        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'title' => Yii::t('orders', 'page.orders'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allOrdersCount' => $dataProvider->totalCount,
            'saveURL' => array_merge(['save'], $this->request->queryParams),
        ]);
    }

    /**
     * Save Orders List.
     * @throws HttpException if saving error
     */
    public function actionSave()
    {
        ini_set('memory_limit', '1024M');

        $searchModel = new OrderSearch();

        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination = false;

        $data = $searchModel->toCSV($dataProvider);

        Yii::$app->response->sendContentAsFile($data, 'export_' . date('d.m.Y') . '.csv');
    }
}
