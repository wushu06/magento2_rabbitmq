## Import csv data into `search_synonyms` table using Rabbitmq

`
    'queue' => [
        'amqp' => [
            'host' => 'localhost',
            'port' => '5672',
            'user' => 'guest',
            'password' => 'guest',
            'virtualhost' => '/'
        ]
    ],
`
place `test.csv` in `var` folder.

run `$ bin/magento wam:sync:data` to import `test.csv` and for it to be published and added to the queue.

run `$ bin/magento queue:consumers:start ElementaryDataCreate ` to consume the data which will be saved in 
`search_synonyms` table and also will generate timestamped file in `var/log` folder with the imported data.
