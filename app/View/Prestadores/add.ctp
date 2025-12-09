<title>Editar Prestador</title>

<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">

<header>
    <h2>Cadastro de Prestador de Serviço</h2>
</header>

<div style="padding-bottom: 20px; border-bottom: 1px solid var(--border-color);" class="mb-4">
    <h4>Informações Pessoais</h4>
    <p class="subtitle">Cadastre suas informações e adicione uma foto</p>
</div>

<?= $this->Form->create('Prestador', ['type' => 'file']);
?>

<div class="form-group">
    <label for="nome">Nome</label>
    <div class="form-control">
        <?= $this->Form->input('nome', [
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => 'Nome',
            'required' => true,
            'maxlength' => 255
        ]); ?>
        <?= $this->Form->input('sobrenome', [
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => 'Sobrenome',
            'required' => true,
            'maxlength' => 255
        ]); ?>
    </div>
</div>

<div class="form-group">
    <label for="email">Email</label>
    <div class="form-control single">
        <div class="input-icon-wrapper">
            <span class="input-icon"><span class="material-icons-outlined">mail</span></span>
            <?= $this->Form->input('email', [
                'label' => false,
                'div' => false,
                'class' => 'form-control with-icon',
                'placeholder' => 'Email',
                'required' => true,
                'maxlength' => 255
            ]); ?>
        </div>
    </div>
</div>


<div class="form-group">
    <div>
        <label>Sua foto</label>
        <p class="subtitle">Ela aparecerá no seu perfil</p>
    </div>
    <div class="form-control photo">
        <img id="preview-foto"
            src="<?= $this->Html->url('https://www.shutterstock.com/image-vector/avatar-gender-neutral-silhouette-vector-600nw-2470054311.jpg'); ?>"
            alt="Preview da Foto"
            style="aspect-ratio: 1 / 1; width: 100px; background-color: var(--surface-0); border-radius: 50%;" />
        <label for="PrestadorFoto" class="input-file-photo">
            <span
                style="color: var(--text-secondary); font-size: 30px; margin-bottom: var(--spacing-sm); border: solid 10px var(--bg-body); border-radius: 100%;"
                class="material-icons-outlined">cloud_upload</span>
            <div>
                <span style="font-weight: bold; color: var(--text-primary);">Clique aqui para enviar</span> ou arraste e
                solte
                <p>SVG, PNG, JPG ou GIF (máx. 1200x800px)</p>
            </div>
        </label>
        <?= $this->Form->input('foto', [
            'type' => 'file',
            'label' => false,
            'div' => false,
            'accept' => 'image/*',
            'id' => 'PrestadorFoto'
        ]); ?>
    </div>
</div>

<div class="form-group">
    <label for="telefone">Telefone</label>
    <?= $this->Form->input('telefone', [
        'label' => false,
        'div' => false,
        'class' => 'form-control single',
        'placeholder' => '(00) 00000-0000',
        'required' => true,
        'maxlength' => 20
    ]); ?>
</div>

<div class="form-group">
    <label>Serviços</label>
    <div class="form-control single">
        <?= $this->element(
            'multiple-select',
            [
                'options' => $servicos,
                'idInput' => '#servicos-input',
                'placeholder' => 'Selecione o serviço',
                'name' => 'data[Servico][Servico][]'
            ]
        );
        ?>
    </div>
    <button class="btn btn-primary open-modal"><span class="material-icons-outlined">add</span> Cadastrar
        serviço</button>
</div>

<div class="mensagens">
    <?= $this->Session->flash('success') ?>
    <?= $this->Session->flash('error') ?>
</div>

<div class="actions" style="margin-top:20px; display: flex; justify-content: end;">
    <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'btn btn-secondary']); ?>
    <?= $this->Form->end(['label' => 'Salvar', 'class' => 'btn btn-primary']); ?>
</div>

<?= $this->element('create-service-modal'); ?>

<script>
    // Mascara para o campo de telefone
    $(document).ready(function () {
        var maskBehavior = function (val) {
            var digits = val.replace(/\D/g, '');
            return (digits.length === 11) ? '(00) 00000-0000' : '(00) 0000-00009';
        };

        var options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(maskBehavior.apply({}, arguments), options);
            },
            clearIfNotMatch: false
        };

        $('input[name="data[Prestador][telefone]"]').mask(maskBehavior, options);
    });

    // Preview da foto ao adicionar arquivo
    $(document).ready(function () {
        $('#PrestadorFoto').change(function (event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview-foto').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    });

    // Drag and drop para o upload da foto
    $(document).ready(function () {
        var dropArea = $('.form-control.photo');

        dropArea.on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        dropArea.on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        dropArea.on('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');

            var files = e.originalEvent.dataTransfer.files;
            $('#PrestadorFoto')[0].files = files;
            $('#PrestadorFoto').trigger('change');
        });
    });
</script>