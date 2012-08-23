#!/bin/bash

 /opt/lampp/bin/mysqldump -u root dixi | /opt/lampp/bin/mysql -u root 'dixi_test'

