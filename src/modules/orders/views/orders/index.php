<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $searchModel app\modules\orders\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $statuses array */
/* @var $allOrdersCount int */
/* @var $services array */
/* @var $modes array */
/* @var $pageSize int */
/* @var $saveURL array*/


$this->title = $title;

?>
<div class="orders-index">
    <ul class="nav nav-tabs p-b">
        <?= $this->render('_status', [
            'model' => $searchModel,
            'statuses' => $statuses,
        ]) ?>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </ul>

    <?php $servicesWidget = $this->render('_services', [
        'model' => $searchModel,
        'services' => $services,
        'allOrdersCount' => $allOrdersCount,
    ]); ?>

    <?php $modesWidget = $this->render('_modes', [
        'model' => $searchModel,
        'modes' => $modes,
    ]); ?>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,

        'tableOptions' => [
            'class' => 'table order-table'
        ],

        'layout' => "{items}
            <div class='row'>
                <div class='col-sm-8'>{pager}</div>
                <div class='col-sm-4 pagination-counters'>{summary}</div>
            </div>",

        'summary' => $dataProvider->getTotalCount() > $dataProvider->pagination->pageSize ?
            (
                "{begin} " . Yii::t('orders', 'search.summary.to') . " {end} " .
                Yii::t('orders', 'search.summary.of') . " {totalCount}"
            ) : "{totalCount}"

        ,

        'columns' => [

            'id',

            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user_full_name;
                },
            ],

            'link',
            'quantity',

            [
                'attribute' => 'service_id',
                'header' => $servicesWidget,
                'headerOptions' => ['class' => 'dropdown-th'],
                'value' => function ($model) {
                    return '<span class="label-id">' . $model->service_orders_cnt . '</span> ' . $model->service_name;
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
                    return '<span class="nowrap">' . $model->createdDateOnly . '</span>' .
                        '<span class="nowrap">' . $model->createdTimeOnly . '</span>';
                },
            ],
        ],
    ]); ?>

<div class="row">
    <div class="col text-right">
        <?= Html::a(
            '<i class="glyphicon glyphicon-download"></i> ' . Yii::t('orders', 'search.savebtn'),
            $saveURL,
            ['class' => 'btn btn-default']
        )?>
    </div>
</div>
</div>
