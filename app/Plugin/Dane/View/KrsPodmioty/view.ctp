<?
if (isset($odpis) && $odpis) {
    $this->Html->meta(array(
        'http-equiv' => "refresh",
        'content' => "0;URL='$odpis'"
    ), null, array('inline' => false));
}

echo $this->Element('dataobject/pageBegin');

echo $this->Html->script('Dane.d3/d3', array('block' => 'scriptBlock'));

$this->Combinator->add_libs('css', $this->Less->css('dataobjectslider', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-krspodmioty', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-krs-graph', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', 'Dane.view-krspodmioty');
$this->Combinator->add_libs('js', 'graph-krs');

?>

<div class="krsPodmioty">
	<div class="col-md-9 objectMain">
	    <div class="object">
	        <? if ($object->getData('wykreslony')) { ?>
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        Prezentowane dane dotyczą chwili, w której podmiot był wykreślany z KRS.
    </div>
<? }

$adres = $object->getData('adres_ulica');
$adres .= ' ' . $object->getData('adres_numer');
$adres .= ', ' . $object->getData('adres_miejscowosc');
$adres .= ', Polska';

if (($object->getData('adres_ulica')) && ($object->getData('adres_numer')) && ($object->getData('adres_miejscowosc'))) { ?>
    <div class="block block-simple col-xs-12 adres">
        <header>
            <div class="sm">Adres</div>
            <div class="mapsOptions pull-right">
                <button
                    class="googleMap btn btn-sm btn-default"><?= __d('dane', 'LC_DANE_VIEW_KRSPODMIOTY_OTWORZ_MAPE') ?></button>
                <button
                    class="streetView btn btn-sm btn-default"><?= __d('dane', 'LC_DANE_VIEW_KRSPODMIOTY_OTWORZ_MAPE_STREET') ?></button>
            </div>
        </header>

        <section class="profile_baner nopadding" data-adres="<?= urlencode($adres) ?>">
            <div class="bg">
                <img
                    src="http://maps.googleapis.com/maps/api/staticmap?center=<?= urlencode($adres) ?>&markers=<?= urlencode($adres) ?>&zoom=15&sensor=false&size=831x212&scale=2&feature:road"/>

                <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"
                     class="content">
                    <p itemprop="streetAddress">
                        ul. <?= $object->getData('adres_ulica') ?> <?= $object->getData('adres_numer') ?><? if ($object->getData('adres_lokal')) { ?>/<?= $object->getData('adres_lokal') ?><? } ?></p>
                    <? if ($object->getData('adres_poczta') != $object->getData('adres_miejscowosc')) { ?>
                        <p><?= $object->getData('adres_miejscowosc') ?></p><? } ?>
                    <p><span itemprop="postalCode"><?= $object->getData('adres_kod_pocztowy') ?></span> <span
                            itemprop="addressLocality"><?= $object->getData('adres_poczta') ?></span>
                    </p>

                    <p><?= $object->getData('adres_kraj') ?></p>
                </div>
            </div>
            <div class="googleView">
                <script>
                    var googleMapAdres = '<?= $adres ?>';
                </script>
                <div id="googleMap"></div>
                <div id="streetView"></div>
            </div>
        </section>
    </div>
<? }

if ($object->getId() == '481129') { ?>
    <div class="special banner">
        <a title="Zobacz umowy podpisywane przez Komitet Konkursowy Kraków 2022"
           href="/dane/krs_podmioty/481129/umowy">
            <img src="/Dane/img/krakow_special_banner.png" width="885" height="85"/>
        </a>
    </div>
<?php }

if ($object->getData('cel_dzialania')) { ?>
    <div class="dzialanie block col-xs-12">
        <header>
            <div class="sm">Cel działania</div>
        </header>

        <section class="content normalizeText textBlock">
            <?= $object->getData('cel_dzialania') ?>
        </section>
    </div>
<? }

if ($object->getData('sposob_reprezentacji')) { ?>
    <div class="reprezentacja block col-xs-12">
        <header>
            <div class="sm">Sposób reprezentacji</div>
        </header>

        <section class="content normalizeText textBlock">
            <?= $object->getData('sposob_reprezentacji') ?>
        </section>
    </div>
<? } ?>

	        <div class="organy block-group col-xs-12 col-xs-12">
                <? if ($organy_count = count($organy)) {
    if ($organy_count == 1) {
        $column_width = 12;
    } elseif ($organy_count == 2) {
        $column_width = 6;
    } elseif ($organy_count == 3) {
        $column_width = 4;
    } else {
        $column_width = 6;
    }

    foreach ($organy as $organ) { ?>
                        <div class="block col-lg-<?= $column_width ?>">
                            <header>
                                <div class="sm normalizeText" id="<?= $organ['idTag'] ?>"><?= $organ['title'] ?></div>
                            </header>

                            <? if ($organ['content']) { ?>
                                <section class="list-group less-borders">
                                    <? foreach ($organ['content'] as $osoba) { ?>
        <? if (@$osoba['osoba_id']) { ?>
            <a class="list-group-item" href="/dane/krs_osoby/<?= $osoba['osoba_id'] ?>" itemprop="member" itemscope itemtype="http://schema.org/OrganizationRole">
        <? } elseif (@$osoba['krs_id']) { ?>
            <a class="list-group-item" href="/dane/krs_podmioty/<?= $osoba['krs_id'] ?>" itemprop="member" itemscope itemtype="http://schema.org/OrganizationRole">
        <? } else { ?>
            <div class="list-group-item" itemprop="member" itemscope itemtype="http://schema.org/OrganizationRole">
        <? } ?>

        <h4 class="list-group-item-heading" itemprop="member" itemscope itemtype="http://schema.org/OrganizationPerson">
            <span itemprop="name"><?= $osoba['nazwa'] ?></span>
            <? if (
                ($osoba['privacy_level'] != '1') &&
                $osoba['data_urodzenia'] &&
                $osoba['data_urodzenia'] != '0000-00-00'
            ) {
                ?>
                <span itemprop="birthDate" datetime="<?= substr($osoba['data_urodzenia'], 0, 4) ?>"
                      class="wiek"><?= substr($osoba['data_urodzenia'], 0, 4) ?>'</span>
            <? } ?>
        </h4>

        <? if (isset($osoba['funkcja']) && $osoba['funkcja']) {
            if ($organ['idTag'] == 'reprezentacja') {
                $useLabel = true;
                $class = 'warning';

                foreach (array('prezes', 'prezydent', 'przewodnicząc') as $phr) {
                    if (stripos($osoba['funkcja'], ltrim($phr)) === 0) {
                        $class = 'danger';
                        break;
                    }
                }
            } else {
                $useLabel = false;
            } ?>

            <p itemprop="namedPosition"
               class="list-group-item-text <? if ($useLabel) { ?> label label-<?= $class ?><? } ?>"><?= $osoba['funkcja'] ?></p>
        <? } ?>
        <? if (@$osoba['osoba_id'] || @$osoba['krs_id']) { ?>
            </a>
        <? } else { ?>
            </div>
        <? } ?>
    <? } ?>
                                </section>
                            <? } ?>
                        </div>
                    <? } ?>
<? } ?>
            </div>

            <? if ($wspolnicy = $object->getLayer('wspolnicy')) { ?>
                <div class="wspolnicy block col-xs-12">
                    <header>
                        <div class="sm">Udziałowcy:</div>
                    </header>

                    <section>
                        <div id="wspolnicy_graph">
                            <div class="list-group less-borders wspolnicy">
                                <? foreach ($wspolnicy as $osoba) { ?>
                                    <span itemprop="member" itemscope itemtype="http://schema.org/OrganizationRole">
                                        <? if (@$osoba['osoba_id']) {
    $class = "Person"; ?>
    <a class="list-group-item row" href="/dane/krs_osoby/<?= $osoba['osoba_id'] ?>">
<? } elseif (@$osoba['krs_id']) {
    $class = "Organization"; ?>
    <a class="list-group-item row" href="/dane/krs_podmioty/<?= $osoba['krs_id'] ?>">
<? } else {
    $class = "Intangible"; ?>
    <div class="list-group-item row">
<? } ?>

                                        <h4 class="list-group-item-heading col-xs-6" itemprop="member" itemscope itemtype="http://schema.org/Organization<?= $class ?>">
                                            <span itemprop="name"><?= $osoba['nazwa'] ?></span>
                                            <? if (($osoba['privacy_level'] != '1') && $osoba['data_urodzenia'] && $osoba['data_urodzenia'] != '0000-00-00') { ?>
    <span itemprop="birthDate"
          datetime="<?= substr($osoba['data_urodzenia'], 0, 4) ?>"
          class="wiek"><?= substr($osoba['data_urodzenia'], 0, 4) ?>'</span>
<? } ?>
                                        </h4>

                                        <? if (isset($osoba['funkcja']) && $osoba['funkcja']) { ?>
    <p itemprop="namedPosition" class="list-group-item-text normalizeText col-xs-6"><?= $osoba['funkcja'] ?></p>
<? } ?>

    <? if (@$osoba['osoba_id'] || @$osoba['krs_id']) { ?>
        </a>
    <? } else { ?>
        </div>
    <? } ?>
                                    </span>
                                <? } ?>
                            </div>
                        </div>
                    </section>
                </div>
            <? } ?>

<? if ($firmy = $object->getLayer('firmy')) { ?>
    <div class="wspolnicy block col-xs-12">
        <header>
            <div class="sm">Ta firma posiada udziały w:</div>
        </header>

        <section id="wspolnicy_graph">
            <div class="list-group less-borders wspolnicy">
                <? foreach ($firmy as $firma) { ?>
                    <a class="list-group-item row" href="/dane/krs_podmioty/<?= $firma['id'] ?>">
                        <div class="list-group-item row">
                            <h4 class="list-group-item-heading col-xs-6">
                                <?= $firma['nazwa'] ?>
                            </h4>

                            <? if (isset($firma['udzialy_str']) && $firma['udzialy_str']) { ?>
                                <p class="list-group-item-text normalizeText col-xs-6">
                                    <?= $firma['udzialy_str'] ?>
                                </p>
                            <? } ?>
                        </div>
                    </a>
                <? } ?>
            </div>
        </section>
    </div>
<? } ?>
        </div>
    </div>
	
	<div class="col-md-3 objectSide">
	    <? if (!$object->getData('wykreslony')) { ?>
    <div class="banner block">
        <?php echo $this->Html->image('Dane.banners/krspodmioty_banner.png', array(
            'width' => '69',
            'alt' => 'Aktualny odpis z KRS za darmo',
            'class' => 'pull-right'
        )); ?>
        <p>Pobierz aktualny odpis z KRS <strong>za darmo</strong></p>
        <a href="/dane/krs_podmioty/<?= $object->getId() ?>/odpis" class="btn btn-primary">Kliknij aby
            pobrać</a>
    </div>
<? } ?>
	
	<? /*
	<div class="banner block">
        <?php echo $this->Html->image('Dane.banners/krspodmioty_banner.png', array(
            'width' => '69',
            'alt' => 'Aktualny odpis z KRS za darmo',
            'class' => 'pull-right'
        )); ?>
        <p>Napisz i <strong>wyślij pismo</strong> do tej organizacji</p>
        <a href="/dane/krs_podmioty/<?= $object->getId() ?>/odpis" class="btn btn-primary">Stwórz pismo</a>
    </div>
    */ ?>
		
		
	</div>
</div>

<div class="powiazania block block-simple col-xs-12">
    <header>
        <div class="sm">Powiązania</div>
    </header>
</div>

</div></div>

<div class="powiazania block block-simple col-xs-12">
    <section id="connectionGraph" class="loading" data-id="<?php echo $object->getId() ?>" data-url="krs_podmioty"></section>
</div>

<div class="container"><div class="objectsPageContent main">
	
<div class="krsPodmioty">
	<div class="col-md-9 objectMain">
	    <div class="object">
	        
	        <? if( $dzialalnosci = $object->getLayer('dzialalnosci') ) {?>
		    <div class="block block-default col-xs-12">
		        <header>Działalność według PKD</header>
		        <section>
			        
			        <ul>
				    <? foreach( $dzialalnosci as $d ) {?>
				    	<li><?= $d['str'] ?></li>
				    <? } ?>
			        </ul>
			        
		        </section>
		    </div>
		    <? } ?>
		    
	    </div>
	</div>
</div>

	
<?= $this->Element('dataobject/pageEnd'); ?>