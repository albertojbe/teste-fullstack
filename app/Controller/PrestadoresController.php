<?php


use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $servicos = $this->Servico->find('list', ['fields' => ['id', 'nome']]);
        $this->set('servicos', $servicos);

        if ($this->request->is('post')) {
            if (!empty($this->request->data['Prestador']['foto']['name'])) {
                try {
                    $this->request->data['Prestador']['foto'] = $this->_uploadFoto($this->request->data['Prestador']['foto']);
                } catch (Exception $e) {
                    $this->Session->setFlash(
                        'Erro ao enviar foto: ' . $e->getMessage(),
                        'default',
                        ['class' => 'error-message'],
                        'error'
                    );
                    return $this->redirect($this->referer());
                }
            } else {
                unset($this->request->data['Prestador']['foto']);
            }

            $this->Prestador->create();

            if ($this->Prestador->save($this->request->data)) {
                $this->Session->setFlash('Prestador cadastrado com sucesso.', 'default', ['class' => 'success-message'], 'success');
                return $this->redirect(['action' => 'index']);
            }

            $this->Session->setFlash('Erro ao cadastrar. Verifique os dados.', 'default', ['class' => 'warning-message'], 'error');
        }
    }


    public function edit($id = null)
    {
        $servicos = $this->Servico->find('list', ['fields' => ['id', 'nome']]);
        $this->set('servicos', $servicos);
        $this->set('id', $id);

        if (!$id || !$this->Prestador->exists($id)) {
            throw new NotFoundException("Prestador não encontrado.");
        }

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->request->data['Prestador']['foto']['name'])) {
                try {
                    $this->request->data['Prestador']['foto'] = $this->_uploadFoto($this->request->data['Prestador']['foto']);
                } catch (Exception $e) {
                    $this->Session->setFlash(
                        'Erro ao enviar foto: ' . $e->getMessage(),
                        'default',
                        ['class' => 'error-message'],
                        'error'
                    );
                    return $this->redirect($this->referer());
                }
            } else {
                unset($this->request->data['Prestador']['foto']);
            }

            if ($this->Prestador->save($this->request->data)) {
                $this->Session->setFlash('Prestador atualizado.', 'default', ['class' => 'success-message'], 'success');
                return $this->redirect(['action' => 'edit', $id]);
            }

            $this->Session->setFlash('Erro ao atualizar.', 'default', ['class' => 'warning-message'], 'error');
        } else {
            $this->request->data = $this->Prestador->findById($id);
        }

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
            $this->Session->setFlash('Prestador removido.', 'default', ['class' => 'success-message'], 'success');
        } else {
            $this->Session->setFlash('Erro ao remover.', 'default', ['class' => 'warning-message'], 'error');
        }

        return $this->redirect(['action' => 'index']);
    }

    public function importar()
    {
        $TAMANHO_MAXIMO = 25 * 1024 * 1024;
        $servicos_db = array_map('strtolower', $this->Servico->find('list', ['fields' => ['id', 'nome']]));

        try {
            if ($this->request->is('post')) {
                if (empty($this->request->data['Prestador']['tabela']['name'])) {
                    $this->Session->setFlash('Nenhum arquivo selecionado.', 'default', ['class' => 'warning-message'], 'error');
                    return $this->redirect(['action' => 'index']);
                }

                $file = $this->request->data['Prestador']['tabela'];

                $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($extensao, ['xls', 'xlsx'])) {
                    throw new Exception('Formato inválido. Use apenas XLS ou XLSX.');
                }

                if ($file['size'] > $TAMANHO_MAXIMO) {
                    throw new Exception('Arquivo muito grande. Tamanho máximo: 25MB.');
                }


                $spreadsheet = IOFactory::load($file["tmp_name"]);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
                array_shift($rows); // Remove o cabeçalho

                if (empty($rows)) {
                    throw new Exception('A planilha está vazia.');
                }

                if (count($rows[0]) != 5) {
                    throw new Exception('Formato inválido. Verifique o cabeçalho da planilha.');
                }

                $importados = 0;
                $erros = [];

                $this->dataSource = $this->Prestador->getDataSource();
                $this->dataSource->begin();

                foreach ($rows as $index => $row) {
                    $linha = $index + 2; // Considera o cabeçalho

                    $nome = trim($row[0]);
                    $sobrenome = trim($row[1]);
                    $email = trim($row[2]);
                    $telefone = trim($row[3]);
                    $serviços = array_map('trim', explode(', ', $row[4]));
                    $servico_ids = [];

                    foreach ($serviços as $servico_nome) {
                        $servico_id = array_search(strtolower($servico_nome), $servicos_db);
                        if ($servico_id !== false) {
                            $servico_ids[] = $servico_id;
                        } else {
                            $erros[] = "Linha " . ($linha) . ": Serviço '$servico_nome' não encontrado.";
                        }
                    }

                    $prestadorData = [
                        "Prestador" => [
                            'nome' => $nome,
                            'sobrenome' => $sobrenome,
                            'email' => $email,
                            'telefone' => $telefone,
                        ],
                        "Servico" => [
                            'Servico' => ($servico_ids)
                        ]
                    ];

                    $this->Prestador->create();
                    $this->Prestador->set($prestadorData);

                    if (!$this->Prestador->validates()) {
                        foreach ($this->Prestador->validationErrors as $campo => $mensagens) {
                            foreach ($mensagens as $mensagem) {
                                $erros[] = "Linha {$linha}: {$mensagem}";
                            }
                        }
                        continue;
                    }

                    if ($this->Prestador->save($prestadorData, ['validate' => false])) {
                        $importados++;
                    } else {
                        $erros[] = "Linha {$linha}: Erro ao salvar no banco de dados.";
                    }
                }

                // Commit ou rollback
                if (empty($erros)) {
                    $this->dataSource->commit();
                    $this->Flash->modalSuccess('Lista enviada com sucesso!', array(
                        'key' => 'import-success',
                        'params' => array(
                            'detalhes' => 'Confira seus servidores na tabela',
                        )
                    ));
                } else {
                    $this->dataSource->rollback();
                    $mensagemErro = "Importação cancelada. Erros encontrados:<br>" . implode('<br>', $erros);
                    $this->Session->setFlash($mensagemErro, 'default', ['class' => 'warning-message'], 'error');
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                'Erro ao importar: ' . $e->getMessage(),
                'default',
                ['class' => 'error-message'],
                'error'
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    private function _uploadFoto($foto)
    {
        $RESOLUCAO_MAXIMA = [1200, 800];
        $UPLOAD_DIR = WWW_ROOT . 'img' . DS . 'perfil' . DS;
        $EXTENSOES_PERMITIDAS = ['jpg', 'jpeg', 'png', 'gif'];

        if (!is_dir($UPLOAD_DIR)) {
            mkdir($UPLOAD_DIR, 0755, true);
        }

        if (!is_writable($UPLOAD_DIR)) {
            throw new Exception("Diretório sem permissão de escrita.");
        }

        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $resolucao = getimagesize($foto['tmp_name']);

        if (!in_array(strtolower($extensao), $EXTENSOES_PERMITIDAS)) {
            throw new Exception("Tipo de arquivo não permitido.");
        }

        if ($resolucao[0] > $RESOLUCAO_MAXIMA[0] || $resolucao[1] > $RESOLUCAO_MAXIMA[1]) {
            throw new Exception("Resolução máxima permitida é " . $RESOLUCAO_MAXIMA[0] . "x" . $RESOLUCAO_MAXIMA[1] . " pixels.");
        }

        $nome = time() . '_' . md5(uniqid()) . '.' . $extensao;
        $destino = $UPLOAD_DIR . $nome;

        if (!move_uploaded_file($foto['tmp_name'], $destino)) {
            throw new Exception("Erro ao fazer upload da foto.");
        }

        return $nome;
    }
}
