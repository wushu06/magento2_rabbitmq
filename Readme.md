## Import csv data into `search_synonyms` table using Rabbitmq

run `$ bin/magento wam:sync:data` to import `test.csv` in `var/log` and to be published and added to the quer

run `$ bin/magento queue:consumers:start ElementaryDataCreate ` to consume the data which will be saved in 
`search_synonyms` table and also will generate timestamped file in `var/log` folder with the imported data.