<?php

App::uses('DataobjectsController', 'Dane.Controller');

class PrawoController extends DataobjectsController
{

    public $initLayers = array('docs', 'counters', 'files');
    public $helpers = array('Document');

    // public $uses = array('Dane.Dataliner');

    public $observeOptions = true;

    public $headerObject = array('url' => '/dane/img/headers/ustawa.jpg', 'height' => '250px');

    public $objectOptions = array(
        'hlFields' => array(),
        'routes' => array(
            'description' => false,
        ),
    );
    
    public function _prepareView()
    {
	            
        $aggs_fields = array();
        foreach(array('akty_uchylajace', 'akty_uchylone', 'akty_wykonawcze', 'akty_uznane_za_uchylone', 'akty_zmieniajace', 'akty_zmienione', 'informacja_o_tekscie_jednolitym', 'odeslania', 'orzeczenie_do_aktu', 'orzeczenie_tk', 'podstawa_prawna', 'tekst_jednolity_do_aktu', 'uchylenia_wynikajace_z') as $field) {
	        $aggs_fields[$field] = array(
                'filter' => array(
                    'term' => array(
                    	'data.prawo.' . $field => $this->request->params['id'],
                    ),
                ),
	        );
        }
                
        $this->addInitAggs(array(
            'all' => array(
                'global' => '_empty',
                'aggs' => array(
                    'prawo' => array(
                        'filter' => array(
                            'bool' => array(
                                'must' => array(
                                    array(
                                        'term' => array(
                                            'dataset' => 'prawo',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                        'aggs' => $aggs_fields,
                    ),
                ),
            ),
        ));
        
	    return parent::_prepareView();
	    
    }
    
    public function view()
    {
        $this->_prepareView();
    }
    
    public function hasla()
    {
        $this->_prepareView();
    }

    private function connections_view($id, $title)
    {
		
		$this->_prepareView();
        $this->Components->load('Dane.DataBrowser', array(
            'conditions' => array(
                'dataset' => 'prawo',
                'prawo.' . $id => $this->object->getId(),
            ),
        ));
		
        $this->set('title_for_layout', $title . ': ' . $this->object->getTitle());

    }
	
	public function podstawa_prawna()
    {
        return $this->connections_view('podstawa_prawna', 'Podstawa prawna');
    }
	
    public function podstawa_prawna_z_artykulem()
    {
        return $this->connections_view('podstawa_prawna_z_artykulem', 'Podstawa prawna z artykułem');
    }

    public function akty_zmienione()
    {
        return $this->connections_view('akty_zmienione', 'Akty zmienione');
    }

    public function akty_wykonawcze()
    {
        return $this->connections_view('akty_wykonawcze', 'Akty wykonawcze');
    }

    public function akty_uchylone()
    {
        return $this->connections_view('akty_uchylone', 'Akty uchylone');
    }

    public function akty_uznane_za_uchylone()
    {
        return $this->connections_view('akty_uznane_za_uchylone', 'Akty uznane za uchylone');
    }

    public function orzeczenie_do_aktu()
    {
        return $this->connections_view('orzeczenie_do_aktu', 'Orzeczenia do aktu');
    }

    public function tekst_jednolity_do_aktu()
    {
        return $this->connections_view('tekst_jednolity_do_aktu', 'Tekst jednolity do aktu');
    }

    public function orzeczenia_tk()
    {
        return $this->connections_view('orzeczenie_tk', 'Orzeczenia TK');
    }

    public function informacja_o_tekscie_jednolitym()
    {
        return $this->connections_view('informacja_o_tekscie_jednolitym', 'Informacje o tekście jednolitym');
    }

    public function akty_zmieniajace()
    {
        return $this->connections_view('akty_zmieniajace', 'Nowelizacje');
    }

    public function akty_uchylajace()
    {
        return $this->connections_view('akty_uchylajace', 'Akty uchylające');
    }

    public function uchylenia_wynikajace_z()
    {
        return $this->connections_view('uchylenia_wynikajace_z', 'Uchylenia wynikające z');
    }

    public function dyrektywy_europejskie()
    {
        return $this->connections_view('dyrektywy_europejskie', 'Dyrektywy europejskie');
    }

    public function odeslania()
    {
        return $this->connections_view('odeslania', 'Odesłania');
    }

	public function getMenu()
	{
		
		$menu = array(
            'items' => array(
                array(
                    'id' => '',
                    'label' => 'Treść',
                ),
            ),
            'base' => $this->object->getUrl(),
        );
        

        
        if( @$this->object_aggs['all']['prawo']['akty_uchylajace']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_uchylajace',
	        	'label' => 'Akty uchylające',
	        	'count' => $this->object_aggs['all']['prawo']['akty_uchylajace']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['akty_uchylone']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_uchylone',
	        	'label' => 'Akty uchylone',
	        	'count' => $this->object_aggs['all']['prawo']['akty_uchylone']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['akty_wykonawcze']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_wykonawcze',
	        	'label' => 'Akty wykonawcze',
	        	'count' => $this->object_aggs['all']['prawo']['akty_wykonawcze']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['akty_uznane_za_uchylone']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_uznane_za_uchylone',
	        	'label' => 'Akty uznane za uchylone',
	        	'count' => $this->object_aggs['all']['prawo']['akty_uznane_za_uchylone']['doc_count'],
        	);
                
        if( @$this->object_aggs['all']['prawo']['akty_zmieniajace']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_zmieniajace',
	        	'label' => 'Nowelizacje',
	        	'count' => $this->object_aggs['all']['prawo']['akty_zmieniajace']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['akty_zmienione']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'akty_zmienione',
	        	'label' => 'Akty zmienione',
	        	'count' => $this->object_aggs['all']['prawo']['akty_zmienione']['doc_count'],
        	);
        
        if( @$this->object_aggs['all']['prawo']['informacja_o_tekscie_jednolitym']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'informacja_o_tekscie_jednolitym',
	        	'label' => 'Informacja o tekście jednolitym',
	        	'count' => $this->object_aggs['all']['prawo']['informacja_o_tekscie_jednolitym']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['odeslania']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'odeslania',
	        	'label' => 'Odesłania',
	        	'count' => $this->object_aggs['all']['prawo']['odeslania']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['orzeczenie_do_aktu']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'orzeczenie_do_aktu',
	        	'label' => 'Orzeczenia do aktu',
	        	'count' => $this->object_aggs['all']['prawo']['orzeczenie_do_aktu']['doc_count'],
        	);
        
        if( @$this->object_aggs['all']['prawo']['orzeczenie_tk']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'orzeczenia_tk',
	        	'label' => 'Orzeczenia TK',
	        	'count' => $this->object_aggs['all']['prawo']['orzeczenie_tk']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['podstawa_prawna']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'podstawa_prawna',
	        	'label' => 'Podstawa prawna',
	        	'count' => $this->object_aggs['all']['prawo']['podstawa_prawna']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['tekst_jednolity_do_aktu']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'tekst_jednolity_do_aktu',
	        	'label' => 'Tekst jednolity do aktu',
	        	'count' => $this->object_aggs['all']['prawo']['tekst_jednolity_do_aktu']['doc_count'],
        	);
        	
        if( @$this->object_aggs['all']['prawo']['uchylenia_wynikajace_z']['doc_count'] )
        	$menu['items'][] = array(
	        	'id' => 'uchylenia_wynikajace_z',
	        	'label' => 'Uchylenia wynikające z',
	        	'count' => $this->object_aggs['all']['prawo']['uchylenia_wynikajace_z']['doc_count'],
        	);
        
        return $menu;
	}

} 