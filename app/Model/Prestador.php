<?php

class Prestador extends AppModel
{
    public $useTable = 'prestadores';

    public $validate = [

        'nome' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'O nome é obrigatório.'
            ],
            'alpha' => [
                'rule' => '/^[A-Za-zÀ-ÿ\s]+$/u',
                'message' => 'O nome só pode conter letras.'
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'Já existe um prestador com esse nome.'
            ],
            'maxLength' => [
                'rule' => ['maxLength', 100],
                'message' => 'O nome pode ter no máximo 100 caracteres.'
            ]
        ],

        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'O email é obrigatório.'
            ],
            'email' => [
                'rule' => 'email',
                'message' => 'Insira um endereço de email válido.'
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'Este email já está cadastrado.'
            ]
        ],

        'telefone' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'O telefone não pode ficar em branco.'
            ],
            'regex' => [
                'rule' => '/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/',
                'message' => 'Informe um telefone válido.'
            ]
        ],

        'servicos' => [
            'multiple' => [
                'rule' => ['multiple', ['min' => 1]],
                'message' => 'Selecione pelo menos um serviço.'
            ]
        ]
    ];

    public $hasAndBelongsToMany = [
        'Servico' => [
            'className' => 'Servico',
            'joinTable' => 'prestadores_servicos',
            'foreignKey' => 'prestador_id',
            'associationForeignKey' => 'servico_id'
        ]
    ];
}
