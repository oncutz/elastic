<?php
namespace Test\Classes\Models;

use PDOException;
use Test\Classes\Models\Books;

class Authors extends BaseModel
{
    public $name;
    public $tableName = 'authors';
    public $columns   = array('author_id', 'name');
    private $id;
    private $books    = [];
 

    public function __construct(?array $books, $dbRead, $dbWrite)
    {
        parent::__construct($dbRead, $dbWrite);
        $this->books   = $books;
    }

    public function getRecords()
    {
        
        $book = new Books($this->dbRead, $this->dbWrite);
        $this->select()->stringAGG($book)->from()->join('INNER', $book)->groupBy();    

        try{
            $query = $this->dbRead->prepare($this->sql);
            $query->execute();
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
                echo $e->getMessage();
        }
      
        return $result;
    }

    public function getId() 
    {
        
    }

    public function addRow()
    {
        $command = $this->dbWrite->prepare('INSERT into authors (name) values (?) ON CONFLICT (name) DO NOTHING');
        $command->execute(array($this->name));
        $sth = $this->dbWrite->prepare('Select author_id from authors Where name=?');
        $sth->execute(array((string)$this->name));
        $this->id = $sth->fetch()['author_id'];
        $this->addBooks();
    }

    private function addBooks()
    {
        $books = $this->dbWrite->prepare('INSERT into books (author_id, name) values (:author_id, :name) ON CONFLICT (name) DO UPDATE SET name = EXCLUDED.name');
        foreach($this->books as $book) {
            $books->execute(array(':author_id' => $this->id,':name' => $book));
        }
    }
}