<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Truckexpense */

$this->title = 'Update Truckexpense: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Truckexpenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'truck_id' => $model->truck_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="truckexpense-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
