restart jobs:
	docker-compose exec php supervisorctl restart all

clean cache:
	docker-compose exec php php yii cache/flush-all

composer install:
	docker-compose exec php composer install --no-dev -o

composer update:
	docker-compose exec php composer update
