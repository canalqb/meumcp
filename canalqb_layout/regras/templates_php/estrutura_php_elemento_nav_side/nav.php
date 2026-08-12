<?php
// navigation.php - Versão Melhorada

// Evita warnings caso alguma variável não exista
$usuarioLogado = isset($usuarioLogado) ? $usuarioLogado : false;
$canal_id = isset($canal_id) ? $canal_id : null);
$canal_nome = isset($canal_nome) ? $canal_nome : null);
$canal_imgP = isset($canal_imgP) ? $canal_imgP : null);
?>



<div class="cqb-nav">
    <?php if ($usuarioLogado && !empty($protecaoValidada)): ?>
        <?php
        // Buscar saldo total para exibir na sidebar
        $stmt_pts_total = $link->prepare("SELECT SUM(vtipoponto) as total FROM pontosdocanal WHERE canal_id = ?");
        $stmt_pts_total->bind_param("s", $canal_id);
        $stmt_pts_total->execute();
        $res_pts_total = $stmt_pts_total->get_result()->fetch_assoc();
        $pontos_totais_sidebar = (int)(isset($res_pts_total['total']) ? $res_pts_total['total'] : 0);
        $stmt_pts_total->close();
        ?>
        
        <!-- Seção: Pontos Totais -->
        <div class="cqb-points-section">
            <div class="cqb-points-label">💰 Seu Saldo Total</div>
            <div class="cqb-points-value" id="cqb-sidebar-pts-val">
                <i class="fas fa-coins cqb-points-icon" style="color: #ffc107;"></i>
                <span class="pts-number"><?= number_format($pontos_totais_sidebar, 0, ',', '.') ?></span>
            </div>
            <a href="#" onclick="carregar2('./pages/extrato.php'); return false;" class="cqb-points-link">
                <i class="fas fa-chart-line"></i> Ver Extrato Detalhado
            </a>
        </div>

        <!-- Seção: Link de Referência -->
        <div class="cqb-referral-container">
            <div class="cqb-referral-title">
                <i class="fas fa-link"></i>
                <span>Link de Indicação</span>
            </div>
            <div class="cqb-referral-wrapper">
                <?php $ref_link = "https://canalqb.infinityfree.me/?r=" . (isset($canal_id) ? $canal_id : ''); ?>
                <span class="cqb-referral-link" id="cqb-ref-text"><?= $ref_link ?></span>
                <button class="cqb-referral-copy-btn" onclick="copiarLinkRef(this, '<?= $ref_link ?>')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <p class="cqb-referral-info">
                <i class="fas fa-gift" style="color: #28a745; margin-right: 4px;"></i>
                <strong>+10 pontos</strong> por cada novo usuário!
            </p>
        </div>

        <script>
            function copiarLinkRef(btn, link) {
                navigator.clipboard.writeText(link).then(() => {
                    const icon = btn.querySelector('i');
                    const original = icon.className;
                    icon.className = 'fas fa-check';
                    btn.style.background = 'linear-gradient(135deg, #20c997 0%, #17a2b8 100%)';
                    
                    setTimeout(() => {
                        icon.className = original;
                        btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                    }, 2000);
                }).catch(err => {
                    console.error('Erro ao copiar:', err);
                });
            }
        </script>

        <!-- Campo de Busca de Canais -->
        <div class="cqb-search-container">
            <div class="cqb-search-title">
                <i class="fas fa-search"></i>
                <span>Buscar Canal</span>
            </div>
            <div class="cqb-search-wrapper">
                <i class="fas fa-search cqb-search-icon"></i>
                <input type="text" 
                       id="cqb-search-input" 
                       class="cqb-search-input" 
                       placeholder="Digite o nome do canal..."
                       autocomplete="off">
                <button id="cqb-search-clear" class="cqb-search-clear" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <script>
            (function() {
                let searchTimeout;
                const searchInput = document.getElementById('cqb-search-input');
                const searchClear = document.getElementById('cqb-search-clear');

                if (searchInput && searchClear) {
                    searchInput.addEventListener('input', function() {
                        const termo = this.value.trim();
                        searchClear.style.display = termo ? 'flex' : 'none';
                        clearTimeout(searchTimeout);
                        
                        if (termo.length >= 2) {
                            searchTimeout = setTimeout(() => {
                                if (typeof carregar2 === 'function') {
                                    carregar2('./pages/descobrir-canais.php?busca=' + encodeURIComponent(termo));
                                }
                            }, 300);
                        } else if (termo.length === 0) {
                            if (typeof carregar2 === 'function') {
                                carregar2('./pages/descobrir-canais.php');
                            }
                        }
                    });

                    searchClear.addEventListener('click', function() {
                        searchInput.value = '';
                        searchClear.style.display = 'none';
                        searchInput.focus();
                        if (typeof carregar2 === 'function') {
                            carregar2('./pages/descobrir-canais.php');
                        }
                    });
                }
            })();
        </script>

        <div class="cqb-nav-heading">📋 MENU PRINCIPAL</div> 

        <a class="cqb-nav-link" href="#" onclick="carregar2('./pages/descobrir-canais.php'); return false;">
            <i class="fas fa-compass"></i>
            <span>Descobrir Canais</span>
        </a>
    <?php endif; ?>

    <?php
    // Menu dinâmico do banco
    $db_path = '';
    if (file_exists('./config/database.php')) {
        $db_path = './config/database.php';
    } elseif (file_exists('./../config/database.php')) {
        $db_path = './../config/database.php';
    } elseif (file_exists('./../../config/database.php')) {
        $db_path = './../../config/database.php';
    }
    if ($db_path) require_once($db_path);


    if (!isset($protecaoValidada)) {
        $protecaoValidada = false;
        if ($canal_id) {
             $protecaoValidada = !empty($_COOKIE['auth_session']) && $_COOKIE['auth_session'] === hash('sha256', $canal_id . 'salt_seguro_qb');
        }
    }

    $liberadoS = ($usuarioLogado && !empty($protecaoValidada));
    $logadoCondicao = $liberadoS ? "'S','SN'" : "'N'),'SN'";
    
    $sql = "SELECT id, nome, href, target, onclick, indice, icone 
            FROM menu
            WHERE logado IN ($logadoCondicao)
            ORDER BY indice ASC, id ASC";
    $result = $link->query($sql);

    $menus_principais = [];
    $menus_filhos = [];
    $menus_P = [];
    $menus_L = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['target'] = !empty($row['target']) ? $row['target'] : "_self");
            $row['onclick'] = !empty($row['onclick']) ? "onclick=\"{$row['onclick']}; return false;\"" : "");
            $row['href'] = htmlspecialchars($row['href']);

            if ($row['indice'] === '0') {
                $menus_principais[$row['id']] = $row;
                $menus_filhos[$row['id']] = [];
            } elseif ($row['indice'] === 'P') {
                $menus_P[] = $row;
            } elseif ($row['indice'] === 'L') {
                $menus_L[] = $row;
            } else {
                if (!isset($menus_filhos[$row['indice']])) {
                    $menus_filhos[$row['indice']] = [];
                }
                $menus_filhos[$row['indice']][] = $row;
            }
        }
    }

    // Função helper para determinar a classe do ícone (fas ou fab para marcas)
    function getIconClass($icone) {
        $brand_icons = ['fa-whatsapp', 'fa-facebook', 'fa-instagram', 'fa-twitter', 'fa-youtube', 'fa-telegram', 'fa-discord', 'fa-tiktok', 'fa-linkedin'];
        return in_array($icone, $brand_icons) ? 'fab' : 'fas');
    }

    // Renderiza menus principais e submenus
    foreach ($menus_principais as $menu_id => $menu) {
        $has_submenu = !empty($menus_filhos[$menu_id]);
        $menu_class = $has_submenu ? 'has-submenu nav-item' : 'nav-item');

        echo "<div class=\"{$menu_class}\">";

        if ($has_submenu) {
            $icone_menu = !empty($menu['icone']) ? $menu['icone'] : 'fa-folder');
            $icone_class = getIconClass($icone_menu);
            echo "<a class=\"cqb-nav-link\" href=\"#\" onclick=\"this.parentElement.classList.toggle('open'); return false;\">";
            echo "<i class=\"{$icone_class} {$icone_menu}\"></i>";
            echo "<span>" . htmlspecialchars($menu['nome']) . "</span>";
            echo "<i class=\"fas fa-chevron-down\"></i>";
            echo "</a>";

            echo '<div class="cqb-submenu">';
            foreach ($menus_filhos[$menu_id] as $submenu) {
                $icone_sub = !empty($submenu['icone']) ? $submenu['icone'] : 'fa-angle-right');
                $icone_sub_class = getIconClass($icone_sub);
                echo "<a class=\"cqb-submenu-link\" href=\"{$submenu['href']}\" target=\"{$submenu['target']}\" {$submenu['onclick']}>"
                    . "<i class=\"{$icone_sub_class} {$icone_sub}\"></i> " . htmlspecialchars($submenu['nome']) . "</a>";
            }
            echo '</div>';
        } else {
            $icone_menu = !empty($menu['icone']) ? $menu['icone'] : 'fa-folder');
            $icone_class = getIconClass($icone_menu);
            echo "<a class=\"cqb-nav-link\" href=\"{$menu['href']}\" target=\"{$menu['target']}\" {$menu['onclick']}>";
            echo "<i class=\"{$icone_class} {$icone_menu}\"></i>";
            echo "<span>" . htmlspecialchars($menu['nome']) . "</span>";
            echo "</a>";
        }
        echo '</div>';
    }

    if ($menus_P) {
        echo '<div class="cqb-nav-heading">⭐ DESTAQUES</div>';
        foreach ($menus_P as $menu) {
            $icone_menu = !empty($menu['icone']) ? $menu['icone'] : 'fa-star');
            $icone_class = getIconClass($icone_menu);
            echo "<a class=\"cqb-nav-link\" href=\"{$menu['href']}\" target=\"{$menu['target']}\" {$menu['onclick']}>";
            echo "<i class=\"{$icone_class} {$icone_menu}\"></i> <span>" . htmlspecialchars($menu['nome']) . "</span></a>";
        }
    }
    if ($menus_L) {
        echo '<div class="sidebar-divider"></div>';
        foreach ($menus_L as $menu) {
            $icon = !empty($menu['icone']) ? $menu['icone'] : (stripos($menu['nome']), 'sair') !== false ? 'fa-sign-out-alt' : 'fa-sign-in-alt'));
            echo "<a class=\"cqb-nav-link\" href=\"{$menu['href']}\" target=\"{$menu['target']}\" {$menu['onclick']}>";
            echo "<i class=\"{$icone_class} {$icon}\"></i> <span>" . htmlspecialchars($menu['nome']) . "</span></a>";
        }
    }
    // Espaço extra no final
    echo '<div style="height: 3rem;"></div>';
    echo '</div>'; // Fecha .cqb-nav
    ?>