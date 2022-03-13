for DB in $(psql -U $POSTGRES_USER -t -c "SELECT datname FROM pg_database WHERE datname NOT IN ('postgres', 'template0', 'template1')"); do
  psql -U $POSTGRES_USER -d $DB -c "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\""
done