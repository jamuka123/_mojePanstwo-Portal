<?php $this->Combinator->add_libs('css', $this->Less->css('paszport', array('plugin' => 'Paszport'))) ?>
<? $this->Combinator->add_libs('js', 'Paszport.paszport-profile.js'); ?>
<?= $this->Element('appheader'); ?>
<div class="objectsPage">
    <div class="container">
        <div class="row editProfile">
            <div class="col-md-8">
                <h3>Podstawowe informacje</h3>
                <form>
                    <div class="form-group">
                        <label for="inputUserName">Nazwa użytkownika</label>
                        <div class="form-user-edit" data-field="username">
                            <a href="#" title="Edytuj">
                                <b><?= $user['username']; ?></b>
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Adres E-mail</label>
                        <div class="form-user-edit" data-field="email">
                            <a href="#" title="Edytuj">
                                <b><?= $user['email']; ?></b>
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="inputPassword">Hasło</label>
                            <div id="form-user-edit-password">
                                <a href="#" title="Edytuj">
                                    ******
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <h3>Dodatkowe opcje</h3>
                <button type="button" id="deletePaszportButton" class="btn btn-default remove-button" data-toggle="modal" data-target=".delete-paszport-modal">
                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    Usuń paszport
                </button>
                <div class="modal fade delete-paszport-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Usuwanie paszportu</h4>
                            </div>
                            <div class="modal-body">
                                <p>Wprowadź aktualne hasło aby potwierdzić operację usunięcia Paszportu.</p>
                                <div class="form-group">
                                    <label for="inputDeletePassword">Hasło</label>
                                    <input id="inputDeletePassword" value="" type="password" class="form-control" name="deletePassword">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                                <button type="button" id="submitDeletePaszport" class="btn btn-primary">Potwierdź</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>
</div>