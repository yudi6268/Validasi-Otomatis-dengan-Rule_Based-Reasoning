#!/bin/sh
set -eu

RUNTIME="${APP_RUNTIME:-web}"
PORT_VALUE="${PORT:-10000}"

if [ "$RUNTIME" = "worker" ]; then
    exec php artisan queue:work \
        --queue="${QUEUE_NAME:-default}" \
        --sleep="${QUEUE_SLEEP:-3}" \
        --tries="${QUEUE_TRIES:-1}" \
        --timeout="${QUEUE_TIMEOUT:-300}"
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

exec php artisan serve --host=0.0.0.0 --port="$PORT_VALUE"
