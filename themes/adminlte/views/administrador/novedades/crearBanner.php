<?php 
$this->pageTitle = 'Crear banner'; 
$bc = array();
$bc['Banners'] = bu('/administrador/novedades/banners');
$bc[] = 'Crear';
$this->breadcrumbs = $bc;
?>
<div class="col-sm-12">
<?php echo $this->renderPartial('_form_banner', array('model' => $model)); ?>
</div>