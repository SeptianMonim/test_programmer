<?php

namespace app\models;

use Yii;
use yii\web\HttpException;

/**
 * This is the model class for table "supplier".
 *
 * @property int $supplier_id
 * @property string $fullname
 */
class Product extends \yii\db\ActiveRecord
{
	const CAT_1 = 1;
	const CAT_2 = 2;
	const CAT_3 = 3;
	const CAT_4 = 4;
	const CAT_5 = 5;
	const CAT_6 = 6;
	const STAT_1 = 1;
	const STAT_2 = 2;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'produk';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['kategori_id'], 'required'],
			[['nama_produk', 'kategori_id', 'status_id'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id_produk' => '#ID',
			'id_produk' => 'Kode Produk',
			'nama_produk' => 'Nama Produk*',
			'kategori_id' => 'Kategori',
			'status_id' => 'Status',
		];
	}


	public function getCategory()
	{
		return $this->hasOne(Category::class, ['kategori_id' => 'id_kategori']);
	}

	public static function categories()
	{
		return array(
			self::CAT_1 => 'L QUEENLY',
			self::CAT_2 => 'L MTH AKSESORIS (IM)',
			self::CAT_3 => 'L MTH TABUNG (LK)',
			self::CAT_4 => 'SP MTH SPAREPART (LK)',
			self::CAT_5 => 'CI MTH TINTA LAIN (IM)',
			self::CAT_6 => 'S MTH STEMPEL (IM)',
		);
	}
	public function listCategories()
	{
		$arr = self::categories();
		return $arr[$this->kategori_id];
	}

	public static function status()
	{
		return array(
			self::STAT_1 => 'bisa dijual',
			self::STAT_2 => 'tidak bisa dijual',

		);
	}
	public function listStatus()
	{
		$arr = self::status();
		return $arr[$this->status_id];
	}


	public static function listAll()
	{
		$list = array();
		foreach (self::find()->orderBy('nama_produk')->all() as $m) {
			$list[$m->id_produk] = $m->nama_produk;
		}
		return $list;
	}
}
