<?php

class PrestadoresController extends AppController
{

    public $uses = ['Prestador', 'Servico'];
    public $components = ['Session'];


    public function index()
    {
        $this->paginate = [
            'limit' => 5,
            'order' => ['Prestador.id' => 'DESC'],
            'contain' => ['Servico']
        ];

        $prestadores = $this->paginate('Prestador');
        $this->set('prestadores', $prestadores);
    }


    public function add()
    {
        if ($this->request->is('post')) {
            if (!empty($this->request->data['Prestador']['foto']['name'])) {
                $this->request->data['Prestador']['foto'] =
                    $this->_uploadFoto($this->request->data['Prestador']['foto']);
            } else {
                unset($this->request->data['Prestador']['foto']);
            }

            $this->Prestador->create();

            if ($this->Prestador->save($this->request->data)) {
                $this->Session->setFlash('Prestador cadastrado com sucesso.', 'default', ['class'=>'success-message'], 'success');
                return $this->redirect(['action' => 'index']);
            }

            $this->Session->setFlash('Erro ao cadastrar. Verifique os dados.', 'default', ['class'=>'warning-message'], 'error');
        }

        $servicos = $this->Servico->find('list', ['fields' => ['id', 'nome']]);
        $this->set('servicos', $servicos);
        $this->set('title', 'Adicionar Prestador');
    }


    public function edit($id = null)
    {
        if (!$id || !$this->Prestador->exists($id)) {
            throw new NotFoundException("Prestador não encontrado.");
        }

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->request->data['Prestador']['foto']['name'])) {
                $this->request->data['Prestador']['foto'] =
                    $this->_uploadFoto($this->request->data['Prestador']['foto']);
            } else {
                unset($this->request->data['Prestador']['foto']);
            }

            if ($this->Prestador->save($this->request->data)) {
                $this->Session->setFlash('Prestador atualizado.', 'default', ['class'=>'success-message'], 'success');
                return $this->redirect(['action' => 'index']);
            }

            $this->Session->setFlash('Erro ao atualizar.', 'default', ['class'=>'warning-message'], 'error');
        } else {
            $this->request->data = $this->Prestador->findById($id);
        }

        $servicos = $this->Servico->find('list', ['fields' => ['id', 'nome']]);
        $this->set('servicos', $servicos);
        $this->set('id', $id);
    }

    public function delete($id = null)
    {
        if (!$id) {
            throw new NotFoundException("ID inválido");
        }

        $prestador = $this->Prestador->findById($id);

        if ($this->Prestador->delete($id)) {
            if (!empty($prestador['Prestador']['foto'])) {
                $caminhoFoto = WWW_ROOT . 'img' . DS . 'perfil' . DS . $prestador['Prestador']['foto'];
                if (file_exists($caminhoFoto)) {
                    unlink($caminhoFoto);
                }
            }
            $this->Session->setFlash('Prestador removido.', 'default', ['class'=>'message success'], 'success');
        } else {
            $this->Session->setFlash('Erro ao remover.', 'default', ['class'=>'message warning'], 'error');
        }

        return $this->redirect(['action' => 'index']);
    }

    private function _uploadFoto($foto)
    {
        $uploadDir = WWW_ROOT . 'img' . DS . 'perfil' . DS;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!is_writable($uploadDir)) {
            throw new Exception("Diretório sem permissão de escrita.");
        }

        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($extensao), $extensoesPermitidas)) {
            throw new Exception("Tipo de arquivo não permitido.");
        }

        $nome = time() . '_' . md5(uniqid()) . '.' . $extensao;
        $destino = $uploadDir . $nome;

        if (!move_uploaded_file($foto['tmp_name'], $destino)) {
            throw new Exception("Erro ao fazer upload da foto.");
        }

        return $nome;
    }
}
