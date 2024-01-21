#!/bin/bash

cat dump/db.sql | docker-compose exec -T db mysql -h localhost -u root -psecret -v