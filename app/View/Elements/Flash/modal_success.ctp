<?= $this->Html->css('modal') ?>

<div id="flash-<?php echo h($key) ?>" class="modal">
    <div class="modal-content">
        <div class="success-header">
            <span class="material-icons-outlined">task_alt</span>
            <h2><?= h($message) ?></h2>
        </div>
        <div class="modal-body">
            <p><?= h($params['detalhes']) ?></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success">Certo</button>
        </div>
    </div>
</div>

<style>
    .success-header {
        color: var(--success);
        text-align: center;
    }

    div .success-header .material-icons-outlined {
        font-size: 2rem;
        color: var(--success);
        margin-bottom: 1rem;
        border-radius: 50%;
        padding: 8px;
        border: 10px solid var(--success-bg);
        background-color: var(--success-border);
    }

    .modal-body {
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: var(--spacing-md);
    }

    .modal-footer {
        display: flex;
        justify-content: center;
        padding: 1rem;
    }
</style>

<script>

    $(document).ready(function() {
        setTimeout(function() {
            const modal = document.querySelector('.modal');
            modal.classList.add('active');
        }, 100);
    });

    function closeModal() {
        const modal = document.querySelector('.modal');
        modal.classList.remove('active');
    }

    document.querySelector('.modal-footer').addEventListener('click', closeModal);
</script>