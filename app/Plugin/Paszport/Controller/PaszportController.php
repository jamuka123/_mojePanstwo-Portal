<?php

App::uses('ApplicationsController', 'Controller');
App::import('Model', 'Paszport.User');

class PaszportController extends ApplicationsController
{
	public $settings = array(
		'menu' => array(
			array(
				'id' => '',
				'label' => 'Zaloguj',
                'href' => 'paszport'
			),
			array(
				'id' => 'register',
				'label' => 'Zarejestruj',
                'href' => 'register'
			),
		),
		'title' => 'Paszport',
		'subtitle' => '',
		'headerImg' => 'paszport',
	);

    public function login()
    {
        if($this->request->is('post')) {
            try {
                $user = $this->Auth->login();
            } catch(Exception $e) {
                $this->Session->setFlash(
                    __($e->getMessage()),
                    'default',
                    array(),
                    'auth'
                );
            }
        }

        $this->setMenuSelected();
    }

    public function register()
    {
        if($this->request->isPost()) {
            $user = new User();
            $response = $user->register($this->data);
            debug($response);
        }

        $languages = array(
            'language' => array(
                'Polski',
                'English'
            )
        );

        $groups = array(
            'group' => array(
                'LC_PERSONAL',
                'LC_INSTITUTION'
            )
        );

        foreach($groups['group'] as &$group)
            $group = __d('paszport', $group, true);

        $this->set('languages', $languages['language']);
        $this->set('groups', $groups['group']);
    }

} 