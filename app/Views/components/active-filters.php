<?php if (!empty($filters)): ?>
    <div style="margin-bottom: 16px;">
        <strong>Filtros ativos:</strong>

        <div style="margin-top: 8px;">
            <?php foreach ($filters as $filter): ?>
                <span style="
                    display: inline-block;
                    background: #f1f5f9;
                    border: 1px solid #cbd5e1;
                    border-radius: 999px;
                    padding: 6px 10px;
                    margin: 4px 6px 4px 0;
                    font-size: 14px;
                ">
                    <?= $this->e($filter) ?>
                </span>
            <?php endforeach; ?>

            <?php if (!empty($clearUrl)): ?>
                <a href="<?= $this->e($clearUrl) ?>" style="margin-left: 8px;">Limpar todos</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
