<?php include '../resources/includes/header.php'; ?>

<div class="main-content">
    <h3 class="box-title">Adicionar Contatos</h3>

    <!-- Mensagens de erro -->
    <?php if (!empty($erros)): ?>
        <div style="color:red">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Formulario -->
    <form action="index.php?pagina=store" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="" placeholder="Escreva seu E-mail">
        </div>

        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="" placeholder="Escreva seu nome">
        </div>

        <div class="form-group">Sexo:
            <label>
                <input type="radio" name="sexo" value="M"> Masculino
            </label>
            <label>
                <input type="radio" name="sexo" value="F"> Feminino
            </label>
        </div>

        <div class="form-group">
            <label for="nascimento">Data de Cadastro:</label>
            <input type="date" name="nascimento" value="" placeholder="Clique para inserir">
        </div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="number" name="telefone" value="" placeholder="insira seu telefone">
        </div>

        <div class="form-group">
            <label for="pais">País:</label>
            <div class="select-wrapper">
                <select name="id_pais" id="id_pais" class="custom-select">
                    <option value="">Selecione um país</option>
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?= htmlspecialchars($pais['id_pais'])?>">
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

        <button type="sumit" class="btn">Adicionar</button>
    </form>

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

<?php include '../resources/includes/footer.php'; ?>