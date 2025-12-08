<?= $this->Html->css('modal') ?>

<div class="modal">
    <div class="modal-content">
        <h2>Cadastre um serviço</h2>
        <?= $this->Form->create('Servico', ['url' => ['controller' => 'Servicos', 'action' => 'add'], 'type' => 'file']); ?>

        <div class="modal-input-group">
            <label for="ServicoNome">Nome do Serviço</label>
            <?= $this->Form->control('nome', ['type' => 'text', 'label' => false, 'class' => 'form-control-single', 'div' => false]); ?>
        </div>

        <div class="modal-input-group">
            <label for="ServicoDescricao">Descrição</label>
            <?= $this->Form->control('descricao', ['type' => 'textarea', 'label' => false, 'class' => 'form-control-single', 'div' => false]); ?>
        </div>

        <div class="modal-input-group">
            <label for="ServicoValor">Valor</label>
            <div class="input-money-wrapper">
                <span class="input-money-prefix">R$</span>
                <?= $this->Form->input('valor', [
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'input-money',
                    'placeholder' => '0,00',
                    'required' => true
                ]); ?>
            </div>
        </div>

        <div class="modal-actions">
            <?= $this->Html->link('Cancelar', '#', ['class' => 'btn btn-secondary close-modal']); ?>
            <?= $this->Form->end(['label' => 'Cadastrar', 'class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>

<style>
.input-money-wrapper {
    display: flex;
    align-items: stretch;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    background: var(--bg-content);
}

.input-money-prefix {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
    background: var(--bg-surface-1);
    color: var(--text-secondary);
    border-right: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.input-money {
    flex: 1;
    border: none;
    padding: 10px 12px;
    font-size: 0.95rem;
    background: transparent;
    outline: none;
}

.input-money:focus {
    box-shadow: none;
}
</style>

<script>
$(document).ready(function () {
    const $input = $('#ServicoValor');
    const $form = $input.closest('form');
    if (!$input.length) return;

    const formatBRLPlain = (num) =>
        new Intl.NumberFormat('pt-BR', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(num);

    const toNumber = (str) => {
        const clean = (str || '').replace(/[^\d,-]/g, '').replace(',', '.');
        const n = parseFloat(clean);
        return isNaN(n) ? '' : n;
    };

    $input.on('focus', function (e) {
        const n = toNumber(e.target.value);
        e.target.value = n === '' ? '' : n;
    });

    $input.on('blur', function (e) {
        const n = toNumber(e.target.value);
        e.target.value = n === '' ? '' : formatBRLPlain(n);
    });

    $form.on('submit', function () {
        const n = toNumber($input.val());
        $input.val(n === '' ? '' : n); // envia número puro (ponto decimal)
    });
});

// Abre/fecha modal
$(document).ready(function () {
    $('.close-modal').click(function (e) {
        e.preventDefault();
        $('.modal').removeClass('active');
    });
    $('.open-modal').click(function (e) {
        e.preventDefault();
        $('.modal').addClass('active');
    });
});
</script>