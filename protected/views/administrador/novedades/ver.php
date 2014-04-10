<div class="row">
	<div class="col-sm-2 panel panel-default">
		<ul class="nav nav-pills nav-stacked">
		  <li><?php echo l('<span class="glyphicon glyphicon-chevron-left"></span> Volver', bu('administrador/novedades'))?></li>
		  <li><?php echo l('<span class="glyphicon glyphicon-pencil"></span> Editar', bu('administrador/novedades/update/' . $model->id))?></li>
		  <li><?php echo l('<small><span class="glyphicon glyphicon-remove"></span> Eliminar</small>', bu('administrador/novedades/delete/' . $model->id), array('onclick' => 'if( !confirm(¿"Seguro que desea borrar la novedad "<?php echo $model->nombre; ?>") ) {return false;}'))?></li>
		</ul>
	</div>
	<div class="col-sm-10">
		<h1>Novedad "<?php echo $model->nombre; ?>"</h1>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data' => $model,
			'attributes'=>array(
				'id',
				'nombre',
				array(
					'name' => 'url.slug', 
					'type' => 'raw', 
					'value' => l($model->url->slug, bu($model->url->slug), array('target' => '_blank')),
				),
				'pgArticuloBlogs.entradilla:html',
				'pgArticuloBlogs.texto:html',
				array(
					'name' => 'pgArticuloBlogs.enlace', 
					'type' => 'raw', 
					'value' => l($model->pgArticuloBlogs->enlace, $model->pgArticuloBlogs->enlace, array('target' => '_blank')),
				),
				array(
					'name' => 'pgArticuloBlogs.imagen', 
					'type' => 'raw', 
					'value' => l($model->pgArticuloBlogs->imagen, bu('images/'.$model->pgArticuloBlogs->imagen), array('target' => '_blank', 'class' => 'fancybox')),
				),
				array(
					'name' => 'pgArticuloBlogs.imagen_mobile', 
					'type' => 'raw', 
					'value' => l($model->pgArticuloBlogs->imagen_mobile, bu('images/'.$model->pgArticuloBlogs->imagen_mobile), array('target' => '_blank', 'class' => 'fancybox')),
				),
				array(
					'name' => 'pgArticuloBlogs.miniatura', 
					'type' => 'raw', 
					'value' => l($model->pgArticuloBlogs->miniatura, bu('images/'.$model->pgArticuloBlogs->miniatura), array('target' => '_blank', 'class' => 'fancybox')),
				),
				'creado',
				'modificado',
				array(
					'name' => 'pgArticuloBlogs.posicion', 
					'type' => 'raw', 
					'value' => ($model->pgArticuloBlogs->posicion==1)?'Arriba':'Abajo',
				),
				array(
					'name' => 'estado',
					'label' => 'Estado', 
					'value' => ($model->estado==2)?'Publicado (en el home)':(($model->estado==1)?'Archivado':'Desactivado'),
				),
				'destacado:boolean'
			),
		)); ?>
	</div>
</div>
