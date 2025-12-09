<?php
class Servico extends AppModel
{
    public $useTable = 'servicos';

    public $validate = [

        'nome' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'O nome do serviço é obrigatório.'
            ],
            'minLength' => [
                'rule' => ['minLength', 3],
                'message' => 'O nome deve ter pelo menos 3 caracteres.'
            ],
            'maxLength' => [
                'rule' => ['maxLength', 100],
                'message' => 'O nome pode ter no máximo 100 caracteres.'
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'Já existe um serviço com esse nome.'
            ]
        ],

        'descricao' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'A descrição é obrigatória.'
            ],
            'minLength' => [
                'rule' => ['minLength', 10],
                'message' => 'A descrição deve ter pelo menos 10 caracteres.'
            ]
        ],

        'valor' => [
            'numeric' => [
                'rule' => 'numeric',
                'message' => 'O valor deve ser um número válido.'
            ],
            'positive' => [
                'rule' => ['comparison', '>=', 0],
                'message' => 'O valor não pode ser negativo.'
            ]
        ]
    ];

    public $hasAndBelongsToMany = [
        'Prestador' => [
            'className' => 'Prestador',
            'joinTable' => 'prestadores_servicos',
            'foreignKey' => 'servico_id',
            'associationForeignKey' => 'prestador_id'
        ]
    ];

    public function beforeSave($options = [])
    {
        if (isset($this->data['Servico']['nome'])) {
            $this->data['Servico']['nome'] = trim($this->data['Servico']['nome']);
        }

        if (isset($this->data['Servico']['descricao'])) {
            $this->data['Servico']['descricao'] = trim($this->data['Servico']['descricao']);
        }

        return true;
    }
}
