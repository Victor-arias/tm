<?php

class TelemedellinController extends Controller
{
	public function actionError()
	{
		$this->render('error');
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCargar()
	{
		if( isset($_GET['tm']) )
		{
			switch ( $_GET['tm']->tipo ) {
				case 1:
					$this->cargar_seccion();
					break;
				case 2:
					if( $_GET['tm']->slug == 'novedades' )
						$this->cargar_novedades();
					else if( $_GET['tm']->slug == 'programacion' )
						$this->cargar_programacion();
					else
						$this->cargar_micrositio();
					break;
				case 3:
						$this->cargar_micrositio( $_GET['tm']->id );
					break;
				default:
					# code...
					break;
			}
		}
		//print_r($_GET);
	}

	public function actionCargarSeccion()
	{
		$url_id = $_GET['tm']->id;
		$seccion = Seccion::model()->cargarPorUrl( $url_id );
		if( !$seccion ) throw new CHttpException(404, 'Invalid request');
		$micrositios= Micrositio::model()->listarPorSeccion( $seccion->id );
		if( !$micrositios ) throw new CHttpException(404, 'Invalid request');
		$this->render( 'seccion', array('seccion' => $seccion, 'micrositios' => $micrositios) );
	}

	public function actionCargarMicrositio( )
	{
		$url_id = $_GET['tm']->id;

		if( isset($_GET['slug_id']) )
		{
			$pagina  = Pagina::model()->cargarPorUrl( $_GET['slug_id'] );
			$micrositio = Micrositio::model()->cargarMicrositio( $pagina['pagina']->micrositio_id );
		}
		else
		{
			$micrositio = Micrositio::model()->cargarPorUrl( $url_id );
			$pagina  = Pagina::model()->cargarPagina( $micrositio->pagina_id );
		}

		if( !is_null($micrositio->menu_id) )
		{
			$menu = $micrositio->menu_id;
		}
		else
		{
			$menu = false;
		}

		if( !$pagina ) throw new CHttpException(404, 'No se encontró la página solicitada');

		$contenido = $this->renderPartial('_'.$pagina['partial'], array('contenido' => $pagina), true);

		$this->render( 
			'micrositio', 
			array(	'seccion' 	=> $micrositio->seccion, 
					'micrositio'=> $micrositio, 
					'menu'		=> $menu,
					'pagina' 	=> $pagina['pagina'], 
					'contenido' => $contenido, 
				) 
		);
	}

	public function actionCargarNovedades()
	{
		$url_id = $_GET['tm']->id;

		$micrositio = Micrositio::model()->cargarPorUrl( $url_id );

		$novedades = Pagina::model()->listarPaginas( $micrositio->id );

		$pagina = new stdClass();
		$pagina->id   = NULL;
		$pagina->tipoPagina = new stdClass();
		$pagina->tipoPagina->tabla = 'novedades';

		$contenido = $this->renderPartial('_novedades', array('novedades' => $novedades), true);

		$this->render( 
			'micrositio', 
			array(	'seccion' 	=> $micrositio->seccion, 
					'micrositio'=> $micrositio, 
					'pagina' 	=> $pagina, 
					'contenido' => $contenido, 
				) 
		);
	}

	public function actionCargarProgramacion()
	{
		$url_id = $_GET['tm']->id;

		$micrositio = Micrositio::model()->cargarPorUrl( $url_id );

		$pagina = new stdClass();
		$pagina->id   = NULL;
		$pagina->tipoPagina = new stdClass();
		$pagina->tipoPagina->tabla = 'programacion';

		date_default_timezone_set('America/Bogota');
		setlocale(LC_ALL, 'es-ES');
		
		$sts = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$tts = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

		if( isset($_GET['dia']) &&  isset($_GET['mes']) )
		{
			$dia = $_GET['dia'];
			$mes = $_GET['mes'];
			$anio = ( isset( $_GET['anio'] ) ) ? $_GET['anio'] : date('Y');
			if( checkdate($mes, $dia, $anio) )
			{
				$sts = mktime(0, 0, 0, $mes, $dia, $anio);
			}
		}

		// set current date
		// parse about any English textual datetime description into a Unix timestamp
		$ts 		= $sts;
		// calculate the number of days since Monday
		$dow 		= date('w', $ts);
		$offset 	= $dow - 1;
		if ($offset < 0) $offset = 6;
		// calculate timestamp for the Monday
		$ts 		= $ts - $offset * 86400;
		$semana 	= array();

		// loop from Monday till Sunday
		for ($i = 0; $i < 7; $i++, $ts += 86400){
		    $semana[] = $ts;
		}
		$p = new Programacion;
		$programas = $p->getDay( $sts );

		$contenido = $this->renderPartial(
				'_programacion', 
				array(
					'programas' => $programas,
					'menu'		=> $semana,
				), 
				true
			);

		$this->render( 
			'micrositio', 
			array(	'seccion' 	=> $micrositio->seccion, 
					'micrositio'=> $micrositio, 
					'pagina' 	=> $pagina, 
					'hoy' 		=> $tts, 
					'contenido' => $contenido, 
				) 
		);
	}

	public function actionProgramar()
	{
		//Horario::model()->
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}