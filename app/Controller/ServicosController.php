<?php

class ServicosController extends AppController
{
    public $uses = ['Servico'];
    public $components = ['Session'];

    public function index() {
        $this->paginate = [
            'limit' => 6,
            'order' => ['Servico.nome' => 'ASC'],
            'contain' => ['Prestador']
        ];

        $servicos = $this->paginate('Servico');
        $this->set(compact('servicos'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Servico->create();
            $this->Servico->set($this->request->data);

            if (!$this->Servico->validates()) {
                $erros = [];
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

    public function delete($id = null)
    {
        if (!$id) {
            throw new NotFoundException("ID inválido");
        }

        if ($this->Servico->delete($id)) {
            $this->Session->setFlash('Serviço removido com sucesso.', 'default', ['class' => 'success-message'], 'success');
        } else {
            $this->Session->setFlash('Erro ao remover serviço.', 'default', ['class' => 'warning-message'], 'error');
        }

        return $this->redirect(['action' => 'index']);
    }
}