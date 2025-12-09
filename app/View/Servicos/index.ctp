<title>Serviços</title>

<header>
    <div>
        <h1>Serviços</h1>
        <div class="subtitle">Veja sua lista de serviços cadastrados</div>
    </div>
    <div class="actions">
        <?= $this->Html->link(
            '<span class="material-icons-outlined">keyboard_return</span> Voltar',
            ['controller' => 'Prestadores', 'action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ); ?>
        <button class="btn btn-primary open-modal"><span class="material-icons-outlined">add</span> Adicionar
            Serviço</button>
    </div>
</header>

<div class="search-container">
    <div class="search-box">
        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" id="filtro-servicos" name="q" placeholder="Buscar">
    </div>
</div>

<table class="table table-bordered table-striped" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Serviço</th>
            <th>Valor</th>
            <th>Prestadores</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($servicos)): ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                    <span class="material-icons-outlined"
                        style="font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.3;">
                        work_outline
                    </span>
                    Nenhum serviço cadastrado ainda
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td>
                        <strong style="color: var(--text-primary);"><?= h($servico['Servico']['nome']); ?></strong>
                        <?php if (!empty($servico['Servico']['descricao'])): ?>
                            <div class="weight-sm" style="color: var(--text-secondary);">
                                <?= h(mb_substr($servico['Servico']['descricao'], 0, 80)); ?>
                                <?= mb_strlen($servico['Servico']['descricao']) > 80 ? '...' : ''; ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        R$ <?= number_format($servico['Servico']['valor'], 2, ',', '.'); ?>
                    </td>

                    <td>
                        <?php if (!empty($servico['Prestador'])): ?>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <?php
                                $total = count($servico['Prestador']);
                                $mostrar = array_slice($servico['Prestador'], 0, 5);
                                ?>
                                <?php foreach ($mostrar as $prestador): ?>
                                    <div title="<?= h($prestador['nome'] . ' ' . $prestador['sobrenome']); ?>">
                                        <?= $this->element('avatar', [
                                            'nome' => $prestador['nome'],
                                            'sobrenome' => $prestador['sobrenome'],
                                            'foto' => $prestador['foto'],
                                            'tamanho' => 'sm'
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($total > 5): ?>
                                    <div style="
                                        width: 40px;
                                        height: 40px;
                                        border-radius: 50%;
                                        background: var(--bg-surface-1);
                                        color: var(--text-secondary);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 0.875rem;
                                        font-weight: 600;
                                    " title="<?= ($total - 5); ?> prestadores a mais">
                                        +<?= ($total - 5); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <i style="color: var(--text-muted);">Nenhum</i>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?= $this->Form->postLink(
                            '<span class="material-icons-outlined">delete</span>',
                            ['action' => 'delete', $servico['Servico']['id']],
                            [
                                'class' => 'btn-icon',
                                'escape' => false,
                                'confirm' => 'Tem certeza que deseja excluir este serviço?'
                            ]
                        ); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="table-pagination">
                    <div class="pagination-info">
                        <?= $this->Paginator->counter(['format' => 'Página {:page} de {:pages}']); ?>
                    </div>
                    <div class="pagination-links">
                        <?= $this->Paginator->prev('Anterior', ['class' => 'page-link'], null, ['class' => 'page-link disabled']); ?>
                        <?= $this->Paginator->next('Próximo', ['class' => 'page-link'], null, ['class' => 'page-link disabled']); ?>
                    </div>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

<div class="messages">
    <?= $this->Session->flash('success'); ?>
    <?= $this->Session->flash('error'); ?>
    <?= $this->Session->flash('warning'); ?>
    <?= $this->Session->flash('info'); ?>
</div>

<?= $this->element('create-service-modal'); ?>

<script>
    // Busca em tempo real
    $(document).ready(function () {
        $('#filtro-servicos').on('keyup', function () {
            var valorBusca = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(valorBusca) > -1)
            });
        });
    });

    // Abre/fecha modal
    $(document).ready(function () {
        $('.open-modal').on('click', function (e) {
            e.preventDefault();
            $('.modal').addClass('active');
        });
        $(document).on('click', '.close-modal', function (e) {
            e.preventDefault();
            $('.modal').removeClass('active');
        });
    });
</script>
