<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\orders\models\OrderSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\orders\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<li class="pull-right custom-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index', 'status' => $model->status],
        'method' => 'get',
        'options' => ['class' => 'form-inline']
    ]); ?>

    <div class="input-group">

        <?= $form->field($model, 'search')
            ->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('orders', 'search.placeholder')
            ])
            ->label(false) ?>

        <span class="input-group-btn search-select-wrap">

                <?= Html::activeDropDownList(
                    $model,
                    'search_type',
                    [
                        OrderSearch::BY_ORDER_ID => Yii::t('orders', 'search.type.orderid'),
                        OrderSearch::BY_LINK => Yii::t('orders', 'search.type.link'),
                        OrderSearch::BY_USERNAME => Yii::t('orders', 'search.type.username'),
                    ],
                    ['class' => 'form-control search-select']
                ) ?>

                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>',
                    ['class' => 'btn btn-default']
                ) ?>

                </span>

    </div>

    <?php ActiveForm::end(); ?>
</li>
