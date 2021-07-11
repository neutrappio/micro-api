include .env
export

help: ## help: Help
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


run: ## run (docker): Start containers
	@docker-compose up -d

dstop:
	@docker-compose stop

dclean: ## dclean (docker): Docker drop all volumes system files 
	@docker-compose down
	@docker system prune -af
	@docker volume prune -f


dlogs: ## dlog (docker): Api Logs
	@docker-compose logs --follow --tail 30 service_php

