<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\orders\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $statuses array */
/* @var $allOrdersCount int */
/* @var $services array */
/* @var $modes array */
/* @var $pageSize int */


$this->title = Yii::t('orders', 'Orders');

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

        'summary' => $dataProvider->getTotalCount() > $pageSize ?
            (
                "{begin} " . Yii::t('orders', 'to') . " {end} " .
                Yii::t('orders', 'of') . " {totalCount}"
            ) : "{totalCount}"

        ,

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

<div class="row">
    <div class="col-sm-4 pull-right">
        <?= Html::a(Yii::t('orders', 'Save result'), ['save']) ?>
    </div>
</div>
</div>
