<?
echo $this->Element('dataobject/pageBegin');

echo $this->Html->script('Dane.d3/d3', array('block' => 'scriptBlock'));

$this->Combinator->add_libs('css', $this->Less->css('view-krs-graph', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', 'Dane.view-krsosoby');
$this->Combinator->add_libs('js', 'graph-krs');

?>
    <div class="krsOsoby row">

        <div class="col-md-9 objectMain">
            <div class="object">
                <div class="block-group col-xs-12">

                    <? if ($organizacje = $object->getLayer('organizacje')) {

                        echo $this->Element('Dane.objects/krs_osoby/organizacje', array(
                            'organizacje' => $organizacje,
                        ));

                    } ?>
					
					<? /*
                    <div class="powiazania block">
                        <div class="block-header"><h2 class="label">Powiązania</h2></div>
                        <div id="connectionGraph" class="loading" data-id="<?php echo $object->getId() ?>"
                             data-url="krs_osoby"></div>
                    </div>
                    */ ?>

                </div>
            </div>
        </div>
    </div>
<?= $this->Element('dataobject/pageEnd'); ?>