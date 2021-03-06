<?

namespace MP\Lib;

class Sejm_okregi_wyborcze extends DataObject
{

    public $force_hl_fields = true;
	protected $tiny_label = 'Okręg wyborczy';

    protected $routes = array(
        'title' => 'numer',
        'shortTitle' => 'numer',
    );
	
	public function getUrl() {
		
		return '/dane/instytucje/3214/okregi/' . $this->getId() . ',' . $this->getSlug();
		
	}
		
	public function getBreadcrumbs()
	{
							
		return array(
			array(
				'id' => '/dane/instytucje/3214,sejm-rzeczypospolitej-polskiej/okregi',
				'label' => 'Okręgi w wyborach do Sejmu RP',
			),
		);
				
	}

}