<?php

namespace Test\Classes\Filters;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use PDO;
use PDOException;
use Test\Classes\Models\Authors as Authors;

/**
 * This class runs the cron job for adding data to the DB
 * 
 * @property array $fileArray
 * @property int   $totalFileSize
 * @property PDO   $dbRead
 * @property PDO   $dbWrite
 * @property array $params
 */


class CronJob
{
    private $fileArray = [];
    private $totalFilesSize = 0;
    private PDO $dbRead;
    private PDO $dbWrite;
    private $params    = [];
    private $arrayToDb = [];

    public function __construct(PDO $dbRead, PDO $dbWrite, array $params)
    {
        $this->dbRead = $dbRead;
        $this->dbWrite = $dbWrite;
        $this->params  = $params;
        $dir = new RecursiveDirectoryIterator(__DIR__ . '/../../../files');
        foreach(new RecursiveIteratorIterator($dir) as $fileInfo) {
            if($fileInfo->getExtension() === 'xml'){
                $this->totalFilesSize += $fileInfo->getSize();
                $this->fileArray[$fileInfo->getPath()][] = $fileInfo->getFilename();		 
            }
        }

        if($this->totalFilesSize > $this->params['filesizeLimit'])
             $this->accelerateProcess();

        $this->parseContentToArray();

        $this->insert();

    }

    /**
     * Usefull when trying to get the Authors and their books in a $key=>$value pair
     */

    public function getArray() : array
    {
        return $this->arrayToDb;
    }

    /**
     * In order to accelerate the process of inserting new data we should split the $fileArray in
     * smaller arrays in order ro send them to other docker containers for simultaneous processing
     */
    private function accelerateProcess() : void 
    {

        // try{
        //     $this->dbWrite->exec('ALTER TABLE books ADD CONSTRAINT book_name_unique UNIQUE (name);');
        // } catch (PDOException $e) {
        //     echo $e->getMessage();
        // }
    }

    /**
    * We get the content of the XML files found on the server and add group it by author to an array for later use
    */
    private function parseContentToArray() : void
    {
       
        foreach($this->fileArray as $path => $files) {
            foreach($files as $filename) {
                $file = $path. '/'. $filename;
                if (file_exists($file)) {
                    $xml = simplexml_load_file($file);
                    foreach($xml->book as $book) {
                        $this->arrayToDb[(string)$book->author][] = (string)$book->name;
                    }
                } else {
                    exit('Failed to open test.xml.');
                }
            }
        }

    }

    /**
    * We get the list of all the authors by using the array keys of $arrayToDb, because we grouped the books by the author
    * And after inserting each Author we insert all the books written by the author
    * On each table there is the UNIQUE constraint, so there will be no duplicate entries
    * After each Author has been inserted, the author_id is used to insert the books
    */

    private function insert()
    {
        $authors = array_keys($this->arrayToDb);
        
        try{
           
            foreach($authors as $author) {
                $booksByAuthor = $this->arrayToDb[$author];
                $insertAuthor = new Authors ($booksByAuthor, $this->dbRead, $this->dbWrite);
                $insertAuthor->name = (string)$author;
                $insertAuthor->addRow(); 
            }
            
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }

       
    }


}