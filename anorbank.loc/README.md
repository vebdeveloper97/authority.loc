README

run development
```bash
docker-compose -f docker-compose.yml -f docker-compose-development.yml start
```

start supervisord
```
service supervisor start > /dev/null &
```
