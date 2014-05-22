<?php

/**
 * This is the model class for table "carpeta".
 *
 * The followings are the available columns in table 'carpeta':
 * @property string $id
 * @property string $url_id
 * @property string $micrositio_id
 * @property string $item_id
 * @property string $carpeta
 * @property string $ruta
 * @property integer $hijos
 * @property integer $estado
 *
 * The followings are the available model relations:
 * @property Archivo[] $archivos
 * @property Pagina $pagina
 * @property Url $url
 */
class Carpeta extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Carpeta the static model class
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
		return 'carpeta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url_id, pagina_id, carpeta, ruta, estado', 'required'),
			array('hijos, estado', 'numerical', 'integerOnly'=>true),
			array('url_id, pagina_id, item_id', 'length', 'max'=>10),
			array('carpeta', 'length', 'max'=>100),
			array('ruta', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url_id, pagina_id, item_id, carpeta, ruta, hijos, creado, modificado, estado', 'safe', 'on'=>'search'),
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
			'carpetas' => array(self::HAS_MANY, 'Carpeta', 'item_id'),
			'archivos' => array(self::HAS_MANY, 'Archivo', 'carpeta_id'),
			'pagina' => array(self::BELONGS_TO, 'Pagina', 'pagina_id'),
			'url' => array(self::BELONGS_TO, 'Url', 'url_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'url_id' => 'Url',
			'pagina_id' => 'Página',
			'item_id' => 'Crear dentro de',
			'carpeta' => 'Carpeta',
			'ruta' => 'Ruta',
			'hijos' => 'Hijos',
			'creado' => 'Creado',
			'modificado' => 'Modificado',
			'estado' => 'Publicado',
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
		$criteria->compare('url_id',$this->url_id,true);
		$criteria->compare('pagina_id',$this->micrositio_id,true);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('carpeta',$this->carpeta,true);
		$criteria->compare('ruta',$this->ruta,true);
		$criteria->compare('hijos',$this->hijos);
		$criteria->compare('creado',$this->creado,true);
		$criteria->compare('modificado',$this->modificado,true);
		$criteria->compare('estado',$this->estado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getList($pagina_id = 0, $id = 0, $espacio = '--') {
	    $list = array();
	    
	    $atributos = array('item_id' => $id);
	    if($pagina_id != 0) $atributos = array_merge($atributos, array('pagina_id' => $pagina_id));
	    
	    $models = Carpeta::model()->findAllByAttributes($atributos);
	    foreach ($models as $model) {
	    	$childList = Carpeta::getList($pagina_id, $model->id, $espacio.'--');
	    	array_push($list, array('id' => $model->id, 'carpeta' => $espacio.' '.$model->carpeta));
	    	$list = array_merge($list, $childList);
	    }
	    return $list;
	}

	/*public function vaciar_carpeta( $id )
	{
		if( !$id ) return false;
		$c = $this->with('archivos', 'carpetas', 'url')->findByPk( $id );

	}*/

	protected function beforeSave()
	{
	    if(parent::beforeSave())
	    {
	        
	        if($this->isNewRecord)
	        {
	        	$this->creado 		= date('Y-m-d H:i:s');
	        }
	        else
	        {
	            $this->modificado	= date('Y-m-d H:i:s');
	        }
	        return true;
	    }
	    else
	        return false;
	}
}