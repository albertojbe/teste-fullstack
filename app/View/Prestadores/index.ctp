<header>
    <div>
        <h1>Prestadores de Serviço</h1>
        <div class="subtitle">Veja sua lista de prestadores de serviço</div>
    </div>
    <div class="actions">
        <button class="btn btn-secondary open-modal"><span class="material-icons-outlined">file_upload</span> Importar </button>
        <?= $this->Html->link(
            '<span class="material-icons-outlined">add</span> Adicionar Prestador',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ); ?>
    </div>
</header>

<div class="search-container">
    <div class="search-box">
        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" id="filtro-prestadores" name="q" placeholder="Buscar">
    </div>
</div>

<table class="table table-bordered table-striped" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Prestador</th>
            <th>Telefone</th>
            <th>Serviços</th>
            <th width="160">Valor</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prestadores as $p): ?>
            <tr>
                <td>
                    <div class="media">
                        <?php if (!empty($p['Prestador']['foto'])): ?>
                            <?= $this->Html->image(
                                'perfil/' . h($p['Prestador']['foto']),
                                ['class' => 'avatar', 'alt' => h($p['Prestador']['nome'])]
                            ); ?>
                        <?php else: ?>
                            <span class="avatar avatar-placeholder">?</span>
                        <?php endif; ?>
                        <div class="media-body">
                            <strong><?= h($p['Prestador']['nome']); ?></strong>
                            <div class="weight-sm"><?= h($p['Prestador']['email']); ?></div>
                        </div>
                    </div>
                </td>
                <td><?= h($p['Prestador']['telefone']); ?></td>

                <td>
                    <?php
                    if (!empty($p['Servico'])) {
                        $nomes = Hash::extract($p['Servico'], '{n}.nome');
                        echo implode(', ', $nomes);
                    } else {
                        echo "<i>Nenhum</i>";
                    }
                    ?>
                </td>

                <td>
                    <?php
                    $valorTotal = 0;
                    if (!empty($p['Servico'])) {
                        foreach ($p['Servico'] as $servico) {
                            $valorTotal += !empty($servico['valor']) ? $servico['valor'] : 0;
                        }
                        echo 'R$ ' . number_format($valorTotal, 2, ',', '.');
                    } else {
                        echo '-';
                    }
                    ?>
                </td>

                <td>
                    <?= $this->Html->link('<span class="material-icons-outlined">border_color</span>', ['action' => 'edit', $p['Prestador']['id']], ['class' => 'btn-icon', 'escape' => false]); ?>
                    <?= $this->Form->postLink('<span class="material-icons-outlined">delete</span>', ['action' => 'delete', $p['Prestador']['id']], [
                        'class' => 'btn-icon',
                        'escape' => false,
                        'confirm' => 'Tem certeza que deseja excluir este prestador?'
                    ]); ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
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
    <?= $this->Session->flash('warning'); ?>
</div>

<?= $this->element('importar-prestadores-modal'); ?>

<script>
    $(document).ready(function () {
        $('#filtro-prestadores').on('keyup', function () {
            var valorBusca = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(valorBusca) > -1)
            });
        });
    });
</script>