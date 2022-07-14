<?php

namespace app\modules\orders\controllers;

use app\modules\orders\models\OrderSearch;
use app\modules\orders\models\Services;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Orders model.
 */
class DefaultController extends Controller
{

    /**
     * Lists all Orders models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $services = Services::find()->orderBy('name')->asArray()->all();

        $dataProvider->pagination->pageSize = 4;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'services' => $services,
        ]);
    }
}
