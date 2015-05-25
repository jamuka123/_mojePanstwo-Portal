<div class="appHeader">
    <div class="container">
        <div class="holder row">
            <div class="col-md-8">
                <? if (isset($_app['name'])) { ?>
                    <h1>
                        <a href="<?= $_app['href'] ?>"><i class="glyphicon"
                                                          data-icon-applications="<?= $_app['icon'] ?>"></i><?= $_app['name'] ?>
                        </a>
                    </h1>
                <? } ?>
            </div>

            <? if (isset($dataBrowser['chapters']) && !empty($dataBrowser['chapters'])) { ?>
                <div class="col-md-4">
                    <div class="goto text-right">
                        <select class="selectpicker" data-style="btn-default" title="Przejdź do...">
                            <? foreach ($dataBrowser['chapters']['chapters'] as $chapter_id => $chapter) { ?>
                                <option
                                    <? if ((isset($dataBrowser['chapters']['selected'])) && ($chapter_id == $dataBrowser['chapters']['selected'])) { ?>selected="selected"
                                    <? } ?>href="<?= $chapter['href'] ?>"><?= $chapter['label'] ?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
    <? /*
<? if(isset($appSettings['menu'])) { ?>
    <div class="menu">
        <div class="container">
            <ul class="nav nav-tabs">
                <? foreach($appSettings['menu'] as $menuItem) { ?>
                    <? $isActive = (bool) (isset($appSettings['menuSelected']) && ($menuItem['id'] == $appSettings['menuSelected'])); ?>
                    <? $isDropDown = (bool) (isset($menuItem['dropdown'])); ?>
                    <?
                    if($isDropDown && isset($appSettings['menuSelected'])) {
                        // sprawdzamy czy w submenu jest zaznaczona jakaś opcja, jeżeli
                        // tak - ustawiamy całe drzewo jako active
                        foreach($menuItem['dropdown'] as $_menuItem) {
                            if($_menuItem['id'] == $appSettings['menuSelected']) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    ?>
                    <li class="<?= $isActive ? 'active' : ''; ?> <?= $isActive ? 'dropdown ' : ''; ?>">
                        <a <?= isset($menuItem['dropdown']) ? 'class="dropdown-toggle" data-toggle="dropdown"' : ''; ?> href="<?= isset($menuItem['href']) ? '/'.$menuItem['href'] : '/'.strtolower($this->request->params['plugin']).'/'.$menuItem['id']; ?>">
                            <?= $menuItem['label'] ?>
                            <? if(isset($menuItem['dropdown'])) { ?>
                                <span class="caret"></span>
                            <? } ?>
                        </a>
                        <? if(isset($menuItem['dropdown'])) { ?>
                            <ul class="dropdown-menu" role="menu">
                                <? foreach($menuItem['dropdown'] as $menuItem) { ?>
                                    <li <?= (isset($appSettings['menuSelected']) && ($menuItem['id'] == $appSettings['menuSelected'])) ? 'class="active"' : '' ?>>
                                        <a href="<?= isset($menuItem['href']) ? '/'.$menuItem['href'] : '/'.strtolower($this->request->params['plugin']).'/'.$menuItem['id']; ?>">
                                            <?= $menuItem['label']; ?>
                                        </a>
                                    </li>
                                <? } ?>
                            </ul>
                        <? } ?>
                    </li>
                <? } ?>
            </ul>
        </div>
    </div>
<? } ?> <? */ ?>
</div>