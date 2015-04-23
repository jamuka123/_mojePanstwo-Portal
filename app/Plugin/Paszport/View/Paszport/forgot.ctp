<?php $this->Combinator->add_libs('css', $this->Less->css('paszport', array('plugin' => 'Paszport'))) ?>

<div class="objectsPage fullPageHeight"
     style="background-image: url('/img/home/backgrounds/home-background-default.jpg')">
    <div class="forgotPassword" id="modalPaszportLoginForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <? if (isset($tokenSuccess) && $tokenSuccess) { ?>
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><?= _('LC_PASZPORT_CHANGE_PASSWORD') ?></h4>
                    </div>
                    <div class="modal-body">
                        <?php echo $this->Form->create('User', array('action' => 'forgot')); ?>
                        <? if (isset($newPasswordSuccess) && $newPasswordSuccess) { ?>
                            <p class="text-success"><?= _('LC_PASZPORT_CHANGEPASSWORD_SAVED') ?></p>
                        <? } else { ?>
                            <input type="hidden" name="token" value="<?= $token ?>"/>
                        <? } ?>
                        <div class="slide inputForm col-xs-12">
                            <?php echo $this->Form->input('User.password', array(
                                'class' => 'input-xlarge form-control',
                                'type' => 'password',
                                'label' => 'Nowe hasło',
                                'autocomplete' => 'off',
                                'required' => 'required',
                                'after' => '<span class="help-block"></span>'
                            )); ?>
                        </div>
                        <div class="slide inputForm sendForm col-xs-12">
                            <?php echo $this->Form->submit(_('LC_PASZPORT_CHANGEPASSWORD_BUTTON'), array('class' => 'btn btn-primary sendForm')); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                <? } else { ?>
                    <div class="modal-header">
                        <h4 class="modal-title"
                            id="myModalLabel"><?php echo __d('paszport', "LC_PASZPORT_PASSWORD_FORGOT_MOTTO") ?></h4>
                    </div>
                    <div class="modal-body">

                        <? if (isset($success) && $success) { ?>
                            <p class="text-success"><?= _('LC_PASZPORT_CHANGEPASSWORD_EMAIL_SENDED') ?></p>
                        <? } ?>

                        <div class="slide inputForm textForm col-xs-12">
                            <?php echo __d('paszport', "LC_PASZPORT_FORGOT_PASSWORD_EVANGELION"); ?>
                        </div>

                        <?php echo $this->Form->create('User', array('action' => 'forgot')); ?>

                        <div class="slide inputForm col-xs-12">
                            <?php echo $this->Form->input('User.email', array(
                                'class' => 'input-xlarge form-control',
                                'type' => 'email',
                                'label' => __d('paszport', "LC_PASZPORT_FORGET_EMAIL", true),
                                'autocomplete' => 'off',
                                'data-validation-required-message' => __d('paszport', "LC_PASZPORT_EMAIL_REQUIRED", true),
                                'required' => 'required',
                                'data-validation-email-message' => __d('paszport', "LC_PASZPORT_NOT_A_VALID_EMAIL", true),
                                'after' => '<span class="help-block"></span>'
                            )); ?>
                        </div>

                        <div class="slide inputForm sendForm col-xs-12">
                            <?php echo $this->Form->submit(__d('paszport', 'LC_PASZPORT_FORGET_BUTTON'), array('class' => 'btn btn-primary sendForm')); ?>
                        </div>

                        <?php echo $this->Form->end(); ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>