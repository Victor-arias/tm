<?php $this->pageTitle = 'Crear especial' ?>
<?php 
$this->pageTitle = 'Crear especial'; 
$bc = array();
$bc['Especiales'] = $this->createUrl('index');
$bc[] = 'Crear';
$this->breadcrumbs = $bc;
?>

<div class="col-sm-12">
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
</div>