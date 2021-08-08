<?php
namespace Test\Classes\Models;
use PDO;

class BaseModel
{
    public $tableName;
    public $sql;
    public $columns = [];
    public $dbWrite;
    public $dbRead;
    public $fk;

    public function __construct(PDO $dbRead, PDO $dbWrite)
    {
        $this->dbRead  = $dbRead;
        $this->dbWrite = $dbWrite;
    }

    public function select()
    {
          $this->sql = "SELECT ";
          foreach($this->columns as $column) {
              $this->sql .= $this->tableName . "." . $column . ", ";
          }
          $this->sql = substr($this->sql, 0, -2);
        
        return $this;
    }

    public function from()
    {
        $this->sql .= " FROM " . $this->tableName;
        
        return $this;
    }

    public function stringAGG(BaseModel $model) 
    {
        foreach($model->columns as $column) {
            $this->sql .= ", STRING_AGG (" . $model->tableName . "." . $column . "::character varying, ',' ORDER BY " . $model->tableName . "." . $column . ") " . $model->tableName . "_" . $column;
        }

        return $this;
    }

    public function join (string $type, BaseModel $model) 
    {
        $this->sql .= " " . $type . " JOIN " . $model->tableName . " USING (" . $model->fk . ") ";

        return $this;
    }

    public function groupBy()
    {
        $this->sql .= "GROUP BY ";
        foreach($this->columns as $column) {
            $this->sql .=  $this->tableName . "." . $column . ", ";
        }
        $this->sql = substr($this->sql, 0, -2);
        return $this;
    }

    public function getDb() 
    {
        
    }

}