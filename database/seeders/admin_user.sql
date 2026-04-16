INSERT INTO users (name, email, password, role)
VALUES (
    'Administrador',
    'admin@auth-system.dev.localhost',
    '$2y$12$ad1auVccAM4t9fXhoB6WlOIWjmCLGRpXn/XBx7JTGQlQH1ChTd2MW',
    'admin'
)
ON DUPLICATE KEY UPDATE
name = VALUES(name),
password = VALUES(password),
role = VALUES(role);