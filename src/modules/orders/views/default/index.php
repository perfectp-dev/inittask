<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\orders\models\Orders;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\orders\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $services array */

$this->title = Yii::t('orders', 'Orders');
?>
<div class="orders-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    // Сервисы
    $servicesItems = [
        [
            'label' => Yii::t('orders', 'All'),
            'url' => [
                'index',
                'status' => $searchModel->status,
                'search_type' => $searchModel->search_type,
                'search' => $searchModel->search,
            ],
        ],
    ];
    foreach ($services as $service) {
        $servicesItems[] = [
            'label' => $service['name'],
            'url' => [
                'index',
                'status' => $searchModel->status,
                'search_type' => $searchModel->search_type,
                'search' => $searchModel->search,
                'service_id' => $service['id'],
            ]
        ];
    }

    $servicesWidget = '';/*ButtonDropdown::widget([
            'label' => ' Service <span class="caret"></span>',
            'encodeLabel' => false,
            'buttonOptions' => ['class' => 'btn-th btn-default'],
            'dropdown' => [
                'items' => $servicesItems,
            ],
    ]);*/ ?>

    <?php
    // Режимы (Mode)
    $modesWidget = '';/*ButtonDropdown::widget([
        'label' => ' Mode <span class="caret"></span>',
        'encodeLabel' => false,
        'buttonOptions' => ['class' => 'btn-th btn-default'],
        'dropdown' => [
            'items' => [
                ['label'=>'All', 'url'=>''],
                ['label'=>'Manual', 'url'=>''],
                ['label'=>'Auto', 'url'=>''],
            ]
        ],
    ])*/ ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table order-table'
        ],
        'layout' => "{items}\n{pager} {summary}",
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->first_name . ' ' . $model->user->last_name;
                },
            ],
            'link',
            'quantity',
            [
                'attribute' => 'service_id',
                'header' => $servicesWidget,
                'headerOptions' => ['class' => 'dropdown-th'],
                'value' => function ($model) {
                    return '<span class="label-id">' . $model->service->ordersCount . '</span> ' . $model->service->name;
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusName;
                },
            ],
            [
                'attribute' => 'mode',
                'header' => $modesWidget,
                'headerOptions' => ['class' => 'dropdown-th'],
                'value' => function ($model) {
                    return $model->modeName;
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => function ($model) {
                    return '<span class="nowrap">' . Yii::$app->formatter->asDateTime($model->created_at, 'php:Y-m-d') . '</span>' .
                        '<span class="nowrap">' . Yii::$app->formatter->asDateTime($model->created_at, 'php:H:i:s') . '</span>';
                },
            ],
        ],
    ]); ?>


</div>
