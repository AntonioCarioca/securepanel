<?php if ($message = getFlash('error')): ?>
    <p style="color: #b91c1c; margin-bottom: 16px;">
        <?= $this->e($message) ?>
    </p>
<?php endif; ?>

<?php if ($message = getflash('success')): ?>
    <p style="color: #166534; margin-bottom: 16px;">
        <?= $this->e($message) ?>
    </p>
<?php endif; ?>