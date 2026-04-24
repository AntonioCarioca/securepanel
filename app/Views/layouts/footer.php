<?php
/**
 * Rodapé compartilhado com fechamento das estruturas abertas em layouts/header.php.
 */
?>
    <?php if (empty($authUser)): ?>
            </div>
        </main>
    <?php else: ?>
                </main>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
