<?php
$nome = isset($nome) ? h($nome) : 'U';
$sobrenome = isset($sobrenome) ? h($sobrenome) : '';
$foto = isset($foto) ? $foto : '';
$tamanho = isset($tamanho) ? $tamanho : 'md'; // xs, sm, md, lg

// Gerar iniciais
$iniciais = substr($nome, 0, 1) . substr($sobrenome, 0, 1);
$iniciais = strtoupper($iniciais);

// Definir tamanhos
$tamanhos = [
    'xs' => ['container' => '32px', 'font' => '0.75rem'],
    'sm' => ['container' => '40px', 'font' => '0.85rem'],
    'md' => ['container' => '48px', 'font' => '1rem'],
    'lg' => ['container' => '64px', 'font' => '1.25rem'],
];

$config = $tamanhos[$tamanho] ?? $tamanhos['md'];
?>

<div class="avatar" style="
    width: <?= $config['container']; ?>;
    height: <?= $config['container']; ?>;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: <?= $config['font']; ?>;
    overflow: hidden;
    flex-shrink: 0;
">
    <?php if ($foto): ?>
        <img 
            src="<?= $this->Html->url('/img/perfil/' . h($foto)); ?>" 
            alt="<?= $iniciais; ?>"
            style="width: 100%; height: 100%; object-fit: cover;"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        <div class="avatar-fallback" style="
            width: 100%;
            height: 100%;
            background: var(--bg-surface-2);
            color: var(--brand-primary);
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: normal;
        ">
            <?= $iniciais; ?>
        </div>
    <?php else: ?>
        <div class="avatar-fallback" style="
            width: 100%;
            height: 100%;
            background: var(--bg-surface-2);
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        ">
            <?= $iniciais; ?>
        </div>
    <?php endif; ?>
</div>