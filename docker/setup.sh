#!/usr/bin/env bash

# cd to script dir
cd "$(dirname "$0")"

# helper to get distro/codename
distro=""
codename=""
function getDistro() {
    if [[ ! -z $distro ]]; then
        # distro already parsed, don't need to do it again
        return
    fi

    # get distro name and lowercase it
    distro="$(lsb_release -is | sed -r 's/(.+)/\L\1/')"
    case $distro in
        ubuntu|debian)
            # get codename and lowecase it
            codename="$(lsb_release -cs | sed -r 's/(.+)/\L\1/')"
            ;;
        *)
            # if distro can't be read
            if [[ ! -x "$(command -v whiptail)" ]]; then
                sudo apt install --yes whiptail
            fi

            ## i use whiptail here because doing this manually is a pain
            distro=$(whiptail \
                --clear \
                --title "Choose a distro" \
                --menu "We couldn't find your distro. Please select the closes one manually:" \
                15 40 4 \
                "ubuntu" "Ubuntu" \
                "debian" "Debian")

            case $distro in
                ubuntu)
                    codename=$(whiptail \
                        --clear \
                        --title "Choose a distro version" \
                        --menu "Please select the distro version that matches yours the closest:" \
                        15 40 4 \
                        "bionic" "bionic" \
                        "debian" "Debian")
                    ;;
                debian)
                    codename=$(whiptail \
                        --clear \
                        --title "Choose a distro version" \
                        --menu "Please select the distro version that matches yours the closest:" \
                        15 40 4 \
                        "stretch" "stretch" \
                        "wheezy" "wheezy")
                    ;;
                *)
                    echo "Distro selection failed. Exiting..."
                    exit 1;
                    ;;
            esac
            ;;
    esac

    echo "Distro recognized as ${distro} ${codename}"
}

# if needed, install docker
if [[ ! -x "$(command -v docker)" ]]; then
    getDistro

    sudo apt-get install --yes \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg2 \
        software-properties-common

    if [[ $? -ne 0 ]]; then
        echo "Something went wrong while installing dependencies. Exiting..."
        exit 1
    fi

    curl -fsSL "https://download.docker.com/linux/${distro}/gpg" | sudo apt-key add -
    if [[ -z "$(sudo apt-key fingerprint 0EBFCD88)" ]]; then
        echo "Something went wrong while adding dockers GPG key. Exiting..."
        exit 1
    fi

    # TODO: check if [arch=XXX] is required
    sudo add-apt-repository \
       "deb https://download.docker.com/linux/${distro} \
       ${codename} \
       stable"
fi

# if needed, install docker-compose
if [[ ! -x "$(command -v docker-compose)" ]]; then
    url=$(curl -s https://api.github.com/repos/docker/compose/releases/latest \
        | grep browser_download_url \
        | grep run.sh \
        | cut -d '"' -f 4)

    sudo curl -L --fail "${url}" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
fi


# if needed, run docker-compose up -d
# TODO: sudo/docker group/permission check
docker-compose up -d

# if needed, run composer install
if [[ ! -d "${INSTALLER_DIR}/vendor" ]]; then
    # TODO: check if we need to wait for docker to finish setting up the php container
    docker exec trntbl-php composer --working-dir=/app install
fi

# if needed, set up .env
if [[ ! -f ../.env ]]; then
    cp ../.env.example ../.env
    echo "Don't forget to set up your .env"
fi
