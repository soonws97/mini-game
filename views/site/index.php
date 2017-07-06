<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">


    <div class="body-content">
    <h1>Welcome to Mini Game</h1>
        <div class="row">
           <?= Html::a('Login', ['/site/login'], ['class'=>'btn btn-primary']) ?>
        </div>

    </div>
</div>
