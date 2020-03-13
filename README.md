# Convert JSON to CSV using php

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![GitHub stars](https://img.shields.io/github/stars/ship87/json-csv-converter-php.svg)](https://github.com/ship87/json-csv-converter-php/stargazers)

## Build and run

`sudo chmod -R 777 public/download &&\
 docker run --rm -v $(pwd):/app composer install &&\
  docker-compose up -d --force-recreate --no-deps --build`
  
## Test

`curl -X POST -H 'Content-Type:application/json' --data '[{"number":1,"columns":["Line1", "Test1", "Lorem ipsum dolor sit amet, consectetuer adipiscing elit,"]},{"number":2,"columns":["Line2", "Test2", "sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat."]}]' http://localhost:8080 -vvv`
