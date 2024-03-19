<?php

class Database 
{
    protected $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new PDO("pgsql:host=db;port=5432;dbname=mrp_db;user=admin;password=admin");
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function select($query = "" , $params = []) {
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function insert($table, $data)
    {
        try {
            $columns = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $this->executeStatement($sql, $data);
            
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    private function executeStatement($query = "" , $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
    
            if($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            
            if(!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}

?>