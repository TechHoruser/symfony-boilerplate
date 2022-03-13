CREATE USER boilerplate_test WITH PASSWORD 'boilerplate_test' CREATEDB;
CREATE DATABASE boilerplate_test
    WITH OWNER = boilerplate_test
    ENCODING = 'UTF8'
    CONNECTION LIMIT = -1;
