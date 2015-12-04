<?
echo $this->Combinator->add_libs('css', $this->Less->css('view-gminy', array('plugin' => 'Dane')));
echo $this->Combinator->add_libs('css', $this->Less->css('view-gminy-dyzury', array('plugin' => 'Dane')));
echo $this->Combinator->add_libs('css', $this->Less->css('view-gminy-radny-obietnice', array('plugin' => 'Dane')));
echo $this->Combinator->add_libs('css', $this->Less->css('DataBrowser', array('plugin' => 'Dane')));
echo $this->Combinator->add_libs('js', 'Dane.view-gminy-dyzury');

if ($object->getId() == '903') {
    $this->Combinator->add_libs('css', $this->Less->css('view-gminy-krakow', array('plugin' => 'Dane')));
}

echo $this->Element('dataobject/pageBegin', array(
    'titleTag' => 'p',
));

echo $this->Element('Dane.dataobject/subobject', array(
    'object' => $radny,
    'objectOptions' => array(
        'hlFields' => array('komitet', 'liczba_glosow'),
        'bigTitle' => true,
    )
));

?>

<div class="dataBrowser margin-top--5">
    <div class="row">
        <div class="dataBrowserContent">
            <div class="col-xs-12 col-sm-4 col-md-1-5 dataAggsContainer">
				<div class="mp-sticky mp-sticky-disable-sm-4" data-widthFromWrapper="false">
					
                <? if (isset($_submenu) && isset($_submenu['items'])) {

                    if (!isset($_submenu['base']))
                        $_submenu['base'] = $radny->getUrl();

                    echo $this->Element('Dane.DataBrowser/browser-menu', array(
                        'menu' => $_submenu,
                    ));

                } ?>
                
				</div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-4-5 norightpadding">
	            
	            <div class="dataWrap">
	            	
	            	<h1 class="smaller">Obietnice złożone przez radnego</h1>
	            	
	                <div class="object radny-obietnice">

			            <ul class="list-unstyled">
			                <? foreach ($radny->getLayer('obietnice') as $obietnica) {
			                    if (!@$obietnica['text']) continue; ?>
			
			                    <li class="panel panel-default<? if (isset($obietnica['do_sprawdzenia']) && !empty($obietnica['do_sprawdzenia'])) { ?> checking<? } ?>">
			                        <div class="panel-header">
			                            <div class="info date"><span class="glyphicon glyphicon-calendar"
			                                                         aria-hidden="true"></span><?= $obietnica['znaleziono'] ?>
			                            </div>
			                            <? if (isset($obietnica['zrodlo_url']) && !empty($obietnica['zrodlo_url'])) { ?>
			                                <a href="<?= $obietnica['zrodlo_url'] ?>" target="_blank"
			                                   class="info btn btn-link"><span class="glyphicon glyphicon-globe"
			                                                                   aria-hidden="true"></span>Źródło</a>
			                            <? } ?>
			                            <? if (isset($obietnica['zrzut_url']) && !empty($obietnica['zrzut_url'])) { ?>
			                                <a href="<?= $obietnica['zrzut_url'] ?>" target="_blank" class="info btn btn-link"><span
			                                        class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Zrzut ekranu</a>
			                            <? } ?>
			                            <? if (isset($obietnica['do_sprawdzenia']) && !empty($obietnica['do_sprawdzenia'])) { ?>
			                                <a href="<?= $obietnica['do_sprawdzenia'] ?>" target="_blank"
			                                   class="info btn btn-link pull-right"><span class="glyphicon glyphicon-lock"
			                                                                              aria-hidden="true"></span>W trakcie
			                                    sprawdzania</a>
			                            <? } ?>
			                        </div>
			                        <div class="panel-body">
			                            <?= nl2br($obietnica['text']) ?>
			                        </div>
			                    </li>
			                <? } ?>
			            </ul>
			        </div>
                
	            </div>
                
            </div>
        </div>
    </div>
</div>




    

<?
echo $this->Element('dataobject/pageEnd');
?>
