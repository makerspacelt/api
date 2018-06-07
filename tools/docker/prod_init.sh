#!/bin/bash

set -e
set -x

PROJECT_ROOT="$(dirname $(dirname $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)))"

echo "PROJECT ROOT: ${PROJECT_ROOT}"
cd "${PROJECT_ROOT}"


function setPerms {
	mkdir -p $1
	sudo setfacl -R  -m m:rwx -m u:33:rwX -m u:1000:rwX $1
	sudo setfacl -dR -m m:rwx -m u:33:rwX -m u:1000:rwX $1
}

echo -e '\n## Setting up permissions ... '
setPerms "${PROJECT_ROOT}/var"

echo -e '\n## Preparing configs ...'
cp "/home/project/.env" "${PROJECT_ROOT}/.env"

echo -e '\n## Preparing environment ... '
composer --no-interaction config -g optimize-autoloader true
time SYMFONY_ENV=prod composer --no-interaction install --no-dev

