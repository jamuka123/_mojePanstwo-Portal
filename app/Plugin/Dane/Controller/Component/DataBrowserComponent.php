<?php
class DataBrowserComponent extends Component {
	
	public $settings = array();
	public $conditions = array();
	private $Dataobject = false;
	private $aggs_visuals_map = array();
	
	private $aggs_presets = array(
		'prawo' => array(
	        'typ_id' => array(
	            'terms' => array(
		            'field' => 'prawo.typ_id',
		            'exclude' => array(
			            'pattern' => '0'
		            ),
	            ),
	            'aggs' => array(
		            'label' => array(
			            'terms' => array(
				            'field' => 'data.prawo.typ_nazwa',
			            ),
		            ),
	            ),
	            'visual' => array(
		            'label' => 'Typy aktów prawnych',
		            'skin' => 'pie_chart',
	            ),
	        ),
	        'date' => array(
	            'date_histogram' => array(
		            'field' => 'date',
		            'interval' => 'year',
		            'format' => 'yyyy-MM-dd',
	            ),
	            'visual' => array(
		            'label' => 'Liczba aktów prawnych w czasie',
		            'skin' => 'date_histogram',
	            ),
	        ),
	        'autor_id' => array(
	            'terms' => array(
		            'field' => 'prawo.autor_id',
		            'exclude' => array(
			            'pattern' => '0'
		            ),
	            ),
	            'aggs' => array(
		            'label' => array(
			            'terms' => array(
				            'field' => 'data.prawo.autor_nazwa',
			            ),
		            ),
	            ),
	            'visual' => array(
		            'label' => 'Autorzy aktów prawnych',
		            'skin' => 'columns_horizontal',
	            ),
	        ),
	    )
	);
	
	public function __construct($collection, $settings) {
				
		if( 
			(
				!isset( $settings['aggs'] ) || 
				( empty($settings['aggs']) ) 
			) &&  
			isset( $settings['aggsPreset'] ) && 
			array_key_exists($settings['aggsPreset'],  $this->aggs_presets) 
		)
			$settings['aggs'] = $this->aggs_presets[ $settings['aggsPreset'] ];
		
		
        foreach($settings['aggs'] as $key => $value) {
            foreach($value as $keyM => $valueM) {
                if($keyM === 'visual') {
                    $this->aggs_visuals_map[$key] = $valueM;
                    unset($settings['aggs'][$key][$keyM]);
                }
            }
        }

		$this->settings = $settings;
		
	}

    private function getCancelSearchUrl($controller) {
        if(!isset($controller->request->query) || count($controller->request->query) === 0)
            return $controller->here;

        $query = $controller->request->query;

        if(isset($query['q']))
            unset($query['q']);

        if(isset($query['page']))
            unset($query['page']);

        if(isset($query['conditions']['q']))
            unset($query['conditions']['q']);

        if(count(@array_count_values($query)) > 0 || count($query['conditions']) > 0)
            $query = '?' . http_build_query($query);
        else
            $query = '';

        return $controller->here . $query;
    }
	
	public function beforeRender($controller){
		
		$controller->helpers[] = 'Dane.Dataobject';
		
		if( is_null($controller->Paginator) ) {
			$controller->Paginator = $controller->Components->load('Paginator');
		}
		
		if( isset( $controller->request->query['q'] ) ) {
			$controller->request->query['conditions']['q'] = $controller->request->query['q'];
		}
			
		$this->queryData = $controller->request->query;

		if( !property_exists($controller, 'Dataobject') )
			$controller->Dataobject = ClassRegistry::init('Dane.Dataobject');
		
		$controller->Paginator->settings = $this->getSettings();		
		$hits = $controller->Paginator->paginate('Dataobject');

	    $controller->set('dataBrowser', array(
		    'hits' => $hits,
		    'aggs' => $controller->Dataobject->getAggs(),
            'aggs_visuals_map' => $this->aggs_visuals_map,
		    'cancel_url' => $this->getCancelSearchUrl($controller),
	    ));
		
	}
	
	
	private function getSettings() {
		
		$conditions = $this->getSettingsForField('conditions');
		
		$output = array(
			'paramType' => 'querystring',
			'conditions' => $conditions,
			'aggs' => $this->getSettingsForField('aggs'),
		);
				
		if( isset($conditions['q']) )
			$output['highlight'] = true;
		
		return $output;
		
	}
	
	private function getSettingsForField($field) {
		
		$params = isset( $this->queryData[$field] ) ? $this->queryData[$field] : array();
				
		if( isset($this->settings[$field]) )
			$params = array_merge($params, $this->settings[$field]);
			
		return $params;
		
	}
	
}