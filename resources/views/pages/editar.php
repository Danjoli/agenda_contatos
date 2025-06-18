<div class="main-content">
    <h3 class="box-title">Editar Contatos</h3>

    <!-- Formulário de seleção do contato -->
    <form method="get" action="">
        <div class="form-group">
            <label for="contato">Contato:</label>
            <div class="select-wrapper">
                <select name="contato" id="contato" class="custom-select" onchange="this.form.submit()">
                    <option value="">Selecione um contato</option>
                    <?php foreach ($contatos as $contato): ?>
                        <option value="<?= htmlspecialchars($contato['email']) ?>"
                            <?= isset($contatoSelecionado) && $contatoSelecionado['email'] === $contato['email'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($contato['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
</div>
