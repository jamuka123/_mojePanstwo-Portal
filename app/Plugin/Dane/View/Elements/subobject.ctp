<?
$path = App::path('Plugin');
$file = $path[0] . '/Dane/View/Elements/' . $theme . '/' . $object->getDataset() . '.ctp';
$file_exists = file_exists($file);

if (in_array($object->getDataset(), array('krakow_posiedzenia'))) {
    $object_content_sizes = array(3, 9);
} else {
    $object_content_sizes = array(2, 10);
}

$this->Dataobject->setObject($object);
?>
<div class="objectRender col-md-12 <?php echo $object->getDataset(); ?>" oid="<?php echo $object->getId(); ?>">
    <div class="row">
        <div class="data col-md-12">
            <div class="row">


            <div class="content">

                <<?= $titleTag ?> class="title<? if ($bigTitle) { ?> big<? } ?>">
                <?php if ($show_link && ($object->getUrl() != false)){ ?>
                <a class="trimTitle" href="<?= $object->getUrl() ?>"
                   title="<?= strip_tags($object->getTitle()) ?>">
                    <?php } ?>
                    <?= $object->getShortTitle() ?>
                    <?php if ($show_link && ($object->getUrl() != false)){ ?>
                </a> <? if ($object->getTitleAddon()) {
                echo '<small>' . $object->getTitleAddon() . '</small>';
            } ?>
            <?php } ?>
            </<?= $titleTag ?>>
            <?
            if ($file_exists) {
                echo $this->element('Dane.' . $theme . '/' . $object->getDataset(), array(
                    'item' => $item,
                    'object' => $object
                ));
            } else {
                echo $this->Dataobject->highlights($hlFields);
                if ($object->getDescription()) {
                    ?>
                    <div class="description">
                        <?= $object->getDescription() ?>
                    </div>
                <?
                }
            }
            ?>

        </div>
    </div>
</div>
</div>
</div>