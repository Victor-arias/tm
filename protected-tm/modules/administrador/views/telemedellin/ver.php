<?php $this->pageTitle = 'Institucional ' . $model->nombre; ?>
<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
		  <li><?php echo l('<span class="glyphicon glyphicon-chevron-left"></span> Volver', bu('administrador/telemedellin'))?></li>
		  <?php if(Yii::app()->user->checkAccess('editar_telemedellin')): ?>
		  <li><?php echo l('<span class="glyphicon glyphicon-pencil"></span> Editar', bu('administrador/telemedellin/update/' . $model->id))?></li>
		  <?php endif ?>
		  <?php if(Yii::app()->user->checkAccess('eliminar_telemedellin')): ?>
		  <li><?php echo l('<small><span class="glyphicon glyphicon-remove"></span> Eliminar</small>', bu('administrador/telemedellin/delete/' . $model->id), array('onclick' => 'if( !confirm(¿"Seguro que desea borrar la novedad "<?php echo $model->nombre; ?>") ) {return false;}'))?></li>
		  <?php endif ?>
		</ul>
	</div>
	<div class="col-sm-10">
		<h1>Telemedellín <?php echo $model->nombre; ?></h1>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data' => $model,
			'attributes'=>array(
				'nombre',
				array(
					'name' => 'url.slug',
					'type' => 'raw', 
					'value' => l($model->url->slug, bu($model->url->slug), array('target' => '_blank')),
				),
				array(
					'name' => 'background', 
					'type' => 'raw', 
					'value' => l($model->background, bu('images/'.$model->background), array('target' => '_blank', 'class' => 'fancybox')),
				),
				array(
					'name' => 'background_mobile', 
					'type' => 'raw', 
					'value' => l($model->background_mobile, bu('images/'.$model->background_mobile), array('target' => '_blank', 'class' => 'fancybox')),
				),
				array(
					'name' => 'miniatura', 
					'type' => 'raw', 
					'value' => l($model->miniatura, bu('images/'.$model->miniatura), array('target' => '_blank', 'class' => 'fancybox')),
				),
				'creado',
				'modificado',
				array(
					'name' => 'estado',
					'label' => 'Estado', 
					'value' => ($model->estado==2)?'Publicado (Se ve en listados)':(($model->estado==1)?'Archivado':'Desactivado'),
				),
				'destacado:boolean',
			),
		)); ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?php 
		if( Yii::app()->user->checkAccess('ver_paginas') )
		{
			$tabs_content['paginas'] = 
				array(
		            'title'=>'Páginas',
		            'view'=>'/pagina/_paginas', 
		            'data'=> array('paginas' => $contenido, 'model' => $model)
		        );
		}
		if( Yii::app()->user->checkAccess('ver_menus') || Yii::app()->user->checkAccess('ver_menu_item') )
		{
		    $tabs_content['menu'] =    
		        array(
		            'title'=>'Menú',
		            'view'=>'/menu/_menu', 
		            'data'=> array('menu' => $menu, 'model' => $model)
		        );
		}
		if( Yii::app()->user->checkAccess('ver_album_fotos') )
		{
		    $tabs_content['fotos'] =    
		        array(
		            'title'=>'Álbumes de fotos',
		            'view'=>'/albumfoto/_foto', 
		            'data'=> array('fotos' => $fotos, 'model' => $model)
		        );
		}
		if( Yii::app()->user->checkAccess('ver_album_videos') )
		{
			$tabs_content['videos'] = 
				array(
		            'title'=>'Álbumes de videos',
		            'view'=>'/albumvideo/_video', 
		            'data'=> array('videos' => $videos, 'model' => $model)
		        );
		}
	    if( isset($tabs_content) ) $this->widget('CTabView', array('tabs' => $tabs_content));
		?>
	</div>
</div>