<?php 
$this->pageTitle = 'Modificar elemento "' . $model->campo. '"'; 
$bc = array();
$bc['Padre'] = $this->createUrl('documentales/view', array('id' => $model->pgDocumental->pagina->micrositio->id));
$bc[] = 'Editar';
$this->breadcrumbs = $bc;
?>
<div class="col-sm-12">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>