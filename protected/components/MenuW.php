<?php
Yii::import('system.web.widgets.CWidget');

class MenuW extends CWidget
{
    public $id;

    public function run()
    {
        $items = $this->getItems();
        $this->widget('zii.widgets.CMenu', array('items' => $items) );
    }

    protected function getItems()
    {
    	$ru = Yii::app()->request->requestUri;

        $c = new CDbCriteria;
        $c->addCondition('t.estado <> 0');
        $c->addCondition('menuItems.estado = 1');
        $c->order  = 'menuItems.orden ASC';

    	$menu = Menu::model()->with('menuItems')->findByPk($this->id, $c);
    	$items = $menu->menuItems;
    	$items_menu = array();
    	foreach($items as $item)
    	{
    		if($item->item_id != 0) continue;
            $url = $this->getUrl($item);
            
    		$item_actual = array(
    				'label' => $item->label,
    				'url'	=> $url,
    				'active' => strpos($ru, $url)
    			);
    		if($item->hijos == 1)
    		{
    			$hijos = $this->getSubItems($item->item_id);
    			$subitems = array();
    			foreach($hijos as $hijo)
    			{
    				$hurl = $this->getUrl($hijo);
                    $subitems[] = array(
    						'label' => $hijo->label,
    						'url'	=> $hurl,
    						'active' => strpos($ru, $hurl)
    					);
    			}
    			$item_actual['items'] = $subitems;
    		}
    		$items_menu[] = $item_actual;
    	}
    	return $items_menu;
    }

    protected function getSubItems($item_id)
    {
    	$c = new CDbCriteria;
        $c->addCondition('t.estado <> 0');
        $c->order  = 't.orden ASC';
        return MenuItem::model()->findAllByAttributes( array('item_id' => $this->id), $c );
    }

    protected function getUrl($item)
    {
        if(!$item) return false;

        switch($item->tipo_link_id)
        {
            case 1:
                $u = Url::model()->findByPk( $item->url_id );
                $url = bu($u->slug);
                break;
            case 2:
                $url = $this->parseExtUrl( $item->url );
                break;
        }
        return $url;
    }

    protected function parseUrl($url)
    {
        if( strpos($url, '/') !== 0 ) $url = '/' . $url;
        return $url;
    }

    protected function parseExtUrl($url)
    {
       return $url; 
    }
}