#!/bin/bash

/usr/local/php/bin/php  artisan queue:work  --sleep=1 --timeout=45 --tries=3

