<?php
cs()->coreScriptPosition = CClientScript::POS_END;
cs()->registerCoreScript( 'jquery.ui' );
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
cs()->registerScriptFile(bu('js/libs/admin/i18n/jquery.ui.datepicker-es.js'), CClientScript::POS_END);
cs()->registerScriptFile(bu('js/libs/admin/jquery-ui-timepicker-addon.js'), CClientScript::POS_END);
Yii::app()->clientScript->registerScript('datepicker', 
    'var startDateTextBox = $(".inicio_publicacion"),
         endDateTextBox = $(".fin_publicacion");
    startDateTextBox.datetimepicker(
        {
            dateFormat: "yy-mm-dd",
            timeFormat: "H:mm:ss",
            minuteGrid: 10,
            onClose: function(dateText, inst) {
                if (endDateTextBox.val() != "") {
                    var testStartDate = startDateTextBox.datetimepicker("getDate");
                    var testEndDate = endDateTextBox.datetimepicker("getDate");
                    if (testStartDate > testEndDate)
                        endDateTextBox.datetimepicker("setDate", testStartDate);
                }
                else {
                    endDateTextBox.val(dateText);
                }
            },
            onSelect: function (selectedDateTime){
                endDateTextBox.datetimepicker("option", "minDate", startDateTextBox.datetimepicker("getDate") );
            }
        }, 
        $.datepicker.regional[ "es" ]
    );
    endDateTextBox.datetimepicker(
        { 
           dateFormat: "yy-mm-dd",
           timeFormat: "H:mm:ss",
            minuteGrid: 10,
            onClose: function(dateText, inst) {
                if (startDateTextBox.val() != "") {
                    var testStartDate = startDateTextBox.datetimepicker("getDate");
                    var testEndDate = endDateTextBox.datetimepicker("getDate");
                    if (testStartDate > testEndDate)
                        startDateTextBox.datetimepicker("setDate", testEndDate);
                }
                else {
                    startDateTextBox.val(dateText);
                }
            }
        },
        $.datepicker.regional[ "es" ]
    );
    ', 
    CClientScript::POS_READY);
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'guino-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
        'role' => 'form',
        'class' => 'form-horizontal' 
    )
)); ?>
	<?php echo $form->errorSummary( $model ); ?>
	<div class="form-group">
		<?php echo $form->label($model,'nombre', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-6">
			<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		</div>
		<?php echo $form->error($model,'nombre'); ?>
	</div>
	<div class="form-group">
        <?php echo $this->imageField($form, $model, 'guino', 'archivoImagen', '_guino'); ?>
        <span class="help-block">Alto máximo: 100 px, Ancho máximo: 250px</span>
	</div>
    <div class="form-group">
        <?php echo $this->imageField($form, $model, 'guino_mobile', 'archivoImagenMobile', '_guino'); ?>
        <span class="help-block"> Alto máximo: 74 px, Ancho máximo: 186px</span>
    </div>
    <div class="form-group">
		<?php echo $form->label($model,'inicio_publicacion', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-2">
            <input name="Guino[inicio_publicacion]" type="text" value="<?php echo $model->inicio_publicacion ?>" class="inicio_publicacion form-control" />
        </div>
		<?php echo $form->error($model,'inicio_publicacion'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'fin_publicacion', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-2">
            <input name="Guino[fin_publicacion]" type="text" value="<?php echo $model->fin_publicacion ?>" class="fin_publicacion form-control" />
        </div>
		<?php echo $form->error($model,'fin_publicacion'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'estado', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-2">
			<?php echo $form->dropDownList($model,'estado', array('1' => 'Si', '0' => 'No' ), array('class' => 'form-control') ); ?>
		</div>
		<?php echo $form->error($model,'estado'); ?>
	</div>
	<div class="form-group buttons">
		<?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-primary')); ?>
	</div>
	<input type="hidden" value="<?php echo Yii::app()->request->baseUrl ?>" id="PUBLIC_PATH"/>
<?php $this->endWidget(); ?>
</div><!-- form -->
<?php echo $this->renderPartial('../_file_upload_tmpl') ?>