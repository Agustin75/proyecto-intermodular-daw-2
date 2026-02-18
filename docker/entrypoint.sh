#!/bin/bash

# Si la carpeta está vacía, clona
if [ ! -d "/var/www/html/.git" ]; then
    git clone https://github.com/Agustin75/proyecto-intermodular-daw-2.git /var/www/html
else
    cd /var/www/html
    git pull
fi

apache2-foreground
