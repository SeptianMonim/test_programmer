<?php

use app\models\Category;
// use app\models\Status;
use app\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $form yii\widgets\ActiveForm */

$cs = '
$(document).ready(function() {
	var category = $("#category").val();
	if(category == 2){
		$("#gsm").hide();
		$("#dimension").hide();
	}
	else
	{
		$("#gsm").show();
		$("#dimension").show();
	}

	//menampilkan layout berbeda berdasarkan kategori barang
	$("#category").on("change", function(event, ui){
        var cat = $(this).val();
		if(cat == 2){
			$("#gsm").hide();
			$("#dimension").hide();
			$("#gram").val("");
			$("#length").val("");
			$("#width").val("");
		}
		else
		{
			$("#gsm").show();
			$("#dimension").show();
		}
	});
});
';
$this->registerJs($cs)
?>

<div class="customer-form">
	<?php $form = ActiveForm::begin(); ?>
	<div class="col-lg-12">
		<i>
			<p style="font-size: 12px;">Kolom dengan tanda * harus diisi</p>
		</i>
		<div class="row">
			<div class="form-group col-lg-6">
				<?= $form->field($model, 'nama_produk')->textInput(['class' => 'col-lg-7 form-control']) ?>
			</div>
			<div class="form-group col-lg-6">
				<?= $form->field($model, 'harga')->textInput(['class' => 'col-lg-7 form-control']) ?>
			</div>
			<div class="form-group col-lg-6">
				<?= $form->field($model, 'kategori_id')->dropDownList(Product::categories(), ['class' => 'col-lg-5 form-control', 'id' => 'kategori_id']) ?>
			</div>
			<div class="form-group col-lg-6">
				<?= $form->field($model, 'status_id')->dropDownList(Product::status(), ['class' => 'col-lg-5 form-control']) ?>
			</div>
		</div>
		<div class="form-group">
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>