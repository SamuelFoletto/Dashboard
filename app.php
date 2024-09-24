<?php 
 
  class Dashboard {
 
    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;
    public $clientesAtivos;
    public $clientesInativos;
    public $totalReclamacoes;
    public $totalElogios;
    public $totalSugestoes;
    public $totalDespesas;


    public function __get($atributo) {
 
      return $this->$atributo;
 
    }
 
    public function __set($atributo, $valor) {
 
      $this->$atributo = $valor;
      return $this;
 
    }
 
  }
 
  // classe de conexão bd
 
  class Conexao {
 
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';
 
    public function conectar() {
 
      try {
 
        $conexao = new PDO (
 
          "mysql:host=$this->host;
          dbname=$this->dbname;",
          "$this->user",
          "$this->pass"
 
        );
 
        $conexao->exec('set charset utf8');
 
        return $conexao;
 
      } catch (PDOException $e) {
 
        echo '<p>' . $e->getMessage() . '</p>';
 
      }
 
    }
 
  }
 
  // Classe (model)
 
  class Bd {
 
    private $conexao;
    private $dashboard;
 
    public function __construct(Conexao $conexao, Dashboard $dashboard) {
 
      $this->conexao = $conexao->conectar();
      $this->dashboard = $dashboard;
 
    }
 
    public function getNumeroVendas() {
 
      $query = 'SELECT COUNT(*) as numero_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio AND :data_fim';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->bindValue(':data_inicio',  $this->dashboard-> __get('data_inicio'));
      $stmt->bindValue(':data_fim',  $this->dashboard-> __get('data_fim'));
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
 
    }
 
    public function getTotalVendas() {
 
      $query = 'SELECT SUM(total) as total_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio AND :data_fim';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->bindValue(':data_inicio',  $this->dashboard-> __get('data_inicio'));
      $stmt->bindValue(':data_fim',  $this->dashboard-> __get('data_fim'));
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
 
    }
 
    // Desafio pra continuar onde o professor parou: 
 
    public function getClientesAtivos() {
 
      $query = 'SELECT COUNT(*) as clientes_ativos FROM tb_clientes WHERE cliente_ativo = 1';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
 
    }
 
    public function getClientesInativos() {
 
      $query = 'SELECT COUNT(*) as clientes_inativos FROM tb_clientes WHERE cliente_ativo = 0';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
 
    }
 
    public function getTotalReclamacoes() {
 
      $query = 'SELECT SUM(tipo_contato = 1) as total_reclamacoes FROM tb_contatos';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
 
    }
 
    public function getTotalElogios() {
 
      $query = 'SELECT SUM(tipo_contato = 2) as total_elogios FROM tb_contatos';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
 
    }
 
    public function getTotalSugestoes() {
 
      $query = 'SELECT SUM(tipo_contato = 3) as total_sugestoes FROM tb_contatos';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
 
    }
 
    public function getTotalDespesas() {
 
      $query = 'SELECT SUM(total) as total_despesas FROM tb_despesas WHERE data_despesa BETWEEN :data_inicio AND :data_fim';
 
      $stmt = $this->conexao->prepare($query);
      $stmt->bindValue(':data_inicio',  $this->dashboard-> __get('data_inicio'));
      $stmt->bindValue(':data_fim',  $this->dashboard-> __get('data_fim'));
      $stmt->execute();
 
      return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
 
    }
 
  }
 
  // logica do script
 
  $dashboard = new Dashboard();
 
  $conexao = new Conexao();
 
  $competencia = explode ( '-', $_GET['competencia']);
 
  $ano = $competencia[0];
  $mes = $competencia[1];
 
  $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
 
  $dashboard-> __set('data_inicio', $ano . '-' . $mes . '-01');
  $dashboard-> __set('data_fim', $ano . '-' . $mes . '-' . $dias_do_mes);
 
  $bd = new Bd($conexao, $dashboard);
 
  $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
  $dashboard->__set('totalVendas', $bd->getTotalvendas());
 
  $dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
  $dashboard->__set('clientesInativos', $bd->getClientesInativos());
 
  $dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
  $dashboard->__set('totalElogios', $bd->getTotalElogios());
  $dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());
 
  $dashboard->__set('totalDespesas', $bd->getTotalDespesas());
 
  echo json_encode($dashboard);
 
?>