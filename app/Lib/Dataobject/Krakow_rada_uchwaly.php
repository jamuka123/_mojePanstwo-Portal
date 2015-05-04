<?

namespace MP\Lib;
require_once('DocDataObject.php');

class Krakow_rada_uchwaly extends DocDataObject
{
	
	protected $tiny_label = 'Uchwała Rady Miasta Krakowa';
	
    protected $routes = array(
        'title' => 'tytul',
        'shortTitle' => 'tytul_skrocony',
        'date' => 'data',
        'label' => 'label',
        'description' => 'opis',
    );

    public function getLabel()
    {
        return 'Uchwała <strong>Rady Miasta Krakowa</strong> z dnia ' . dataSlownie( $this->getDate() );
    }
    
    public function getUrl()
    {
	    return '/dane/gminy/903,krakow/rada_uchwaly/' . $this->getId();
    }
    
    public function getMetaDescriptionParts($preset = false)
	{
		/*
		$output = array();
		if( $this->getData('krakow_rada_uchwaly.data') )
			$output[] = dataSlownie($this->getData('krakow_rada_uchwaly.data'));
		
		if( $this->getData('krakow_rada_uchwaly.numer_str') )
			$output[] = $this->getData('krakow_rada_uchwaly.numer_str');
				
		return $output;
		*/
		
		return false;
		
	}
	
	public $force_hl_fields = true;
	
}