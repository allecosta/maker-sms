# Sistema de Gerenciamento de Estoque

*É um projeto que fornece uma plataforma online e automatizada para lojas ou negócios.<br> 
Esta aplicação pode gerenciar os Pedidos de Compras, Recebimentos, Pedidos Pendentes, Devoluções e Registros de Vendas da empresa. O aplicativo possui uma interface de usuário agradável com a ajuda da biblioteca Bootstrap.*

## Desenvolvido com:

- PHP 8.1.10
- MySQL 8
- JS
- Jquery
- AJax
- Bootstrap

## Sobre o sistema:

*O sistema só pode ser acessado por 2 tipos de usuários que são os administradores do sistema e funcionários.<br> 
O usuário administrador pode acessar e gerenciar todas as páginas, formulários e recursos, enquanto os usuários da 
equipe (funcionários), têm apenas acesso limitado aos registros de estoque como Pedido de Compra, Recebimento, etc.<br> 
Neste sistema temos recursos de impressão para cada registro. Falando sobre o fluxo. Primeiro, os usuários administradores devem preencher todas as listas importantes que são a Lista de Fornecedores e a Lista de Itens. Em seguida, os usuários criarão um registro de Pedido de Compra para um Fornecedor. Depois disso, os usuários podem receber os itens em cada Pedido de Compra, o que significa que os registros do Pedido de Compra são necessários para adicionar item ao estoque.<br> Então, ao Receber os itens quando em caso o Fornecedor só entregar alguns dos itens ou não completos, o sistema automaticamente criará um novo registro de Pedido Pendente para os itens que ainda não foram entregues.<br> 
Os registros de Pedidos Pendentes funcionam como os registros de Pedidos de Compra.<br> 
A seguir, quando os itens recebidos apresentarem problemas, ou etc. A gerência pode criar o registro de devolução e ao salvar este arquivo, o sistema irá subtrair automaticamente os itens da disponibilidade de estoque.<br> 
Por fim, o registro de Vendas é o registro da empresa para os estoques que foram comprados por seus clientes.<br> 
Cada estoque listado no registro de Vendas também será subtraido da disponibilidade de estoque.*

## Recursos

### Login e logout
### Gerencia Lista de Fornecedores
### Gerencia Lista de Itens

### Gerencia registros de Pedidos de Compra
- Cria um novo registro
- Edita registro
- Ver registro
- Imprime registro
- Exclui registro

### Gerencia registros de Recebimento
- Recebe Ordem de Compra
- Cria automaticamente um novo Pedido Pendente para itens em falta no estoque
- Edita registro
- Imprime registro
- Adiciona automaticamente à disponibilidade de estoque do item
- Exclui registro

### Gerencia registros de Pedidos Pendentes
- Ver registro
- Recebe Pedido de Pendente
- Imprime registro

### Gerencia registros de Devolução
- Cria novos registros
- Ver registro
- Edita registro
- Imprime registro
- Exclui registro
- Ao salvar atualiza automaticamente a disponibilidade de estoque 

### Gerencia registros de Vendas
- Cria novos registros
- Ver registro
- Edita registro
- Imprime registro
- Exclui registro
- Ao salvar atualiza automaticamente a disponibilidade de estoque 

### Gerencia Lista de Usuários
### Gerencia detalhes/credenciais da conta
### Gerencia informações do sistema
