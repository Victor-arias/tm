<?php 
$this->pageTitle = 'Modificar item de menú "' . $model->label. '"'; 
$bc = array();
$bc['Padre'] = bu('/administrador/'.lcfirst($model->menu->micrositios[0]->seccion->nombre).'/view/'.$model->menu->micrositios[0]->id);
$bc[] = 'Editar';
$this->breadcrumbs = $bc;
?>

<div class="col-sm-12">
<?php echo $this->renderPartial('_form_item', array('model'=>$model)); ?>
</div>