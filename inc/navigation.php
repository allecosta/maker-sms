<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= BASE_URL ?>admin" class="brand-link text-sm">
        <img src="<?= validateImage($_settings->info("logo")) ?>" alt="Logo da loja" 
            class="brand-image img-circle elevation-3" 
            style="opacity: .8; width: 2.5rem; height: 2.5rem; max-height: unset;">
        <span class="brand-text font-weight-light"><?= $_settings->info("short_name") ?></span>
    </a>
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transiton os-host-scrollbar-horizontal-hidden">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style=" overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="<?= validateImage($_settings->userData("avatar")) ?>" 
                                class="img-circle elevation-2" 
                                style="height: 2rem; object-fit: cover;" alt="Imagem do usuário">
                        </div>
                        <div class="info">
                            <a href="<?= BASE_URL ?>admin/?page=user" 
                                class="d-block"><?= ucwords($_settings->userData("firstname") .' '. $_settings->userData("lastname")) ?>
                            </a>
                        </div>
                    </div>
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item dropdown">
                                <a href="./" class="nav-link nav-home">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Painel</p>
                                </a>
                            </li>
                            <li class="nav-header">Lista Principal</li>
                            <li class="nav-item dropdown">
                                <a href="<?= BASE_URL ?>admin/?page=people" class="nav-link nav-people">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Lista Individual</p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="<?= BASE_URL ?>admin/?page=establishment" class="nav-link nav-establishment">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>lista de Estabelecimentos</p>
                                </a>
                            </li>
                            <li class="nav-header">Manutenção</li>
                            <li class="nav-item dropdown">
                                <a href="<?= BASE_URL ?>admin/?page=state" class="nav-link nav-state">
                                    <i class="nav-icon fas fa-map-marker-alt"></i>
                                    <p>Lista de Estados</p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="<?= BASE_URL ?>admin/?page=city" class="nav-link nav-city">
                                    <i class="nav-icon fas fa-map-marker"></i>
                                    <p>Lista de Cidades</p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="<?= BASE_URL ?>admin/?page=zone" class="nav-link nav-zone">
                                    <li class="nav-icon fas fa-layer-group"></li>
                                    <p>Lista de Zonas</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scollbar-tracker">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px)"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
</aside>
<script>
    $(document).ready(function() {
        let page = "<?= isset($_GET['page']) ? $_GET['page'] : 'home' ?>";
        let s = "<?= isset($_GET['s']) ? $_GET['s'] : "" ?>";

        if (s != "") {
            page = page +"_"+ s;
        }

        if ($(".nav-link .nav-"+ page).length > 0) {
            $(".nav-link .nav-"+ page).addClass("active")

            if ($(".nav-link .nav-"+ page).hasClass("tree-item") == true) {
                $(".nav-link .nav-"+ page).closest(".nav-treeview").siblings("a").addClass("active")
                $(".nav-link .nav-"+ page).closest(".nav-treeview").parent().addClass("menu-open")
            }

            if ($(".nav-link .nav-"+ page).hasClass("nav-is-tree") == true) {
                $(".nav-link .nav-"+ page).parent().addClass("menu-open")
            }
        }
    })
</script>
