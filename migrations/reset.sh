#!/bin/bash

set -e  # stop execution on ANY error

# read .env file in root
MIGRATIONS_DIR="$(dirname "$0")"
# export $(grep -h -v '^#' $MIGRATIONS_DIR/../.env | xargs)
set -a
source "$MIGRATIONS_DIR/../.env"
set +a

#check .env vars
: "${DB_PORT:?DB_PORT not set}"
: "${DB_USER:?DB_USER not set}"
: "${DB_NAME:?DB_NAME not set}"
: "${DB_PASS:?DB_PASS not set}"

PSQL_FILE="psql -p $DB_PORT --username=$DB_USER --dbname=$DB_NAME --tuples-only -f"

PSQL_C="psql -X -p $DB_PORT --username=$DB_USER --dbname=$DB_NAME --tuples-only -c"

echo -e "\n~~ DB migrations ~~\n"

echo -e "\n~~ Deleting tables...\n"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS notification_logs CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS alerts CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS price_history CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS products CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS stores CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TABLE IF EXISTS users CASCADE"
PGPASSWORD=$DB_PASS $PSQL_C "DROP TYPE IF EXISTS alert_type"

echo -e "\nReset done!"