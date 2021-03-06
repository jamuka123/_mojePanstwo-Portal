<?
$this->Combinator->add_libs('css', $this->Less->css('view-gminy', array('plugin' => 'Dane')));
if ($object->getId() == '903') {
    $this->Combinator->add_libs('css', $this->Less->css('view-gminy-krakow', array('plugin' => 'Dane')));
}

echo $this->Element('dataobject/pageBegin', array(
    'titleTag' => 'p',
));
?>

<?
echo $this->Element('Dane.dataobject/subobject', array(
    'object' => $radny,
    'objectOptions' => array(
        'hlFields' => array('komitet', 'liczba_glosow'),
        'bigTitle' => true,
    )
)); ?>

<?
if (!isset($_submenu['base']))
    $_submenu['base'] = $radny->getUrl();

$options = array(
    'menu' => $_submenu,
    'class' => 'margin-top--5',
);

if (isset($title))
    $options['title'] = $title;

echo $this->Element('Dane.DataBrowser/browser', $options);
?>

<?php
echo $this->Element('dataobject/pageEnd', array(
    'titleTag' => 'p',
));
?>
