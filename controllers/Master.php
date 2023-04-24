<?php

require_once '../config.php';

class Master extends DBConnection 
{
    private $settings;

    public function __construct()
    {
        global $_settings;

        $this->settings = $_settings;

        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function captureErr() 
    {
        if (!$this->conn->error) {
            return false;
        } else {
            $resp['status'] = "false";
            $resp['error'] = $this->conn->error;

            return json_encode($resp);
            exit;
        }
    }

    function saveSupplier() 
    {
        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id'])) {
                if (!empty($data)) {
                    $data .= ",";
                }

                $data .=  " `{$key}` = '{$value}' ";
            }
        }

        $check = $this->conn->query("
            SELECT 
                * 
            FROM 
                supplier_list 
            WHERE name = '{$name}' ". (!empty($id) ? " and id != {$id} " : ""). " ")->num_rows;

        if ($this->captureErr()) {
            return $this->captureErr();
        }

        if ($check > 0) {
            $resp['status'] = "failed";
            $resp['msg'] = "O nome do fornecedor já existe.";

            return json_encode($resp);
            exit;
        }

        if (empty($id)) {
            $sql = "INSERT INTO supplier_list SET {$data}";
            $save = $this->conn->query($sql);
        } else {
            $sql = "UPDATE supplier_list SET {$data} WHERE id = '{$id}'";
            $save = $this->conn->query($sql);
        }

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $res['msg'] = "Novo fornecedor salvo com sucesso.";
                $id = $this->conn->insert_id;
            } else {
                $res['msg'] = "Novo fornecedor atualizado com sucesso.";
            }

            $this->settings->setFlashData("success", $res['msg']);

        } else {
            $resp['status'] = "failed";
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }

        return json_encode($resp);
    }

    function deleteSupplier() 
    {
        extract($_POST);

        $del = $this->conn->query("DELETE FROM supplier_list WHERE id = '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Fornecedor excluido com sucesso.");
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function saveItem() 
    {
        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id'])) {
                $value = $this->conn->real_escape_string($value);

                if (!empty($data)) {
                    $data .= ",";
                }

                $data .= " `{$key}` = '{$value}' ";
            }
        }

        $check = $this->conn->query("
                SELECT 
                    * 
                FROM 
                    item_list 
                WHERE name = '{$name}' AND supplier_id = '{$supplierID}' ". (!empty($id) ? " and id != {$id} " : ""). " ")->num_rows;

        if ($this->captureErr()) {
            return $this->captureErr();
        }

        if ($check > 0) {
            $resp['status'] = "failed";
            $resp['msg'] = "O item já existe no fornecedor selecionado.";

            return json_encode($resp);
            exit;
        }

        if (empty($id)) {
            $sql = "INSERT INTO item_list SET {$data}";
            $save = $this->conn->query($sql);
        } else {
            $sql = "UPDATE item_list SET {$data} WHERE id = '{$id}'";
            $save = $this->conn->query($sql);
        }

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $this->settings->setFlashData("success", "Novo item salvo com sucesso.");
            } else {
                $this->settings->setFlashData("success", "Item atualizado com sucesso.");
            }
        } else {
            $resp['status'] = "failed";
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }

        return json_encode($resp);
    }

    function deleteItem() 
    {
        extract($_POST);

        $del = $this->conn->query("DELETE FROM item_list WHERE id = '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setflashData("success", "Item excluido com sucesso.");
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function savePurchaseOrder() 
    {
        if (empty($_POST['id'])) {
            $prefix = "PO";
            $code = sprintf("%'.04d", 1);

            while (true) {
                $checkCode = $this->conn->query("
                    SELECT 
                        *
                    FROM
                        purchase_order_list  
                    WHERE
                        po_code = '". $prefix . '-'. $code ."' ")->num_rows;
                
                if ($checkCode > 0) {
                    $code = sprintf("%'.04d", $code + 1);
                } else {
                    break;
                }
            }

            $_POST['po_code'] = $prefix . "-" . $code;
        }

        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id']) && !is_array($_POST[$key])) {
                if (!is_numeric($value)) {
                    $value = $this->conn->real_escape_string($value);
                }

                if (!empty($data)) {
                    $data .= ", ";
                }

                $data .= " `{$key}` = '{$value}'";
            }   
        }

        if (empty($id)) {
            $sql = "INSERT INTO purchase_order_list SET {$data}";
        } else {
            $sql = "UPDATE purchase_order_list SET {$data} WHERE id = '{$id}'";
        }

        $save = $this->conn->query($sql);

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $purchaseOrderID = $this->conn->insert_id;
            } else {
                $purchaseOrderID = $id;
            }

            $resp['id'] = $purchaseOrderID;
            $data = "";

            foreach ($itemID as $key => $value) {
                if (!empty($data)) {
                    $data .= ", ";
                }

                $data .= "('{$purchaseOrderID}', '{$value}', '{$qty[$key]}', '{$price[$key]}', '{$unit[$key]}', '{$total[$key]}')";
            }

            if (!empty($data)) {
                $this->conn->query("DELETE FROM po_items WHERE po_id = '{$purchaseOrderID}'");
                $save = $this->conn->query("INSERT INTO po_items (po_id, item_id, quantity, price, unit, total) VALUES ($data)");

                if (!$save) {
                    $resp['status'] = "failed";

                    if (empty($id)) {
                        $this->conn->query("DELETE FROM po_items WHERE po_id = '{$purchaseOrderID}'");

                    }

                    $resp['status'] = "Ocorreu uma falha ao salvar na lista de pedidos de compra. Error: ". $this->conn->error;
                    $resp['sql'] = "INSERT INTO po_items (po_id, item_id, quantity, price, unit, total) VALUES {$data}";        
                }
            }
            
        } else {
            $resp['status'] = "failed";
            $resp['msg'] = "Ocorreu um erro. Error: ". $this->conn->error;
        }

        if ($resp['status'] == "success") {
            if (empty($id)) {
                $this->settings->setFlashData("success", " Nova ordem de compra foi criada com sucesso.");
            } else {
                $this->settings->setFlashData("success", " Detalhes do pedido de compra atualizados com sucesso.");
            }
        }

        return json_encode($resp);
    }

    function deletePurchaseOrder() 
    {
        extract($_POST);

        $backOrder = $this->conn->query("SELECT * FROM back_order_list WHERE po_id = '{$id}'");
        $del = $this->conn->query("DELETE FROM purchase_order_list WHERE id = '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Detalhes do pedido de compra excluido com sucesso.");

            if ($backOrder->num_rows > 0) {
                $bo_res = $backOrder->fetch_all(MYSQLI_ASSOC);
                $receivingIDs = array_column($bo_res, 'receiving_id');
                $backOrderIDs = array_column($bo_res, 'id');
            }

            $query = $this->conn->query("
                SELECT 
                    * 
                FROM
                    receiving_list 
                WHERE 
                    (form_id = '{$id}' and from_order = '1') ". (isset($receivingIDs) && count($receivingIDs) > 0 ? "OR id in (". (implode(',', $receivingIDs)).") OR (form_id in (".(implode(',', $backOrderIDs)).") and from_order = '2') " : ""). " ");
            
            while ($row = $query->fetch_assoc()) {
                $this->conn->query("DELETE FROM stock_list WHERE id in ({$row['stock_ids']}) ");
            }

            $this->conn->query("
                DELETE 
                FROM
                    receiving_list 
                WHERE
                    (form_id = '{$id}' and from_order = '1') ". (isset($receivingIDs) && count($receivingIDs) > 0 ? "OR id in (". (implode(',', $receivingIDs)).") OR (form_id in (". (implode(',', $backOrderIDs)). ") and from_order = '2') " : ""). " ");
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function saveReceiving() 
    {
        if (empty($_POST['id'])) {
            $prefix = "BO";
            $code = sprintf("%'.04d", 1);

            while (true) {
                $checkCode = $this->conn->query("
                    SELECT 
                        * 
                    FROM 
                        back_order_list
                    WHERE
                        bo_code = '". $prefix .'-'. $code ."' ")->num_rows;
                
                if ($checkCode > 0) {
                    $code = sprintf("%'.04d", $code + 1);
                } else {
                    break;
                }    
            }

            $_POST['bo_code'] = $prefix . "-" . $code;

        } else {
            $get = $this->conn->query("SELECT * FROM back_order_list WHERE receiving_id = '{$_POST['id']}' ");

            if ($get->num_rows > 0) {
                $res = $get->fetch_array();
                $backOrderID = $res['id'];
                $_POST['bo_code'] = $res['bo_code'];
            } else {
                $prefix = "BO";
                $code = sprintf("%'.04d", 1);

                while(true) {
                    $checkCode = $this->conn->query("
                        SELECT 
                            * 
                        FROM
                            back_order_list 
                        WHERE
                            bo_code = '". $prefix . '-' . $code . "' ")->num_rows;
                    
                    if ($checkCode > 0) {
                        $code = sprintf("%'.04d", $code + 1);
                    } else {
                        break;
                    }
                }

                $_POST['bo_code'] = $prefix . "-" . $code;
            }
        }

        extract($_POST);
        
        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id', 'bo_code', 'supplier_id', 'po_id']) && !is_array($_POST[$key])) {
                if (!is_numeric($value)) {
                    $value = $this->conn->real_escape_string($value);
                }

                if (!empty($data)) {
                    $data .= ", ";
                }

                $data .= " `{$key}` = '{$value}' ";
            }
        }

        if (empty($id)) {
            $sql = "INSERT INTO receiving_list SET {$data}";
        } else {
            $sql = "UPDATE receiving_list SET {$data} WHERE id = '{$id}'";
        }

        $save = $this->conn->query($sql);

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $receivingID = $this->conn->insert_id;
            } else {
                $receivingID = $id;
            }

            $resp['id'] = $receivingID;

            if (!empty($id)) {
                $stockIDs = $this->conn->query("
                    SELECT
                        * 
                    FROM 
                        receiving_list 
                    WHERE 
                        id = '{$id}'")->fetch_array() ['stock_ids'];

                $this->conn->query("DELETE FROM stock_list WHERE id in ({$stockIDs})");
            }

            $stockIDs = [];

            foreach($itemID as $key => $value) {
                if (!empty($data)) {
                    $data .= ", ";   
                }

                $sql = "INSERT INTO stock_list (item_id, quantity, price, unit, total, type) VALUES ('{$value}', '{$qty[$key]}', '{$price[$key]}', '{$unit[$key]}', '{$total[$key]}', '1')";
                $this->conn->query($sql);
                $stockIDs[] = $this->conn->insert_id;

                if ($qty[$key] < $oqty[$key]) {
                    $backOrderIDs[] = $key;
                }
            }

            if (count($stockIDs) > 0) {
                $stockIDs = implode(",", $stockIDs);
                $this->conn->query("UPDATE receiving_list SET stock_ids = '{$stockIDs}' WHERE id = '{$receivingID}'");
            }

            if (isset($backOrderIDs)) {
                $this->conn->query("UPDATE purchase_order_list SET status = 1 WHERE id = '{$purchaseOrderID}'");

                if ($fromOrder == 2) {
                    $this->conn->query("UPDATE back_order_list SET status = 1 WHERE id = '{$formID}'");
                }

                if (!isset($backOrderID)) {
                    $sql = "INSERT INTO back_order_list SET
                        bo_code = '{$backOrderCode}',
                        receiving_id = '{$receivingID}',
                        po_id = '{$purchaseOrderID}',
                        supplier_id = '{$supplierID}',
                        discount_perc = '{$discountPerc}',
                        tax_perc = '{$taxPerc}'
                    ";

                } else {
                    $sql = "UPDATE back_order_list SET
                        receiving_id = '{$receivingID}',
                        po_id = '{$purchaseOrderID}',
                        supplier_id = '{$supplierID}',
                        discount_perc = '{$discountPerc}',
                        tax_perc = '{$taxPerc}',
                        WHERE
                            bo_id = '{$backOrderID}'
                    ";
                }

                $backOrderSave = $this->conn->query($sql);

                if (!isset($backOrderID)) {
                    $backOrderID = $this->conn->insert_id;
                }

                $stotal = 0;
                $data = "";

                foreach ($itemID as $key => $value) {
                    if (!in_array($key, $backOrderIDs)) {continue;}

                    $total = ($oqty[$key] - $qty[$key]) * $price[$key];
                    $stotal += $total;

                    if (!empty($data)) {$data .= ", ";}

                    $data .= " ('{$backOrderID}', '{$value}', '". ($oqty[$key] - $qty[$key]) ."','{$price[$key]}', '{$unit[$key]}', '{$total}')";
                }

                $this->conn->query("DELETE FROM bo_items WHERE bo_id = '{$backOrderID}'");
                $saveBackOrderItems = $this->conn->query("INSERT INTO (bo_id, item_id, quantity, price, unit, total) VALUES {$data}");

                if ($saveBackOrderItems) {
                    $discount = $stotal * ($discountPerc / 100);
                    $stotal -= $discount;
                    $tax = $stotal * ($taxPerc / 100);
                    $stotal += $tax;
                    $amout = $stotal;

                    $this->conn->query("UPDATE back_order_list SET amount = '{$amount}', discount = '{$discount}', tax = '{$tax}' WHERE id = '{$backOrderID}'");      
                }
            } else {
                $this->conn->query("UPDATE purchase_order_list SET status = 2 WHERE id '{$purchaseOrderID}'");

                if ($fromOrder == 2) {
                    $this->conn->query("UPDATE backOrderList SET status = 2 WHERE id '{$formID}'");
                }
            }   
        } else {
            $resp['status'] = "failed";
            $resp['msg'] = "Ocorreu um erro. Error: ". $this->conn->error;
        }

        if ($resp['status'] == "success") {
            if (empty($id)) {
                $this->settings->setFlashData("success", " Novo estoque recebido com sucesso.");
            } else {
                $this->settings->setFlashData("success", "Detalhes do estoque recebido atualizados com sucesso.");
            }
        }

        return json_encode($resp);
    }

    function deleteReceiving() 
    {
        extract($_POST);

        $query = $this->conn->query("SELECT * FROM receiving_list WHERE id = '{$id}' ");

        if ($query->num_rows > 0) {
            $res = $query->fetch_array();
            $ids = $res['stock_ids'];
        }

        if (isset($ids) && !empty($ids)) {$this->conn->query("DELETE FROM stock_list WHERE id in ($ids) ");}

        $del = $this->conn->query("DELETE FROM receiving_list WHERE id = '{$id}' ");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Detalhes do pedido recebido excluídos com sucesso.");

            if (isset($res)) {
                if ($res['from_order'] == 1) {
                    $this->conn->query("UPDATE purchase_order_list SET status = 0 WHERE id = '{$res['form_id']}' ");
                }
            }
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function deleteBackOrderList() 
    {
        extract($_POST);

        $backOrder = $this->conn->query("SELECT * FROM back_order_list WHERE id = '{$id}'");

        if ($backOrder->num_rows > 0) {$bo_res = $backOrder->fetch_array();}

        $del = $this->conn->query("DELETE FROM back_order_list WHERE id = '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Detalhes do pedido de compra excluídos com sucesso.");
            $query = $this->conn->query("SELECT stock_ids FROM receiving_list WHERE form_id = '{$id}' AND from_order = '2' ");

            if ($query->num_rows > 0) {
                $res = $query->fetch_array();
                $ids = $res['stock_ids'];
                $this->conn->query("DELETE FROM stock_list WHERE id in ($ids) ");
                $this->conn->query("DELETE FROM receiving_list WHERE form_id '{$id}' and from_order = '2' ");
            }

            if (isset($bo_res)) {
                $check = $this->conn->query("
                    SELECT
                        *
                    FROM 
                        receiving_list
                    WHERE 
                        from_order = 1 AND form_id = '{$bo_res['po_id']}' ");

                if ($check->num_rows > 0) {
                    $this->conn->query("UPDATE purchase_order_list SET status = 1 WHERE id = '{$bo_res['po_id']}' ");
                } else {
                    $this->conn->query("UPDATE purchase_order_list SET status = 0 WHERE id = '{$bo_res['po_id']}' ");
                }
            }
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function saveReturn() 
    {
        if (empty($_POST['id'])) {
            $prefix = "R";
            $code = sprintf("%'.04d", 1);

            while (true) {
                $checkCode = $this->conn->query("
                    SELECT 
                        * 
                    FROM 
                        return_list 
                    WHERE 
                        return_code = '". $prefix . '-'. $code ."' ")->num_rows;

                if ($checkCode > 0) {
                    $code = sprintf("%'.04d", $code + 1);
                } else {
                    break;
                }
            }

            $_POST['return_code'] = $prefix ."-". $code;
        }

        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($k, ['id']) && !is_array($_POST[$key])) {
                if (!is_numeric($value)) {$value = $this->conn->real_escape_string($value);}

                if (!empty($data)) {$data .= ", ";}

                $data .= " `{$key}` = '{$value}' ";
            }
        }

        if (empty($id)) {
            $sql = "INSERT INTO return_list SET {$data}";
        } else {
            $sql = "UPDATE return_list SET {$data} WHERE id = '{$id}'";
        }

        $save = $this->conn->query($sql);

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $returnID = $this->conn->insert_id;
            } else {
                $returnID = $id;
            }

            $resp['id'] = $returnID;
            $data = "";
            $stockIDs = [];
            $get = $this->conn->query("SELECT * FROM return_list WHERE id = '{$returnID}'");

            if ($get->num_rows > 0) {
                $res = $get->fetch_array();

                if (!empty($res['stock_ids'])) {
                    $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']}) ");
                }
            }

            foreach ($itemID as $key => $value) {
                $save = $this->conn->query("
                    INSERT INTO 
                        stock_list 
                    SET 
                        item_id = '{$value}', quantity = '{$qty[$key]}', unit = '{$unit[$key]}', price = '{$price[$key]}', total '{$total[$key]}', type = 2 ");

                if ($save) {
                    $stockIDs[] = $this->conn->insert_id;
                }
            }

            $stockIDs = implode(',', $stockIDs);
            $this->conn->query("UPDATE return_list SET stock_ids = '{$stockIDs}' WHERE id = '{$returnID}'");

        } else {
            $resp['status'] = "failed";
            $resp['msg'] = "Ocorreu um erro. Error: ". $this->conn->error;
        }

        if ($resp['status'] == "success") {
            if (empty($id)) {
                $this->settings->setFlashData("success", "Novo registro de item devolvido foi criado com sucesso.");
            } else {
                $this->settings->setFlashData("success", "Registro de item devolvido atualizado com sucesso.");
            }
        }

        return json_encode($resp);
    }

    function deleteReturn() 
    {
        extract($_POST);
        
        $get = $this->conn->query("SELECT * FROM return_list WHERE id = '{$id}'");

        if ($get->num_rows > 0) {
            $res = $get->fetch_array();
        }

        $del = $this->conn->query("DELETE FROM return_list WHERE id = '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Registro de item devolvido excluído com sucesso.");

            if (isset($res)) {
                $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");
            }
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }

    function saveSale() 
    {
        if (empty($_POST['id'])) {
            $prefix = "SALE";
            $code = sprintf("%'.04d", 1);

            while (true) {
                $checkCode = $this->conn->query("
                    SELECT 
                        * 
                    FROM 
                        sales_list 
                    WHERE
                        sales_code = '". $prefix .'-'. $code ."'")->num_rows;
                
                if ($checkCode > 0) {
                    $code = sprintf("%',04d", $code + 1);
                } else {
                    break;
                }
            }

            $_POST['sales_code'] = $prefix . "-" . $code;
        }

        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id']) && !is_array($_POST[$key])) {
                if (is_numeric($value)) {$value = $this->conn->real_escape_string($value);}

                if (!empty($data)) {$data .= ", ";}

                $data .= " `{$key}` = '{$value}'";
            }
        }

        if (empty($id)) {
            $sql = "INSERT INTO sales_list SET {$data}";
        } else {
            $sql = "UPDATE sales_list SET {$data} WHERE id = '{$id}'";
        }

        $save = $this->conn->query($sql);

        if ($save) {
            $resp['status'] = "success";

            if (empty($id)) {
                $saleID = $this->conn->insert_id;
            } else {
                $saleID = $id;
            }

            $resp['id'] = $saleId;
            $data = "";
            $stockIDs = [];
            $get = $this->conn->query("SELECT * FROM sales_list WHERE id = '{$saleID}'");

            if ($get->num_rows > 0) {
                $res = $get->fetch_array();

                if (!empty($res['stock_ids'])) {
                    $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']}) ");
                }
            }

            foreach ($itemID as $key => $value) {
                $save = $this->conn->query("
                    INSERT INTO 
                        stock_list
                    SET
                        item_id = '{$value}', quantity = '{$qty[$key]}', unit = '{$unit[$key]}', price = '{$price[$key]}', total = '{$total[$key]}', type = 2 ");

                if ($save) {
                    $stockIDs[] = $this->conn->insert_id;
                }        
            }

            $stockIDs = implode(',', $stockIDs);
            $this->conn->query("UPDATE sales_list SET stock_ids = '{$stockIDs}' WHERE id = '{$saleID}'");

        } else {
            $resp['status'] = "failed";
            $resp['msg'] = "Ocorreu um erro. Error: ". $this->conn->error;
        }

        if ($resp['status'] == "success") {
            if (empty($id)) {
                $this->settings->setFlashData("success", "Novo registro de vendas foi criado com sucesso.");
            } else {
                $this->settings->setFlashData("success", "Registro de vendas atualizado com sucesso.");
            }
        }

        return json_encode($resp);
    }

    function deleteSale() 
    {
        extract($_POST);

        $get = $this->conn->query("SELECT * FROM sales_list WHERE id = '{$id}'");

        if ($get->num_rows > 0) {
            $res = $get->fetch_array();
        }

        $del = $this->conn->query("DELETE FROM sales_list WHERE id '{$id}'");

        if ($del) {
            $resp['status'] = "success";
            $this->settings->setFlashData("success", "Registro de vendas excluido com sucesso.");

            if (isset($res)) {
                $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");
            }
        } else {
            $resp['status'] = "failed";
            $resp['error'] = $this->conn->error;
        }

        return json_encode($resp);
    }
}

$master = new Master();
$action = !isset($_GET['f']) ? "none" : strtolower($_GET['f']);
$sysset = new SystemSettings();

switch ($action) {
    case 'saveSupplier':
        echo $master->saveSupplier();
        break;
    case 'deleteSupplier':
        echo $master->deleteSupplier();
        break;
    case 'saveItem':
        echo $master->saveItem();
        break;
    case 'deleteItem':
        echo $master->deleteItem();
        break;
    // case 'getItem':
    //     echo $master->getItem();
    //     break;
    case 'savePurchaseOrder':
        echo $master->savePurchaseOrder();
        break;
    case 'deletePurchaseOrder':
        echo $master->deletePurchaseOrder();
        break;
    case 'saveReceiving':
        echo $master->saveReceiving();
        break;
    case 'deleteReceiving':
        echo $master->deleteReceiving();
        break;
    case 'saveReturn':
        echo $master->saveReturn();
        break;
    case 'deleteReturn':
        echo $master->deleteReturn();
        break;
    case 'saveReturn':
        echo $master->saveReturn();
        break;
    case 'saveSale':
        echo $master->saveSale();
        break;
    case 'deleteSale':
        echo $master->deleteSale();
        break;
    default:
        break;
}