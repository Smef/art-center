#!/bin/sh

# Install Supervisor using the EPEL repository.

# This script will install Supervisor and configure your workers
# that are added in the .platform/files/supervisor/supervisor.ini file.
# It's highly recommended to add all your workers in supervisor.ini
# to avoid conflicts for further updates.

# The `supervisorctl restart all` command will be
# triggered in the postdeploy hook to avoid
# any errors (the app is not set yet and
# it might trigger spawn errors, which will exit with non-zero code)

dnf install -y pip

#check if venv exists
if ! test -f /opt/supervisor/bin/activate; then
    # install supervisor in venv and symlink to /usr/bin
    echo "Creating Python virtual environment"
    python3 -m venv /opt/supervisor

    #activate the venv
    echo "Activating the Python virtual environment"
    source /opt/supervisor/bin/activate

    echo "Upgrading pip"
    pip install --upgrade pip

    echo "Installing supervisor"
    pip install supervisor

    echo "Symlinking supervisor"
    ln -sf /opt/supervisor/bin/supervisord /usr/bin/supervisord
    ln -sf /opt/supervisor/bin/supervisorctl /usr/bin/supervisorctl
fi


echo "Copying Supervisor configuration files"
# process looks for /etc/supervisor/supervisord.conf
mkdir -p /etc/supervisor/
cp .platform/files/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

echo "Copying Supervisor service file"
cp .platform/files/supervisor/supervisord.service /usr/lib/systemd/system/supervisord.service

mkdir -p /var/run/supervisor
mkdir -p /var/log/supervisor
touch /var/log/supervisor/supervisord.log

mkdir -p /etc/supervisor/conf.d
cp .platform/files/supervisor/supervisor.ini /etc/supervisor/conf.d/laravel.ini

echo "Reloading systemd"
systemctl daemon-reload

echo "Starting and enabling Supervisor"
systemctl start supervisord
systemctl enable supervisord

echo "Restarting Supervisor Service"
systemctl restart supervisord

# update supervisor config
supervisorctl reread
supervisorctl update
