<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Truckexpense */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Truckexpenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="truckexpense-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'truck_id' => $model->truck_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'truck_id' => $model->truck_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date_reported',
            'display_date',
            'spare_part_service:ntext',
            'cost',
            'series_no:ntext',
            'reason:ntext',
            'warranty:ntext',
            'remark:ntext',
            'truck_id',
        ],
    ]) ?>

</div>
