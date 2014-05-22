<?php

/**
 * This is the model class for table "pg_bloques".
 *
 * The followings are the available columns in table 'pg_bloques':
 * @property string $id
 * @property string $pagina_id
 * @property string $imagen
 * @property string $imagen_mobile
 * @property string $miniatura
 * @property integer $estado
 *
 * The followings are the available model relations:
 * @property Pagina $pagina
 */
class PgBloques extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PgArticuloBlog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pg_bloques';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pagina_id, estado', 'required'),
			array('estado', 'numerical', 'integerOnly'=>true),
			array('pagina_id', 'length', 'max'=>10),
			array('imagen, imagen_mobile, miniatura', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pagina_id, imagen, imagen_mobile, miniatura, estado', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bloques' => array(self::HAS_MANY, 'Bloque', 'pg_bloques_id', 'order' => 'bloques.orden ASC'),
			'pagina' => array(self::BELONGS_TO, 'Pagina', 'pagina_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pagina_id' => 'Pagina',
			'imagen' => 'Imagen',
			'imagen_mobile' => 'Imagen (Móvil)',
			'miniatura' => 'Miniatura',
			'estado' => 'Estado',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pagina_id',$this->pagina_id,true);
		$criteria->compare('imagen',$this->imagen,true);
		$criteria->compare('imagen_mobile',$this->imagen_mobile,true);
		$criteria->compare('miniatura',$this->miniatura,true);
		$criteria->compare('estado',$this->estado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}