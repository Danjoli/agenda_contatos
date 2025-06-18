 <div class="main-content">
    <h3 style="text-align: center;">SISTEMA</h3>
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