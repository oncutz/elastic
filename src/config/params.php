<?php
return 
[
    'dsnRead'       => "pgsql:host='localhost';port=49156;dbname='postgres';",
    'dsnWrite'      => "pgsql:host='localhost';port=49155;dbname='postgres';",
    'db_name'       => 'postgres',
    'db_user'       => 'postgres',
    'db_pass'       => 'my_password',
    'db_server'     => 'localhost',
    'root'          => __DIR__ . '/../files',
    'filesizeLimit' => 100,
    'indexes'       => [
                        'authors'  => ['index' => 'books',
                                            'body'  => ['settings' => ['number_of_shards' => 2,
                                                                        'number_of_replicas' => 0]
                                                        ]
                                            ],
                        'books'    => ['index' => 'books',
                                            'body'  => ['settings' => ['number_of_shards' => 2,
                                                                        'number_of_replicas' => 0]
                                                        ]
                                            ]
                        ]
];