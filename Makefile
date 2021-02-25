# Makefile
#
# This file contains the commands most used in DEV, plus the ones used in CI and PRD environments.
#

# Execute targets as often as wanted
.PHONY: config

# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

# make commands be run with `bash` instead of the default `sh`
SHELL='/bin/bash'

include Makefile.defaults.mk
ifneq ("$(wildcard Makefile.defaults.custom.mk)","")
  include Makefile.defaults.custom.mk
endif

# .DEFAULT: If the command does not exist in this makefile
# default:  If no command was specified
.DEFAULT default:
	if [ -f ./Makefile.custom.mk ]; then \
	    $(MAKE) -f Makefile.custom.mk "$@"; \
	else \
	    if [ "$@" != "default" ]; then echo "Command '$@' not found."; fi; \
	    $(MAKE) help; \
	    if [ "$@" != "default" ]; then exit 2; fi; \
	fi

help:  ## Show this help
	@echo "Usage:"
	@echo "     [ARG=VALUE] [...] make [command]"
	@echo "     make env-status"
	@echo "     NAMESPACE=\"dummy-app-namespace\" RELEASE_NAME=\"another-dummy-app\" make env-status"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m  %-30s\033[0m %s\n", $$1, $$2}' | sed 's/://'

########################################################################################################################

#
# Please keep the commands in alphabetical order
#

box-build:  ## Build the docker image for development (without the code)
	docker build -t ${IMAGE_TAG} -f ./build/container/cli-sms.alpine.dockerfile .

box-push:  ## Push the docker image to docker hub (you need to login on the CLI first with `docker login`)
	docker push ${IMAGE_TAG}

box-pull:  ## Pull the docker image from docker hub, even if its already locally
	DOCKER_USER_ID=${HOST_USER_ID} IMAGE_TAG=${IMAGE_TAG} docker-compose -f build/container/docker-compose.yml pull

#web:  ## Start the docker container as web server
#	docker-compose -f build/container/docker-compose.yml pull
#	docker-compose -f build/container/docker-compose.yml run app "php bin/console server:run 0.0.0.0:8000"

.cs-fix:  ## Fix coding standards
	php vendor/bin/php-cs-fixer fix --verbose

cs-fix:  ## Fix coding standards
	./scripts/run-in-docker.sh "make --no-print-directory .cs-fix"

# We use phpdbg because is part of the core and so that we don't need to install xdebug just to get the coverage.
# Furthermore, phpdbg gives us more info in certain conditions, ie if the memory_limit has been reached.
.coverage:  ## Run unit tests, with coverage report
	-phpdbg -qrr bin/phpunit --coverage-text --coverage-clover=${COVERAGE_REPORT_PATH}

coverage:  ## Run unit tests, with coverage report
	-./scripts/run-in-docker.sh "make --no-print-directory .coverage; sed -i 's@/opt/app/@${PWD}/@' ${COVERAGE_REPORT_PATH}"

.db-migrate:  ## Run the migrations
	php bin/console doctrine:migrations:migrate --no-interaction

db-migrate:  ## Run the migrations
	./scripts/run-in-docker.sh "make --no-print-directory .db-migrate"

.db-setup:  ## Setup the initial DB
	mkdir -p ./var/data
	rm -f ${DB_PATH}
	php bin/console doctrine:database:drop -n --force
	php bin/console doctrine:database:create -n
	php bin/console doctrine:schema:create -n
	php bin/console doctrine:migrations:migrate -n
	php bin/console doctrine:fixtures:load -n

db-setup:  ## Setup the initial DB
	./scripts/run-in-docker.sh "make --no-print-directory .db-setup"

.db-inspect:  ## Show the contents of the DB
	sqlite3 ${DB_PATH} '.tables'
	echo
	echo "=== KeyValueStorage ==="
	sqlite3 -column -header ${DB_PATH} 'SELECT * FROM KeyValueStorage;'
	echo
	echo "=== User ==="
	sqlite3 -column -header ${DB_PATH} 'SELECT * FROM User;'
	echo

db-inspect:  ## Show the contents of the DB
	./scripts/run-in-docker.sh "make --no-print-directory .db-inspect"

.dep_analyzer-install:  ## Download the dependencies analyser (Deptrac), if its not there already
	if [ ! -f ./deptrac ]; then \
	    curl -LS https://github.com/qossmic/deptrac/releases/download/0.11.1/deptrac.phar -o deptrac; \
	    chmod +x deptrac; \
	fi
	echo
	echo "If you want to create nice dependency graphs, you need to install graphviz:"
	echo "    - For osx/brew: $ brew install graphviz"
	echo "    - For ubuntu: $ sudo apt-get install graphviz"
	echo "    - For windows: https://graphviz.gitlab.io/_pages/Download/Download_windows.html"

dep_analyzer-install:  ## Download the dependencies analyser (Deptrac), if its not there already
	./scripts/run-in-docker.sh "make --no-print-directory .dep_analyzer-install"

.dep-install:  ## Install all dependencies, including dev dependencies
	composer install

dep-install:  ## Install all dependencies, including dev dependencies
	./scripts/run-in-docker.sh "make --no-print-directory .dep-install"

.dep-install-prd:  ## Install dependencies, ready for production
	composer install --no-dev --optimize-autoloader --no-ansi --no-interaction --no-progress --no-scripts

dep-install-prd:  ## Install dependencies, ready for production
	./scripts/run-in-docker.sh "make --no-print-directory .dep-install-prd"

.dep-update:  ## Update dependencies to new versions
	composer update

dep-update:  ## Update dependencies to new versions
	./scripts/run-in-docker.sh "make --no-print-directory .dep-update"

.host-ip:  # Get the IP from where the guest can reach the host, needed to setup Xdebug
	/sbin/ip route | awk '/default/ {print $3}'

host-ip:  # Get the IP from where the guest can reach the host, needed to setup Xdebug
	./scripts/run-in-docker.sh "make --no-print-directory .host-ip"

.setup:  ## Install all project dependencies for development and testing
	cp .env.dist .env
	$(MAKE) .dep-install
	$(MAKE) .db-setup
	$(MAKE) .db-inspect
	$(MAKE) .test
	$(MAKE) .coverage
	cd /opt/app/vendor/bin && ln -s .phpunit phpunit && cd /opt/app
	echo
	echo "From inside the container, Xdebug can reach the host (IDE) on IP:"
	$(MAKE) .host-ip
	echo "You need to set this IP in config 'xdebug.remote_host' in files 'build/container/xdebug.ini' and 'build/container/docker-compose.yml'"

setup:  ## Install all project dependencies for development and testing
	./scripts/run-in-docker.sh "make --no-print-directory .setup"

shell:  ## Start the docker container and get a shell inside
	DOCKER_USER_ID=${HOST_USER_ID} IMAGE_TAG=${IMAGE_TAG} docker-compose -f build/container/docker-compose.yml run app bash

.test: ## Run all tests
	$(MAKE) .cs-fix
	$(MAKE) .test-unit
	$(MAKE) .test-dep

test: ## Run all tests
	./scripts/run-in-docker.sh "make --no-print-directory .test"

.test-dep:  ## Test the architecture dependencies
	$(MAKE) .test-dep-components
	$(MAKE) .test-dep-layers
	$(MAKE) .test-dep-class

test-dep: ## Test the architecture dependencies
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep"

.test-dep-graph:  ## Test the architecture dependencies and generate graphs
	$(MAKE) .test-dep-components-graph
	$(MAKE) .test-dep-layers-graph
	$(MAKE) .test-dep-class-graph

test-dep-graph:  ## Test the architecture dependencies and generate graphs
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-graph"

.test-dep-components:
	./deptrac analyze depfile.components.yml

test-dep-components:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-components"

.test-dep-components-graph:
	./deptrac analyze depfile.components.yml --graphviz-dump-image=var/deptrac_components.png --graphviz-dump-dot=var/deptrac_components.dot

test-dep-components-graph:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-components-graph"

.test-dep-layers:
	./deptrac analyze depfile.layers.yml

test-dep-layers:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-layers"

.test-dep-layers-graph:
	./deptrac analyze depfile.layers.yml --graphviz-dump-image=var/deptrac_layers.png --graphviz-dump-dot=var/deptrac_layers.dot

test-dep-layers-graph:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-layers-graph"

.test-dep-class:
	./deptrac analyze depfile.classes.yml

test-dep-class:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-class"

.test-dep-class-graph:
	./deptrac analyze depfile.classes.yml --graphviz-dump-image=var/deptrac_class.png --graphviz-dump-dot=var/deptrac_class.dot

test-dep-class-graph:
	./scripts/run-in-docker.sh "make --no-print-directory .test-dep-class-graph"

.test-unit: ## Run unit tests
	php bin/phpunit

test-unit: ## Run unit tests
	-./scripts/run-in-docker.sh "make --no-print-directory .test-unit"
