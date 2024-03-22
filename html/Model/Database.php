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
    public function delete($table, $data)
    {
        $query = "DELETE FROM $table WHERE ";

        $conditions = array();
        $params = array();
        foreach ($data as $column => $value) {
            $conditions[] = "$column = ?";
            $params[] = $value;
        }
        
        $query .= implode(" AND ", $conditions);
    
        try {
            $stmt = $this->executeStatement($query, $params);
            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Error executing DELETE query: " . $e->getMessage());
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
    
    public function getPaginationData($table, $currentPage, $recordsPerPage)
    {
        $offset = ($currentPage - 1) * $recordsPerPage;

        $query = "SELECT COUNT(*) AS total FROM $table";
        $totalRecords = $this->select($query)[0]['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);

        $query = "SELECT * FROM $table LIMIT $recordsPerPage OFFSET $offset";
        $data = $this->select($query);

        return array(
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
            'data' => $data
        );
    }
}

?>