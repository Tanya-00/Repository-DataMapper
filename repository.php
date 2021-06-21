<?php
interface objectRepository {
    public function save(objectMapper $mapper);
    public function remove($mapper);
    public function getById($object_id);
    public function all();
    public function getByField($fieldValue);
}

class obReposit implements objectRepository {
    protected $pdo;

    public function __construct(PDO $database) {
        $this->pdo=$database;
    }

    public function save(objectMapper $mapper): bool {
        $stmt = $this->pdo->prepare("INSER INTO object(object_id, object_name, price, owner) values(?, ?, ?, ?)");
        $stmt->bindParam(1, $this->object_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $this->object_name, PDO::PARAM_STR, 20);
        $stmt->bindParam(3, $this->price, PDO::PARAM_INT);
        $stmt->bindParam(4, $this->owner, PDO::PARAM_STR, 50);
        return $stmt->execute();
    }

    public function remove($mapper) {
        $stmt = $this->pdo->prepare("Delete from object where object_id = ?, object_name = ?, price = ?, owner = ? ");
        $stmt->bindParam(1, $this->object_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $this->object_name, PDO::PARAM_STR, 20);
        $stmt->bindParam(3, $this->price, PDO::PARAM_INT);
        $stmt->bindParam(4, $this->owner, PDO::PARAM_STR, 50);
        return $stmt->execute();
    }

    public function getById($object_id): object
    {
        $stmt = $this->pdo->prepare("Select * from object where object_id = ? ");
        $stmt->bindParam(1, $object_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return new object($row['object_id'],$row['object_name'],$row['price'],$row['owner']);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT object_id,object_name,price, owner FROM object");
        $tableList = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tableList[] = array('object_id'=>$row['object_id'], 'object_name'=>$row['object_name'], 'price'=>$row['price'], 'owner'=>$row['owner']);
        }
        return $tableList;
    }

    public function getByField($fieldValue): array
    {
        $stmt = $this->pdo->prepare("Select ? from object ");
        $stmt->bindParam(1, $fieldValue, PDO::PARAM_INT);
        $stmt->execute();
        $tableList = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tableList[] = array('object_id'=>$row['object_id'], 'object_name'=>$row['object_name'], 'price'=>$row['price'], 'owner'=>$row['owner']);
        }
        return $tableList;
    }
}
?>