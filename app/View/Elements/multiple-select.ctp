<select multiple name="<?= isset($name) ? h($name) : '' ?>" id="<?= isset($idInput) ? h($idInput) : 'input' ?>" style="display: none;">
    <?php foreach ($options as $id => $option): ?>
        <option value="<?= h($id) ?>"><?= h($option) ?></option>
    <?php endforeach; ?>
</select>

<div class="custom-select">
    <div class="selected">
        <span class="selected-text"><?= isset($placeholder) ? h($placeholder) : 'Escolha uma opção' ?></span>
        <span class="material-icons-outlined">keyboard_arrow_down</span>
    </div>
    <div class="options">
        <?php foreach ($options as $id => $option): ?>
            <div class="option" data-value="<?= h($id) ?>" data-label="<?= h($option) ?>">
                <span><?= h($option) ?></span>
                <span class="material-icons-outlined">check_small</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .custom-select {
        position: relative;
        width: 100%;
    }

    .selected {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        background: var(--bg-content);
        cursor: pointer;
        border-radius: var(--border-radius-sm);
        color: var(--text-secondary);
        transition: all var(--transition-base);
    }

    .selected:hover {
        border-color: var(--brand-primary);
    }

    .selected.active {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px var(--brand-primary-light);
    }

    .selected-text {
        flex: 1;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .selected span.material-icons-outlined {
        transition: transform var(--transition-base);
    }

    .selected.active span.material-icons-outlined {
        transform: rotate(180deg);
    }

    .options {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        border: 1px solid var(--border-color);
        background: var(--bg-content);
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
    }

    .options.active {
        display: block;
    }

    .option {
        display: grid;
        color: var(--text-secondary);
        grid-template-columns: 1fr 20px;
        padding: 10px 12px;
        cursor: pointer;
        transition: all var(--transition-base);
    }

    .option:hover {
        background-color: var(--bg-hover);
    }

    .option.selected {
        background-color: var(--brand-primary-light);
        color: var(--text-primary);
    }

    .option span:first-child {
        font-size: 0.875rem;
    }

    .option span.material-icons-outlined {
        visibility: hidden;
        font-size: 1.25rem;
        justify-content: end;
    }

    .option.selected span.material-icons-outlined {
        visibility: visible;
        color: var(--text-secondary);
    }
</style>

<script>
    $(document).ready(function() {
        const $select = $('select[name="<?= isset($name) ? h($name) : '' ?>"]');
        const $customSelect = $select.next('.custom-select');
        const $selectedDiv = $customSelect.find('.selected');
        const $selectedText = $customSelect.find('.selected-text');
        const $optionsDiv = $customSelect.find('.options');
        const $options = $customSelect.find('.option');
        const placeholderText = '<?= isset($placeholder) ? h($placeholder) : 'Escolha uma opção' ?>';

        // Função para atualizar o texto e o select
        function updateSelected() {
            const selectedValues = $select.val() || [];
            const selectedCount = selectedValues.length;

            // Atualizar texto
            if (selectedCount === 0) {
                $selectedText.text(placeholderText);
            } else {
                $selectedText.text(selectedCount + ' Selecionado' + (selectedCount > 1 ? 's' : ''));
            }

            // Atualizar visual das opções
            $options.each(function() {
                const value = $(this).data('value').toString();
                if (selectedValues.includes(value)) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });
        }

        // Abrir/Fechar dropdown
        $selectedDiv.on('click', function() {
            $optionsDiv.toggleClass('active');
            $selectedDiv.toggleClass('active');
        });

        // Selecionar/Desselecionar opção
        $options.on('click', function() {
            const value = $(this).data('value').toString();
            const currentValues = $select.val() || [];

            if (currentValues.includes(value)) {
                // Remover
                const index = currentValues.indexOf(value);
                currentValues.splice(index, 1);
            } else {
                // Adicionar
                currentValues.push(value);
            }

            // Atualizar select
            $select.val(currentValues);
            
            // Atualizar visual
            updateSelected();
        });

        // Fechar dropdown ao clicar fora
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $optionsDiv.removeClass('active');
                $selectedDiv.removeClass('active');
            }
        });

        // Inicializar visual
        updateSelected();
    });
</script>