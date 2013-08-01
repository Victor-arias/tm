<?php
/* @var $this PaginaController */
/* @var $model Pagina */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pagina-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'usuario_id'); ?>
		<?php echo $form->textField($model,'usuario_id',array('size'=>10,'maxlength'=>10, 'value' => 1)); ?>
		<?php echo $form->error($model,'usuario_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'micrositio_id'); ?>
		<?php 
			$micrositios = Micrositio::model()->findAll();
			$datos = array();
			foreach($micrositios as $micrositio)
			{
				$datos[$micrositio->id] = $micrositio->nombre;
			}
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			    'name'=>'micrositio_id',
			    //'model'=> new Micrositio,
			    'source'=> CJSON::encode($datos),
			    // additional javascript options for the autocomplete plugin
			    'options'=>array(
			        'minLength'=>'3',
			    ),
			    'htmlOptions'=>array(
			        'style'=>'height:20px;',
			    ),
			));
		?>
		<?php //echo $form->dropDownList($model,'micrositio_id', CHtml::listData(Micrositio::model()->findAll(), 'id', 'nombre') ); ?>
		<?php //echo $form->textField($model,'micrositio_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'micrositio_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tipo_pagina_id'); ?>
		<?php echo $form->textField($model,'tipo_pagina_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'tipo_pagina_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url_id'); ?>
		<?php echo $form->textField($model,'url_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'url_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'creado'); ?>
		<?php echo $form->textField($model,'creado',array('size'=>19,'maxlength'=>19)); ?>
		<?php echo $form->error($model,'creado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modificado'); ?>
		<?php echo $form->textField($model,'modificado',array('size'=>19,'maxlength'=>19)); ?>
		<?php echo $form->error($model,'modificado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estado'); ?>
		<?php echo $form->textField($model,'estado'); ?>
		<?php echo $form->error($model,'estado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'destacado'); ?>
		<?php echo $form->textField($model,'destacado'); ?>
		<?php echo $form->error($model,'destacado'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->