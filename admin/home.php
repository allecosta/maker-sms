<h1 class="">Bem vindo(a) ao <?= $_settings->info("name"); ?></h1>
<hr>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pedido de Compra</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM purchase_order_list")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Recebimento</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM receiving_list")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-exchange-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pedido Pendente</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM back_order_list")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-undo"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Retorno</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM return_list")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vendas</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM sales_list")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-navy elevation-1"><i class="fas fa-truck-loading"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Fornecedores</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM supplier_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-lightblue elevation-1"><i class="fas fa-th-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Items</span>
                <span class="info-box-number text-right">
                    <?= $conn->query("SELECT * FROM item_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>
    <?php if ($_settings->userData("type") == 1): ?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-light shadow">
                <span class="info-box-icon bg-teal elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Usuários</span>
                    <span class="info-box-number text-right">
                        <?= $conn->query("SELECT * FROM users WHERE id != 1")->num_rows; ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>