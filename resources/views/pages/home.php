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

    <!-- Agora o formulário de edição -->
     <?php if (isset($contatoSelecionado) && is_array($contatoSelecionado)): ?>
        <!-- Formulário de edição só aparece se tiver contato selecionado -->
        <form action="index.php?pagina=update" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= $contatoSelecionado['email'] ?? '' ?>" placeholder="Digite o email">
            </div>

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= $contatoSelecionado['nome'] ?? '' ?>" placeholder="Digite o nome">
            </div>

            <?php if (is_array($contatoSelecionado)): ?>
                <div class="form-group">Sexo:
                    <label>
                        <input type="radio" name="sexo" value="M" <?= ($contatoSelecionado['sexo'] === 'M') ? 'checked' : '' ?>> Masculino
                    </label>
                    <label>
                        <input type="radio" name="sexo" value="F" <?= ($contatoSelecionado['sexo'] === 'F') ? 'checked' : '' ?>> Feminino
                    </label>
                </div>
            <?php else: ?>
                <div class="form-group">Sexo:
                    <label>
                        <input type="radio" name="sexo" value="M"> Masculino
                    </label>
                    <label>
                        <input type="radio" name="sexo" value="F"> Feminino
                    </label>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nascimento">Data de Cadastro:</label>
                <input type="date" name="nascimento" id="nascimento" value="<?= $contatoSelecionado['nascimento'] ?? '' ?>" placeholder="Data de nascimento">
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" value="<?= $contatoSelecionado['telefone'] ?? '' ?>" placeholder="Digite o telefone">
            </div>

            <div class="form-group">
                <label for="id_pais">País:</label>
                <div class="select-wrapper">
                    <select name="id_pais" id="id_pais" class="custom-select">
                        <option value=""><?= htmlspecialchars($nomePaisSelecionado) ?? 'Selecione um país'?></option>
                        <?php foreach ($paises as $pais): ?>
                            <option value="<?= $pais['id_pais'] ?>" <?= ($pais['id_pais'] == $idPaisSelecionado) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pais['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <!-- Contêiner que empilha a barra e a imagem -->
                <div class="file-upload">
                    <label id="nameFoto" for="imagem">Foto:</label>
                    <div class="file-display"></div>
                        <label for="imagem" class="upload-button">
                            <span class="icon">&#128269;</span>
                        </label>
                    <input type="file" name="imagem" id="imagem" accept="image/*">
                </div>
            </div>

            <?php if (!empty($contatoSelecionado['imagem'])): ?>
                <div class="preview-imagem">
                    <img src="images/<?= htmlspecialchars($contatoSelecionado['imagem']) ?>" alt="Foto do usuário" class="foto_perfil">
                </div>

                <!-- Campo oculto com a imagem atual -->
                <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($contatoSelecionado['imagem']) ?>">
            <?php endif; ?>

            <button type="submit" class="btn">Editar</button>
        </form>
    <?php else: ?>
        <!-- Opcional: mensagem ou nada -->
        <p>Por favor, selecione um contato para editar.</p>
    <?php endif; ?>

    <!-- Mensagem de Sucesso ou Erro -->
    <?php if (isset($sucesso) && $sucesso) : ?>
        <div class="success" id="mensagem-flash">
            <p><?= htmlspecialchars($sucesso) ?></p>
        </div>
    <?php elseif (isset($error) && $error) : ?>
        <div class="error" id="mensagem-flash">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif ?>
</div>
