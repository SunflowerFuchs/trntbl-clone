#!/usr/bin/env bash

# check what needs to be done

# if needed, set up .env

# if needed, install docker

# if needed, install docker-compose

# if needed, run docker-compose up -d

# if needed, install
if [[ ! -d "${INSTALLER_DIR}/vendor" ]]; then
    docker exec trntbl-php composer --working-dir=/app install
fi
