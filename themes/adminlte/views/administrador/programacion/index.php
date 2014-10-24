<?php
$this->pageTitle = 'Programación ' . Yii::app()->name; 
$bc = array();
$bc[] = 'Programación';
$this->breadcrumbs = $bc;
$this->renderPartial('//layouts/commons/_flashes');
echo $menu;
?>
<div class="col-sm-12">
    <div class="nav navbar-right">
      <?php if(Yii::app()->user->checkAccess('crear_programacion')): ?>
            <?php echo l('<i class="fa fa-plus"></i> Nueva', $this->createUrl('crear'), array('class' => 'btn btn-primary')) ?>
      <?php endif ?>
    </div>
</div>
<div class="col-sm-12">
    <?php 
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$model->search(),
        'filter' => $model, 
        'enableSorting' => true,
        'columns'=>array(
            array(
                'name'=>'nombre_micrositio',
                'header'=>'Nombre',
                'type' => 'raw', 
                'value'=>'$data->micrositio->nombre'
                ),
            array(
                'name'=>'hora_fin',
                'value'=>'date("H:i", $data->hora_fin)',
                'filter'=> false,
                ),
            array(
                'name'=>'estado',
                'header'=>'Publicado',
                'filter'=>array('1'=>'Si','0'=>'No'),
                'value'=>'($data->estado=="1")?("Si"):("No")'
                ),           
            array(
                'class'=>'CButtonColumn',
                'template' => '{view} | {update} | {delete}',
                'buttons' => array(
                    'view' => array(
                        'imageUrl' => false,
                        'label'    => '<i class="fa fa-search"></i>', 
                        'options'  => array('title' => 'Ver detalles'),
                    ),
                    'update' => array(
                        'visible' => '(Yii::app()->user->checkAccess("editar_programacion"))?true:false', 
                        'imageUrl' => false,
                        'label'    => '<i class="fa fa-pencil"></i>', 
                        'options'  => array('title' => 'Editar'),
                    ),
                    'delete' => array(
                        'visible' => '(Yii::app()->user->checkAccess("eliminar_programacion"))?true:false', 
                        'imageUrl' => false,
                        'label' => '<i class="fa fa-trash-o"></i>', 
                        'options'  => array('title' => 'Eliminar'),
                    ),
                ),
            ),
        )
    ));
    ?>
</div>