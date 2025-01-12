<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $brand_id
 * @property string $brand_name
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['nama_kategori'], 'safe'],
        ];
    }
    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'kategori_id' => '#ID',
            'nama_kategori' => 'kategori*',
        ];
    }

    public static function listAll()
    {
        $list = array();
        foreach (self::find()->orderBy('nama_kategori')->all() as $m) {
            $list[$m->id_kategori] = $m->nama_kategori;
        }
        return $list;
    }
}
