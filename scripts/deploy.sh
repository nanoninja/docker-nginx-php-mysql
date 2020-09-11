docker stack deploy --compose-file ../docker-swarm.yml  lnch-n-lrn
docker service update --config-add source=nginx_default,target=/etc/nginx/conf.d/default.conf,mode=0440 web
docker service update --config-add source=nginx_default_template,target=/etc/nginx/conf.d/default.template,mode=0440 web
docker service update --config-add source=php_ini,target=/usr/local/etc/php/conf.d/php.ini,mode=0440 php