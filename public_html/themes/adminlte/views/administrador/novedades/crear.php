<?php $this->pageTitle = 'Crear novedad' ?>
<?php 
$this->pageTitle = 'Crear novedad'; 
$bc = array();
$bc['Novedades'] = $this->createUrl('index');
$bc[] = 'Crear';
$this->breadcrumbs = $bc;
?>
<div class="col-sm-12">
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
</div>