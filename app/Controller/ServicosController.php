<?php

class ServicosController extends AppController {
    public $uses = ['Servico'];


    public function add() {
        if ($this->request->is('post')) {
            $this->Servico->create();
            if ($this->Servico->save($this->request->data)) {
                $this->Session->setFlash('Serviço cadastrado com sucesso.', 'default', [], 'success');
                $this->render('/Prestadores/add');
            }
            $this->Session->setFlash('Erro ao cadastrar serviço. Verifique os dados.', 'default', [], 'error'); 
        }

    }
}