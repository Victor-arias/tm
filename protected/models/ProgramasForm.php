<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ProgramasForm extends CFormModel
{
	public $id;
	public $nombre;
	public $resena;
	public $imagen;
	public $imagen_mobile;
	public $miniatura;
	public $formulario;
	public $destacado;
	public $estado;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('nombre, resena', 'required'),
			array('imagen, imagen_mobile, miniatura, formulario', 'length', 'max'=>255),
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
			'resena' => 'Reseña',
			'imagen' => 'Imagen',
			'imagen_mobile' => 'Imagen (Móvil)',
			'miniatura' => 'Imagen Miniatura',
			'formulario' => 'Formulario (ID de JotForm)',
			'estado' => 'Publicado',
		);
	}

}