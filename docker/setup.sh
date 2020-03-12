#!/usr/bin/env bash

# cd to script dir
cd "$(dirname "$0")"

# helper function for pretty messages
message() {
    echo -ne "\n"
    echo -ne "===========================================================\n"
    echo -ne "\t$(tput setaf ${2:-3})${1}$(tput sgr0)\n"
    echo -ne "===========================================================\n"
    echo -ne "\n"
}

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
                sudo apt-get install -qq --yes whiptail
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
}

# if needed, install docker
if [[ ! -x "$(command -v docker)" ]]; then
    getDistro

    message "Installing docker..."

    echo "Installing dependencies..."
    sudo apt-get -qq update
    sudo apt-get -qq install --yes \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg2 \
        software-properties-common

    if [[ $? -ne 0 ]]; then
        echo "Something went wrong while installing dependencies. Exiting..."
        exit 1
    fi

    echo "Adding GPG key..."
    curl -fsSL "https://download.docker.com/linux/${distro}/gpg" | sudo apt-key add -
    if [[ -z "$(sudo apt-key fingerprint 0EBFCD88 2>/dev/null)" ]]; then
        echo "Something went wrong while adding dockers GPG key. Exiting..."
        exit 1
    fi

    echo "Adding repository"
    sudo add-apt-repository \
       "deb https://download.docker.com/linux/${distro} \
       ${codename} \
       stable"
    sudo apt-get -qq update

    echo "Installing docker packages..."
    sudo apt-get -qq --yes install docker-ce docker-ce-cli containerd.io

    echo "Successfully installed docker!"
fi


# docker permission check
# check if we're root, then we don't need to do anything
sudo=""
if [[ "$(id -u)" -ne 0 ]]; then
    # if the current user is part of the docker group, we also don't need to do anything
    if [[ "$(groups | grep --count '\bdocker\b')" -eq 0 ]]; then
        read -p "Do you want to enable sudo-less docker execution in the future? " yn < /dev/tty
        case $yn in
            [Yy]* )
                sudo usermod -aG docker "${USER}"
                echo "Log out and back in to update your user groups and use docker without sudo"
                sg docker -c "/usr/bin/env bash $0"
                exit $?
                ;;
        esac

        sudo="sudo"
    fi
fi

# if needed, install docker-compose
if [[ ! -x "$(command -v docker-compose)" ]]; then
    message "Installing docker-compose..."

    echo "Downloading runner..."
    url=$(curl -s https://api.github.com/repos/docker/compose/releases/latest \
        | grep browser_download_url \
        | grep run.sh \
        | cut -d '"' -f 4)

    sudo curl -s -L --fail "${url}" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    ${sudo} docker-compose --help >/dev/null

    echo "Successfully installed docker-compose"
fi

message "Building containers..."
${sudo} docker-compose build --pull

message "Starting containers..."
${sudo} docker-compose up -d

# if needed, run composer install
if [[ ! -d "${INSTALLER_DIR}/vendor" ]]; then
    message "Running first time installer..."
    ${sudo} docker exec trntbl-php composer --working-dir=/app install
fi

# if needed, set up .env
if [[ ! -f ../.env ]]; then
    message "Creating base .env"
    cp ../.env.example ../.env
    ${sudo} docker exec trntbl-php php artisan key:generate
    echo "Don't forget to set up your .env"
fi

# set up directory permissions
chmod -R a+rw ../storage/logs/ ../storage/framework/cache/ ../storage/framework/views/
