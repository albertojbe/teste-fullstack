<!-- Modal para importar prestadores por XLSX, XLS ou CSV -->
<?= $this->Html->css('modal') ?>

<div class="modal">
    <div class="modal-content">
        <h2>Faça upload da sua lista de servidores</h2>
        <?= $this->Form->create('Prestador', ['url' => ['controller' => 'Prestadores', 'action' => 'importar'], 'type' => 'file']); ?>
        <div class="modal-input-group">
            <label for="PrestadorTabela" class="input-file-photo">
                <span
                    style="color: var(--text-secondary); font-size: 30px; margin-bottom: var(--spacing-sm); border: solid 10px var(--bg-body); border-radius: 100%;"
                    class="material-icons-outlined">cloud_upload</span>
                <div>
                    <span style="font-weight: bold; color: var(--text-primary);">Clique aqui para enviar</span> ou
                    arraste e
                    solte
                    <p>XLS, XLSX (máx. 25MB)</p>
                </div>
            </label>
            <?= $this->Form->input('tabela', [
                'type' => 'file',
                'label' => false,
                'div' => false,
                'id' => 'PrestadorTabela',
                'accept' => '.xls,.xlsx'
            ]); ?>
        </div>

        <!-- Container com informações do arquivo -->
        <div class="file-info-container" id="fileInfo" style="display: none;">
            <div class="file-info-item">
                <span class="file-icon material-icons-outlined">description</span>
                <div class="file-details">
                    <span class="file-name" id="fileName">-</span>
                    <span class="file-size" id="fileSize">-</span>
                </div>
                <span class="file-check material-icons-outlined" id="fileCheck" style="display: none;">check</span>
            </div>

            <!-- Barra de progresso -->
            <div class="progress-container" id="progressContainer" style="display: none;">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>
                <span class="progress-text" id="progressText">0%</span>
            </div>
        </div>

        <div class="modal-actions">
            <?= $this->Html->link('Cancelar', '#', ['class' => 'btn btn-secondary close-modal']); ?>
            <?= $this->Form->end(['label' => 'Adicionar', 'class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>

<style>
    .file-info-container {
        margin: var(--spacing-md) 0;
        padding: var(--spacing-md);
        background: var(--bg-surface-0);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .file-info-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        width: 100%;
        position: relative;
    }

    .file-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--brand-primary-light);
        color: var(--brand-primary);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .file-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .file-name {
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--text-primary);
        word-break: break-word;
    }

    .file-size {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .file-check {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 28px;
        height: 28px;
        background: var(--brand-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        animation: scaleIn 0.3s ease;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .progress-container {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        width: 100%;
    }

    .progress-bar {
        flex: 1;
        height: 6px;
        background: var(--bg-surface-2);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--brand-primary);
        border-radius: 3px;
        transition: width 0.15s ease;
    }

    .progress-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--brand-primary);
        min-width: 30px;
        text-align: right;
    }
</style>

<script>
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

        // Exibir informações do arquivo e simular progresso
        $('#PrestadorTabela').on('change', function (e) {
            const file = e.target.files[0];
            const $fileInfo = $('#fileInfo');
            const $fileName = $('#fileName');
            const $fileSize = $('#fileSize');
            const $progressContainer = $('#progressContainer');
            const $progressFill = $('#progressFill');
            const $progressText = $('#progressText');
            const $fileCheck = $('#fileCheck');

            if (file) {
                $fileName.text(file.name);
                $fileSize.text('Tamanho: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
                $fileInfo.show();
                $fileCheck.hide();
                
                // Mostrar barra de progresso e simular upload
                $progressContainer.show();
                simulateUpload($progressFill, $progressText, $fileCheck);
            } else {
                $fileInfo.hide();
                $progressContainer.hide();
            }
        });

        function simulateUpload($progressFill, $progressText, $fileCheck) {
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 50;
                
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    
                    $progressFill.css('width', progress + '%');
                    $progressText.text(progress + '%');
                    
                    // Mostrar ícone de conclusão após 300ms
                    setTimeout(() => {
                        $fileCheck.show();
                    }, 300);
                } else {
                    $progressFill.css('width', progress + '%');
                    $progressText.text(Math.floor(progress) + '%');
                }
            }, 200);
        }
    });

    // Drag and drop para o upload da foto
    $(document).ready(function () {
        var dropArea = $('#PrestadorTabela').parent();

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
            $('#PrestadorTabela')[0].files = files;
            $('#PrestadorTabela').trigger('change');
        });
    });
</script>