<?php 
$this->pageTitle = 'Crear menú'; 
$bc = array();
$bc['Padre'] = Yii::app()->request->urlReferrer;
$bc[] = 'Crear';
$this->breadcrumbs = $bc;
?>
<div class="col-sm-12">
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
</div>