<?php

/**
 * Base Model Class
 * All models should extend this class
 */
class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = connectToDatabase();
    }

    /**
     * Get all records from the table
     * 
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array Array of records
     */
    public function getAll($orderBy = 'id', $order = 'ASC')
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        $result = mysqli_query($this->db, $sql);
        
        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    /**
     * Get a single record by ID
     * 
     * @param int $id The record ID
     * @return array|null The record or null
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get records by a specific column value
     * 
     * @param string $column The column name
     * @param mixed $value The value to search for
     * @return array Array of matching records
     */
    public function getBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $value);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    /**
     * Insert a new record
     * 
     * @param array $data Associative array of column => value
     * @return int|bool The insert ID on success or false on failure
     */
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$this->db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($this->db, $sql)) {
            return mysqli_insert_id($this->db);
        }
        return false;
    }

    /**
     * Update a record
     * 
     * @param int $id The record ID
     * @param array $data Associative array of column => value
     * @return bool True on success or false on failure
     */
    public function update($id, $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $value = mysqli_real_escape_string($this->db, $value);
            $set[] = "{$key} = '{$value}'";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = {$id}";
        
        return mysqli_query($this->db, $sql);
    }

    /**
     * Delete a record
     * 
     * @param int $id The record ID
     * @return bool True on success or false on failure
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        return mysqli_query($this->db, $sql);
    }

    /**
     * Custom query execution
     * 
     * @param string $sql The SQL query
     * @return array|bool Query results or boolean
     */
    public function query($sql)
    {
        $result = mysqli_query($this->db, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $records = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }
        return $result;
    }

    /**
     * Get the last inserted ID
     * 
     * @return int The last insert ID
     */
    public function lastInsertId()
    {
        return mysqli_insert_id($this->db);
    }
}
