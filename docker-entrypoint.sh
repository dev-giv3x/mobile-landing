#!/bin/bash
set -e

if [ ! -f ".env" ]; then
  echo "Созадние .env конфига..."
  cp .env.example .env

  fi

exec "$@"