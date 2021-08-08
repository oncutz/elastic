# Postgres Elasticsearch Combo
  
     The project was created using:
     - Ubuntu 18
     - PHP 7.4
     - PostgreSQL
     - Docker Compose
     - Elasticsearch (to process large amount of data)
     - PSR-4 autoload
     
     To simplify the process I used a Docker solution in order to get the PostgreSQL DB 
     up and running very easy in a master-replica setup.
     The master is ofcourse used for writing operation and the replica for read.
     
     There is the file cron.php which can be run in order to parse all the XML 
     files located in files directory.
     After this step, the data is assembled in an array like:
     ['author'] => [ 'book1', 'book2', 'book3']
     Using this format we insert the data in the DB, taking in consideration 
     the UNIQUE constraint on the name of the author and the name of the book.
     
     After the insertion is complete, we get the data from the DB and send it 
     to the Elasticsearch where we operate a upsert action, 
     in order to be sure that the records which exist will not be duplicated.
     
     The form will detect each character introduced and send a XHR to getData.php 
     and if there are records found, they will be displayed inside the page.
     
     I did not get the time to complete the task of improving the processing 
     time of large records of data.
     This would have ment to have several docker container running and split 
     the array of data in predefined max values and send each part to a docker 
     container for processing in order to send a bulk insert into a temp table on the master.
     
