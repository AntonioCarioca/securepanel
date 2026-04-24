<form action="<?= $this->e($form['action']) ?>" method="POST">
    <?php $this->insert('components/csrf') ?>

    <div>
        <label for="name">Nome</label><br>
        <input id="name" type="text" name="name" value="<?= $this->e($form['name']) ?>" required>
    </div>

    <br>

    <div>
        <label for="email">E-mail</label><br>
        <input id="email" type="email" name="email" value="<?= $this->e($form['email']) ?>" required>
    </div>

    <br>

    <div>
        <label for="password"><?= $this->e($form['passwordLabel']) ?></label><br>
        <input id="password" type="password" name="password" <?= !empty($form['passwordRequired']) ? 'required' : '' ?>>
    </div>

    <br>

    <div>
        <label for="role">Perfil</label><br>
        <select id="role" name="role">
            <option value="user" <?= selected_attr($form['role'], 'user') ?>>Usuário</option>
            <option value="admin" <?= selected_attr($form['role'], 'admin') ?>>Admin</option>
        </select>
    </div>

    <br>

    <button type="submit"><?= $this->e($form['button']) ?></button>
</form>
