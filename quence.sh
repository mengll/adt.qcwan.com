#!/bin/bash

/usr/local/php/bin/php  artisan queue:work  --sleep=1 --timeout=10 --tries=1 
