<?php
namespace Test\Classes\Models;

class Books extends BaseModel
{
    public $name;
    public $tableName = 'books';
    public $columns   = array('id', 'author_id', 'name');
    private $id;
    public $fk = 'author_id';
 

    public function __construct($dbRead, $dbWrite)
    {
        parent::__construct($dbRead, $dbWrite);
    }

    public function getRecords()
    {
        
    }

    public function getId() 
    {
        
    }

    public function addRow()
    {
        $command = $this->dbWrite->prepare('INSERT into authors (name) values (?) ON CONFLICT (name) DO NOTHING');
        $command->execute(array($this->name));
        $sth = $this->dbWrite->prepare('Select id from Authors Where name=?');
        $sth->execute(array($this->name));
        $this->id = $sth->fetch()[0];
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