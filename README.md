# Sistema de Gerenciamento de Prestadores de Serviços

Esse sistema tem como objetivo oferecer de forma simples o gerenciamento de prestadores de serviços e serviços prestados. Foi feito como parte do desafio para a vaga de desenvolvedor *Fullstack Junior* na **Doity**, empresa focada em soluções para gestão de eventos.

## Sumário

- [Tecnologias](#tecnologias)
- [Instalação](#instalação)
  - [Requisitos](#requisitos)
  - [Processo](#processo)
  - [Configurações](#configurações)
  - [Outras Configurações Possíveis](#outras-configurações-possíveis)
- [Funcionalidades](#funcionalidades)
  - [Listagem de Prestadores](#listagem-de-prestadores)
  - [Cadastro de Prestadores](#cadastro-de-prestadores)
  - [Edição de Prestadores](#edição-de-prestadores)
  - [Exclusão de Prestadores](#exclusão-de-prestadores)
  - [Importação via Planilha](#importação-via-planilha)
  - [Estrutura da Planilha](#estrutura-da-planilha)
  - [Validações na Importação](#validações-na-importação)
  - [Cadastro de Serviços](#cadastro-de-serviços)
  - [Seleção Múltipla de Serviços](#seleção-múltipla-de-serviços)
- [Notas](#notas)
  - [Sobre o fork do CakePHP 2](#sobre-o-fork-do-cakephp-2)
  - [Sobre o Phinx](#sobre-o-phinx)

## Tecnologias

- PHP 8.3
- [CakePHP 2 ( Fork para suporte à versão 8 do PHP)](https://github.com/kamilwylegala/cakephp2-php8)
- Banco de dados MySQL 8
- Phinx (Para migrações no banco de dados)
- Frontend dinâmico e intuitivo (HTML5, CSS3, JavaScript, jQuery)
- Orquestração de Containers (Docker Compose)

## Instalação

### Requisitos
- Git
- Docker e Docker Compose

### Processo

**1. Clone o repositório**
```sh
git clone https://github.com/albertojbe/teste-fullstack.git
cd teste-fullstack
```
**2. Execute o container**
```sh
docker compose up -d --build
```

**3. Instale as dependências e execute as migrações**
```sh
docker container exec -it desafio-doity composer install --ignore-platform-req=ext-intl
docker container exec -it desafio-doity ./vendor/bin/phinx migrate -e development
```

> Acesse em `localhost:8080`.

### Configurações
O sistema permite personalizações através de arquivos de configuração. Abaixo estão as principais alterações possíveis:

#### Configuração do Banco de Dados
1. Alterar Variáveis de Ambiente no Docker Compose \
Edite o arquivo `docker-compose.yml` para modificar as credenciais e configurações do banco de dados:

```yml
db:
  image: mysql:latest
  environment:
    MYSQL_DATABASE: seu_banco     # Nome do banco de dados
    MYSQL_USER: seu_usuario       # Usuário do banco
    MYSQL_PASSWORD: sua_senha     # Senha do usuário
    MYSQL_ROOT_PASSWORD: senha_root # Senha do root
  ports:
    - "3306:3306"                 # Porta exposta (host:container)
```

2. Atualizar Configuração do CakePHP 2 \
Após alterar as variáveis no Docker Compose, edite o arquivo `app/Config/database.php`:

```php
<?php
public $default = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => 'db',                    // Nome do serviço no docker-compose.yml
    'login' => 'seu_usuario',          // Mesmo valor de MYSQL_USER
    'password' => 'sua_senha',         // Mesmo valor de MYSQL_PASSWORD
    'database' => 'seu_banco',         // Mesmo valor de MYSQL_DATABASE
    'prefix' => '',
    'encoding' => 'utf8',
);
```

3. Atualizar Configuração do Phinx \
Edite o arquivo `phinx.php` para refletir as mesmas alterações:

```php
<?php
'development' => [
    'adapter' => 'mysql',
    'host' => 'db',                    // Nome do serviço no docker-compose.yml
    'name' => 'seu_banco',             // Mesmo valor de MYSQL_DATABASE
    'user' => 'seu_usuario',           // Mesmo valor de MYSQL_USER
    'pass' => 'sua_senha',             // Mesmo valor de MYSQL_PASSWORD
    'port' => '3306',
    'charset' => 'utf8',
],
```

4. Aplicar as Alterações \
Após modificar as configurações:

```sh
# Reconstrua os containers
docker compose down
docker compose up -d --build

# Execute as migrações novamente
docker container exec -it desafio-doity ./vendor/bin/phinx migrate -e development
```

### Outras Configurações Possíveis

**Alterar Porta do Servidor Web**
\
No docker-compose.yml, modifique o mapeamento de portas do serviço PHP:
```yml
ports:
  - "8080:80"  # Altere para a porta desejada
```

**Alterar Porta do MySQL**
\
No docker-compose.yml, modifique o mapeamento de portas do serviço de banco de dados:

```yml
ports:
  - "3306:3306"  # Altere para a porta desejada
```

> **Importante:** Mantenha sempre a consistência entre as três configurações (Docker Compose, CakePHP e Phinx) para garantir o funcionamento correto do sistema.

## Funcionalidades

### Listagem de Prestadores
- Visualização em tabela com informações organizadas
- Paginação automática (5 registros por página)
- Exibição de foto de perfil, nome completo, email e telefone
- Visualização dos serviços associados a cada prestador
- Sistema de busca e filtros

### Cadastro de Prestadores
- Formulário intuitivo dividido em seções
- Campos obrigatórios:
  - Nome e sobrenome
  - Email (com validação e verificação de duplicidade)
  - Telefone (com máscara automática)
  - Associação de múltiplos serviços
- Upload de foto de perfil:
  - Suporte a SVG, PNG, JPG e GIF
  - Limite de resolução: 1200x800 pixels
  - Preview em tempo real
  - Drag & drop para facilitar o upload
- Validações em tempo real

### Edição de Prestadores
- Interface idêntica ao cadastro para consistência
- Pré-carregamento dos dados existentes
- Atualização de foto (mantém a anterior se não houver nova)
- Preservação dos serviços já associados
- Validação de alterações

### Exclusão de Prestadores
- Confirmação de exclusão para evitar remoções acidentais
- Remoção automática da foto do servidor
- Desassociação de todos os serviços vinculados

### Importação via Planilha
- Suporte a múltiplos formatos:
  - Excel (.xls, .xlsx)
- Modal interativo com:
  - Área de drag & drop
  - Seletor de arquivo tradicional
  - Preview do arquivo selecionado
  - Barra de progresso durante upload
  - Indicador de conclusão
- Limite de tamanho: 25MB
- Processamento em lote com transações

### Estrutura da Planilha
A planilha deve conter as seguintes colunas na ordem:

| Coluna | Descrição | Obrigatório |
|--------|-----------|-------------|
| Nome | Primeiro nome do prestador | Sim |
| Sobrenome | Sobrenome do prestador | Sim |
| Email | Email válido e único | Sim |
| Telefone | Telefone com DDD | Sim |
| Serviços | Nomes dos serviços separados por vírgula ", " | Não |

### Validações na Importação
- Verificação de formato de arquivo
- Validação de tamanho
- Detecção de estrutura (5 colunas obrigatórias)
- Validação individual de cada registro:
  - Email único e válido
  - Telefone no formato correto
  - Serviços existentes no sistema
- Sistema de rollback em caso de erro
- Relatório detalhado de erros por linha

### Cadastro de Serviços
- Modal rápido para cadastro durante registro de prestadores
- Campos:
  - Nome do serviço
  - Descrição detalhada
  - Valor (com formatação de moeda BRL)
- Validações automáticas
- Cadastro sem sair do formulário principal

### Seleção Múltipla de Serviços
- Interface customizada com:
  - Dropdown estilizado
  - Busca integrada para filtrar serviços
  - Checkboxes visuais
  - Contador de seleções
  - Indicadores visuais de itens selecionados
- Permite associar múltiplos serviços simultaneamente

## Notas

### Sobre o fork do CakePHP 2

Optei por usar um fork do CakePHP 2 para manter compatibilidade com PHP 8. Tomei esta decisição levando em consideraçã o que CakePHP 2 oficial não recebe mais atualizações. A utilização deste fork permite aproveitar o Composer e libs modernas; trabalhar com bancos de dados e infraestruturas atuais, e ainda receber correções pontuais de compatibilidade; assim fico com a estabilidade de um framework maduro e, ao mesmo tempo, com uma stack moderna, sem ficar preso a uma versão descontinuada que não suporta PHP 8.

### Sobre o Phinx

O Phinx é uma ferramenta para gerenciamento de migrações muito usada em projetos PHP. Optei por ele para, pensando na escalabilidade do projeto, poder garantir o versionamento do banco de dados sem depender do framework, além de poder gerenciar melhor bancos de dados com propósitos diferentes como: deploy, development e test. 

Levando em consideração os requisitos do desafio, também criei o script SQL para gerar o banco de dados e o deixei em `databases/schema.sql`.