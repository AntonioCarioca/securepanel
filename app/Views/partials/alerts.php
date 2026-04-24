<?php if ($message = $this->flash('error')): ?>
    <p style="color: #b91c1c; margin-bottom: 16px;">
        <?= $this->e($message) ?>
    </p>
<?php endif; ?>

<?php if ($message = $this->flash('success')): ?>
    <p style="color: #166534; margin-bottom: 16px;">
        <?= $this->e($message) ?>
    </p>
<?php endif; ?>
