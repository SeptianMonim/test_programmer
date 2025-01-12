<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$this->title = 'Update Produk: ' . $model->nama_produk;
$this->params['breadcrumbs'][] = ['label' => 'Produk', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_produk, 'url' => ['view', 'id' => $model->id_produk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="produk-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>