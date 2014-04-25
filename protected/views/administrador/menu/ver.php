<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
		  <li><?php echo l('<span class="glyphicon glyphicon-chevron-left"></span> Volver', bu('administrador/menu'))?></li>
		  <li><?php echo l('<span class="glyphicon glyphicon-pencil"></span> Editar', bu('administrador/menu/update/' . $model->id))?></li>
		  <li><?php echo l('<small><span class="glyphicon glyphicon-remove"></span> Eliminar</small>', bu('administrador/menu/delete/' . $model->id), array('onclick' => 'if( !confirm(¿"Seguro que desea borrar el menú "<?php echo $model->nombre; ?>") ) {return false;}'))?></li>
		</ul>
	</div>
	<div class="col-sm-10">
		<h1>Menú <?php echo $model->nombre; ?></h1>
		<?php
		    foreach(Yii::app()->user->getFlashes() as $key => $message) {
		        echo '<div class="flash-' . $key . ' alert alert-info">' . $message . "</div>\n";
		    }
		?>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data' => $model,
			'attributes'=>array(
				'nombre', 
				array(
		            'label' => 'Micrositios asignados', 
		            'value' => 
		            function($model){
		                $r=''; 
		                foreach($model->micrositios as $m) 
		                    $r .= $m->nombre.', '; 
		                return substr($r, 0, -2);
		            }
		        ),
				'creado',
				'modificado',
				array(
					'name' => 'estado',
					'value' => ($model->estado==1)?'Sí':'No',
				),
			),
		)); ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
	<?php 
	$tabs_content = array(
        'menuItems'=>array(
            'title'=>'Items',
            'view'=>'_menuItems', 
            'data'=> array('menuItems' => $menuItems, 'model' => $model)
        )
    );
    $this->widget('CTabView', array('tabs' => $tabs_content));
	?>
	</div>
</div>