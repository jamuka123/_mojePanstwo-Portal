<?
$this->Combinator->add_libs('css', $this->Less->css('view-gminy', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', 'Dane.dataobjects-ajax');
$this->Combinator->add_libs('js', 'Dane.filters');

if ($object->getId() == '903') {
    $this->Combinator->add_libs('css', $this->Less->css('view-gminy-krakow', array('plugin' => 'Dane')));
}

echo $this->Element('dataobject/pageBegin', array(
    'titleTag' => 'p',
));

echo $this->Element('Dane.dataobject/subobject', array(
    'object' => $radny,
    'objectOptions' => array(
        'hlFields' => array('komitet', 'liczba_glosow', 'procent_glosow_w_okregu'),
        'bigTitle' => true,
    )
));

echo $this->Element('Dane.DataBrowser/browser');

echo $this->Element('dataobject/pageEnd');
