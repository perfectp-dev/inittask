<?php

namespace app\modules\orders;

/**
 * orders module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\orders\controllers';
    public $defaultRoute = 'orders/index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
