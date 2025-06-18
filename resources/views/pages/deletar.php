<div class="main-content">
    <h3 class="box-title">Deletando Contatos</h3>

    <form action="?pagina=deletar" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <div class="select-wrapper">
                <select name="email" id="email" class="custom-select">
                    <?php foreach ($contatos as $contato): ?>
                        <option value="<?= htmlspecialchars($contato['email']) ?>"
                                <?= isset($contatoSelecionado) && $contatoSelecionado['email'] === $contato['email'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($contato['email']) ?>
                            </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit" class="btn">Deletar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <div class="success" id="mensagem-flash">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>                        
</div>
