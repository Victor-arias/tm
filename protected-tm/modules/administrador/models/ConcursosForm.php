<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ConcursosForm extends CFormModel
{
	public $id;
	public $nombre;
	public $texto;
	public $meta_descripcion;
	public $imagen;
	public $imagen_mobile;
	public $miniatura;
	public $destacado;
	public $estado;
	private $transaccion;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('nombre, texto', 'required'),
			array('imagen, imagen_mobile, miniatura', 'length', 'max'=>255),
			array('meta_descripcion', 'length', 'max'=>200),
			array('estado, destacado', 'numerical', 'integerOnly'=>true)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nombre' => 'Título',
			'destacado' => 'Destacado',
			'texto' => 'Texto',
			'meta_descripcion' => 'Meta descripción',
			'imagen' => 'Imagen',
			'imagen_mobile' => 'Imagen (Móvil)',
			'miniatura' => 'Imagen Miniatura',
			'estado' => 'Estado',
		);
	}

	public function guardar()
	{
		$this->transaccion = $this->dbConnection->getCurrentTransaction();
		if($this->transaccion === null)
			$this->transaccion = $this->dbConnection->beginTransaction();

		$dir = $this->imageRoute;
		$image_base = Yii::getPathOfAlias('webroot').'/images/';
		if($this->validate()){
			if( isset($this->id) ) //Actualizando
				$micrositio 		= Micrositio::model()->findByPk($this->id);
			else //Nuevo registro
				$micrositio 		= new Micrositio;
			
			$this->transaccion 			= $micrositio->dbConnection->beginTransaction();
			$micrositio->seccion_id = 8; //Concursos
			$micrositio->nombre		= $this->nombre;

			if($this->imagen != $micrositio->background)
			{
				if(file_exists( $image_base . $micrositio->background))
					@unlink( $image_base . $micrositio->background);
				$micrositio->background = $dir . $this->imagen;
			}
			if($this->imagen_mobile != $micrositio->background_mobile)
			{
				if(file_exists( $image_base . $micrositio->background_mobile))
					@unlink( $image_base . $micrositio->background_mobile);
				$micrositio->background_mobile = $dir . $this->imagen_mobile;
			}
			if($this->miniatura != $micrositio->miniatura)
			{
				if(file_exists( $image_base . $micrositio->miniatura))
					@unlink( $image_base . $micrositio->miniatura);
				$micrositio->miniatura 	= $dir . $this->miniatura;
			}
			
			$micrositio->destacado	= $this->destacado;
			$micrositio->estado		= $this->estado;
			
			if( !$micrositio->save(false) ) $this->transaccion->rollback();
			$micrositio_id = $micrositio->getPrimaryKey();

			if( isset($this->id) ) //Actualizando
			{
				$pagina = Pagina::model()->findByAttributes(array('micrositio_id' => $micrositio_id));
			}
			else //Nuevo registro
			{
				$pagina = new Pagina;
				$pagina->micrositio_id 	  = $micrositio_id;
				$pagina->tipo_pagina_id   = 2; //Generica				
			}

			$pagina->nombre			  = $this->nombre;
			$pagina->meta_descripcion = $this->meta_descripcion;
			$pagina->clase 			  = NULL;
			$pagina->destacado		  = $this->destacado;
			$pagina->estado			  = ($this->estado == 2)?1:$this->estado;

			if( !$pagina->save(false) ) $this->transaccion->rollback();
			$pagina_id = $pagina->getPrimaryKey();

			if( isset($this->id) ) //Actualizando
				$pgGst = PgGenericaSt::model()->findByAttributes(array('pagina_id' => $pagina_id));
			else //Nuevo registro
			{
				if( !$micrositio->asignar_pagina($pagina) )
					$this->transaccion->rollback();

				$pgGst 				= new PgGenericaSt;
				$pgGst->pagina_id 	= $pagina_id;
			}

			$pgGst->texto 	= $this->texto;
			$pgGst->estado 	= 1;
			
			if( !$pgGst->save(false) )
			{
				$this->transaccion->rollback();
				return false;
			}
			else
			{
				$this->transaccion->commit();
				$this->id = $micrositio_id;
				return true;
			}
		}//if($this->validate())
		else
		{
			return false;
		}
	}

	public function set_fields($micrositio, $pagina)
	{
		if(!$micrositio || !$pagina) return false;

		$this->nombre 			= $micrositio->nombre;
		$this->texto 			= $pagina->pgGenericaSts->texto;
		$this->meta_descripcion = $pagina->meta_descripcion;
		$this->imagen 			= $micrositio->background;
		$this->imagen_mobile 	= $micrositio->background_mobile;
		$this->miniatura 		= $micrositio->miniatura;
		$this->estado 			= $micrositio->estado;
		$this->destacado 		= $micrositio->destacado;

		return true;
	}

	public static function getImageRoute()
	{
		return 'backgrounds/concursos/' . date('Y') . '/' . date('m') . '/';
	}

}