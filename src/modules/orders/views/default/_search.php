<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

            <?= $form->field($model, 'search')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('orders', 'Search orders')])->label(false) ?>

            <span class="input-group-btn search-select-wrap">

                <?= Html::activeDropDownList($model, 'search_type', [
                    0 => Yii::t('orders', 'Order ID'),
                    1 => Yii::t('orders', 'Link'),
                    2 => Yii::t('orders', 'Username'),
                ],
                    ['class' => 'form-control search-select']) ?>

                <?= Html::submitButton('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['class' => 'btn btn-default']) ?>

                </span>

        </div>

        <?php ActiveForm::end(); ?>
    </li>
