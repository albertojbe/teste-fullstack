<?php

class ServicosController extends AppController
{
    public $uses = ['Servico'];


    public function add()
    {
        if ($this->request->is('post')) {
            $this->Servico->create();
            $this->Servico->set($this->request->data);

            if (!$this->Servico->validates()) {
                foreach ($this->Servico->validationErrors as $campo => $mensagens) {
                    foreach ($mensagens as $mensagem) {
                        $erros[] = "{$mensagem}";
                    }
                }
                $this->Session->setFlash(implode('<br>', $erros), 'default', ['class' => 'error-message'], 'error');
            }
            else if ($this->Servico->save($this->request->data)) {
                $this->Session->setFlash('Serviço cadastrado com sucesso.', 'default', ['class' => 'success-message'], 'success');
            } else {
                $this->Session->setFlash('Erro ao cadastrar serviço. Verifique os dados.', 'default', ['class' => 'error-message'], 'error');
            }
        }
        
        return $this->redirect($this->referer());
    }
}