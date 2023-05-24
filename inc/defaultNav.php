<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="<?= BASE_URL; ?>index3.html" class="navbar-brand">
            <img src="<?= BASE_URL; ?>dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8;"  alt="AdminLTE Logo">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contato</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" id="dropdownSubMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Dropdown</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadown">
                        <li><a href="#" class="dropdown-item">Alguma ação</a></li>
                        <li><a href="#" class="dropdown-item">Alguma outra ação</a></li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-submenu dropdown-hover">
                            <a href="#" id="dropdownSubMenu2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Passe o mouse para ação</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadown">
                                <li><a href="#" tabindex="-1" class="dropdown-item">Nível 2</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" id="dropdownSubMenu3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Nível 2</a>
                                    <ul aria-labelledby="dropdownsubMenu3" class="dropdown-menu border-0 shadow">
                                        <li><a href="#" class="dropdown-item">3º nível</a></li>
                                        <li><a href="#" class="dropdown-item">3º nível</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" class="dropdown-item">Nível 2</a></li>
                                <li><a href="#" class="dropdown-item">Nível 2</a></li>
                            </ul>
                        </li>
                    </ul>                    
                </li>
            </ul>

            <form class="form-inline ml-0 ml-md-3">
                <div class="input-group input-group-sm">
                    <input type="search" class="form-control from-control-navbar" placeholder="Pesquisar" aria-label="Pesquisar">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <i class="fas fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <div class="media">
                            <img src="<?= BASE_URL; ?>dist/img/user1-128x128.jpg" class="img-size-50 mr-3 img-circle" alt="avatar do usuário">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Alexandre Costa
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">Me ligue sempre que puder...</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 horas atrás</p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <div class="media">
                            <img src="<?= BASE_URL; ?>dist/img/user8-128x128.jpg" class="img-size-50 img-circle mr-3" alt="avatar do usuário">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Gabriel Kloper
                                    <span class="float-right text-sm text-muted"><i class="fas far-star"></i></span>
                                </h3>
                                <p class="text-sm">Recebi sua mensagem</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 horas atrás</p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <div class="media">
                            <img src="<?= BASE_URL; ?>dist/img/user3-128x128.jpg" class="img-size-50 img-circle mr-3" alt="avatar do usuário">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Andreia Saling
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">O assunto vai aqui</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 horas atrás</p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">Ver todas as mensagens</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">15 notificações</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 novas mensagens
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-ddivider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 solicitações de amizade
                        <span class="float-right text-muted text-sm">12 horas</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 novos relatórios
                        <span class="float-right text-muted text-sm">2 dias</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">Ver todas as notificações</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-widget="control-sidebar" data-slide="true" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>