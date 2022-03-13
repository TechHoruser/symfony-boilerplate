FROM postgres:12.2-alpine

COPY ./init/* /docker-entrypoint-initdb.d/

RUN chmod +x /docker-entrypoint-initdb.d/*.sh

EXPOSE 5432
