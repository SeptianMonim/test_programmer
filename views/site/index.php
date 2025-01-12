<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\models\Product;
// use app\models\ProductType;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Barang';
$this->params['breadcrumbs'][] = $this->title;
$category = Category::listAll();
?>
<div class="col-lg-12">
    <div class="col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Daftar Produk</h3>
            </div>
            <div class="card-body">
                <div class="form-group col-lg-6" style="text-align: right; margin-left: 500px;">
                    <?= Html::a('<i class="fas fa-plus"></i> Tambah Produk', \yii\helpers\Url::toRoute('site/create'), ['class' => 'btn btn-primary']) ?>
                </div>
                <table class="table table-condensed table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>Kode produk</th>
                            <th>Nama produk</th>
                            <th>harga</th>
                            <th>Kategori</th>
                            <th style="width: 12%;">status</th>
                            <th style="width: 8%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $i => $r): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $r['id_produk'] ?></td>
                                <td><?= $r['nama_produk'] ?></td>
                                <td><?= $r['harga'] ?></td>
                                <td><?= $category[$r['kategori_id']] ?></td>
                                <td><?= $r['status_id'] == 1 ? "bisa dijual" : " - " ?></td>
                                <td style="text-align:center;">
                                    <?= Html::a('<i class="fas fa-pen"></i>', \yii\helpers\Url::toRoute('site/update/' . $r['id_produk']), ['class' => 'btn btn-xs btn-warning']) ?>
                                    <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $r['id_produk']], [
                                        'class' => 'btn btn-danger mt-1',
                                        'data' => [
                                            'confirm' => 'Apakah Anda yakin menghapus data ini?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>