<div class="main-content">
    <h3 class="box-title">Consulta de Contatos</h3>

    <form method="get" action="">
        <input type="hidden" name="pagina" value="consulta">

        <?php $tipoConsulta = $_GET['consulta'] ?? ''; ?>

        <div class="form-group">
            <label for="consulta">Tipo de Consulta:</label>
            <div class="select-wrapper">
                <select name="consulta" id="consulta" class="custom-select" onchange="this.form.submit()">
                    <option value="">---</option>
                    <option value="todos" <?= $tipoConsulta === 'todos' ? 'selected' : '' ?>>Todos</option>
                    <option value="email" <?= $tipoConsulta === 'email' ? 'selected' : '' ?>>Por email</option>
                    <option value="inicial" <?= $tipoConsulta === 'inicial' ? 'selected' : '' ?>>Por inicial</option>
                    <option value="nome" <?= $tipoConsulta === 'nome' ? 'selected' : '' ?>>Por nome</option>
                    <option value="sexo" <?= $tipoConsulta === 'sexo' ? 'selected' : '' ?>>Por sexo</option>
                    <option value="pais" <?= $tipoConsulta === 'pais' ? 'selected' : '' ?>>Por País</option>
                    <option value="geral" <?= $tipoConsulta === 'geral' ? 'selected' : '' ?>>Busca Geral</option>
                </select>
            </div>
        </div>

        <?php if ($tipoConsulta && $tipoConsulta !== 'todos'): ?>

            <?php if ($tipoConsulta === 'email'): ?>
                <div class="form-group">
                    <label for="valor">Email:</label>
                    <input type="email" name="valor" id="valor" value="<?= htmlspecialchars($valor) ?>" placeholder="Digite o email" required>
                </div>
                <button type="submit" class="btn">Buscar</button>

            <?php elseif ($tipoConsulta === 'nome'): ?>
                <div class="form-group">
                    <label for="valor">Nome:</label>
                    <input type="text" name="valor" id="valor" value="<?= htmlspecialchars($valor) ?>" placeholder="Digite o nome" required>
                </div>
                <button type="submit" class="btn">Buscar</button>

            <?php elseif ($tipoConsulta === 'inicial'): ?>
                <div class="form-group">
                    <?php foreach (range('A', 'Z') as $letra): ?>
                        <button 
                            type="submit" 
                            name="valor" 
                            value="<?= $letra ?>" 
                            style="margin: 2px; padding: 6px 10px; <?= $valor === $letra ? 'background-color:#4CAF50; color:#fff;' : '' ?>"
                        ><?= $letra ?></button>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($tipoConsulta === 'sexo'): ?>
                <div class="form-group">
                    <label>Sexo:</label><br>
                    <label>
                        <input type="radio" name="valor" value="M" <?= $valor === 'M' ? 'checked' : '' ?>> Masculino
                    </label>
                    <label>
                        <input type="radio" name="valor" value="F" <?= $valor === 'F' ? 'checked' : '' ?>> Feminino
                    </label>
                </div>
                <button type="submit" class="btn">Buscar</button>

            <?php elseif ($tipoConsulta === 'pais'): ?>
                <div class="form-group">
                    <label for="valor">País:</label>
                    <select name="valor" id="valor" required>
                        <option value="">-- Selecione o país --</option>
                        <?php foreach ($paisesAssoc as $id => $nomePais): ?>
                            <option value="<?= $id ?>" <?= $valor == $id ? 'selected' : '' ?>><?= htmlspecialchars($nomePais) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Buscar</button>

            <?php elseif ($tipoConsulta === 'geral'): ?>
                <div class="form-group">
                    <label for="valor">Buscar:</label>
                    <input type="text" name="valor" id="valor" value="<?= htmlspecialchars($valor) ?>" placeholder="Digite o termo de busca" required>
                </div>
                <button type="submit" class="btn">Buscar</button>

            <?php endif; ?>

        <?php endif; ?>
    </form>


    <!-- Aqui você cola a exibição dos resultados -->
    <?php if (!empty($contatos)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Sexo</th>
                    <th>Telefone</th>
                    <th>País</th>
                    <th>Imagem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $contato): ?>
                    <tr>
                        <td><?= htmlspecialchars($contato['nome']) ?></td>
                        <td><?= htmlspecialchars($contato['email']) ?></td>
                        <td><?= htmlspecialchars($contato['sexo']) ?></td>
                        <td><?= htmlspecialchars($contato['telefone']) ?></td>
                        <td><?= htmlspecialchars($paisesAssoc[$contato['id_pais']] ?? 'Não informado') ?></td>
                        <td class="td-img">
                            <?php if (!empty($contato['imagem'])): ?>
                                <img src="images/<?= htmlspecialchars($contato['imagem']) ?>" alt="Foto de <?= htmlspecialchars($contato['nome']) ?>" class="table-img">
                            <?php else: ?>
                                <span>Sem foto</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (
        !empty($_GET['consulta']) &&
        (
            ($_GET['consulta'] === 'todos' && empty($contatos)) ||
            (isset($_GET['valor']) && $_GET['valor'] !== '' && empty($contatos))
        )
    ): ?>
        <p>Nenhum contato encontrado para esta consulta.</p>
    <?php endif; ?>
</div>

