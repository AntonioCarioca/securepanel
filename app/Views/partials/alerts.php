<?php if ($message = getFlash('error')): ?>
    <p style="color: #b91c1c; margin-bottom: 16px;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<?php if ($message = getFlash('success')): ?>
    <p style="color: #166534; margin-bottom: 16px;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>