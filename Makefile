# Makefile
#
# This file contains the commands most used in DEV, plus the ones used in CI and PRD environments.
#
# The commands are to be organized semantically and alphabetically, so that similar commands are nex to each other
# and we can compare them and update them easily.
#
# For example in a format like `subject-action-environment`, ie:
#
#   box-build-base:     # box is a generic term, we don't care if it is a virtual machine or a container
#   box-build-ci:
#   box-build-dev:
#   box-push-base:
#   box-push-prd:
#   cs-fix:             # here we don't use the env because we only do it in dev
#   dep-install:        # again, by default the env is dev
#   dep-install-ci:
#   dep-install-prd:
#   dep-update:
#   test:               # here we don't even have a subject because it is the app itself, and by default the env is dev
#   test-ci:            # here we don't even have a subject because it is the app itself
#

# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

# .DEFAULT: If the command does not exist in this makefile
# default:  If no command was specified
.DEFAULT default:
	if [ -f ./Makefile.custom ]; then \
	    $(MAKE) -f Makefile.custom "$@"; \
	else \
	    if [ "$@" != "" ]; then echo "Command '$@' not found."; fi; \
	    $(MAKE) help; \
	    if [ "$@" != "" ]; then exit 2; fi; \
	fi

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

COVERAGE_REPORT_PATH="var/coverage.clover.xml"
#DB_PATH='/%kernel.project_dir%/var/data/cli-sms.db'
DB_PATH='/opt/werkspot.com/instapro/var/cache/cli-sms.db'

cs-fix:
	php vendor/bin/php-cs-fixer fix --verbose

db-migrate:
	php bin/console doctrine:migrations:migrate --no-interaction

db-setup:
	mkdir -p ./var/data
	rm -f ${DB_PATH}
	php bin/console doctrine:database:drop -n --force
	php bin/console doctrine:database:create -n
	php bin/console doctrine:schema:create -n
	php bin/console doctrine:migrations:migrate -n
	php bin/console doctrine:fixtures:load -n

db-inspect:
	sqlite3 ${DB_PATH} '.tables'
	echo
	echo "=== KeyValueStorage ==="
	sqlite3 -column -header ${DB_PATH} 'SELECT * FROM KeyValueStorage;'
	echo
	echo "=== User ==="
	sqlite3 -column -header ${DB_PATH} 'SELECT * FROM User;'
	echo

dep_analyzer-install:
	curl -LS http://get.sensiolabs.de/deptrac.phar -o deptrac
	chmod +x deptrac
	echo
	echo "If you want to create nice dependency graphs, you need to install graphviz:"
	echo "    - For osx/brew: $ brew install graphviz"
	echo "    - For ubuntu: $ sudo apt-get install graphviz"
	echo "    - For windows: https://graphviz.gitlab.io/_pages/Download/Download_windows.html"

dep-install:
	composer install

dep-install-prd:
	composer install --no-dev --optimize-autoloader --no-ansi --no-interaction --no-progress --no-scripts

dep-update:
	composer update

test:
	- $(MAKE) cs-fix
	ENV='tst' php vendor/bin/phpunit
	$(MAKE) test-acc
	$(MAKE) test-dep

test-acc:
	ENV='tst' make db-setup
	ENV='tst' php vendor/bin/codecept run -g acceptance

test-dep:
	$(MAKE) test-dep-components
	$(MAKE) test-dep-layers
	$(MAKE) test-dep-class

test-dep-graph:
	$(MAKE) test-dep-components-graph
	$(MAKE) test-dep-layers-graph
	$(MAKE) test-dep-class-graph

test-dep-components:
	./deptrac analyze depfile.components.yml --formatter-graphviz=0

test-dep-components-graph:
	./deptrac analyze depfile.components.yml --formatter-graphviz-dump-image=var/deptrac_components.png --formatter-graphviz-dump-dot=var/deptrac_components.dot

test-dep-layers:
	./deptrac analyze depfile.layers.yml --formatter-graphviz=0

test-dep-layers-graph:
	./deptrac analyze depfile.layers.yml --formatter-graphviz-dump-image=var/deptrac_layers.png --formatter-graphviz-dump-dot=var/deptrac_layers.dot

test-dep-class:
	./deptrac analyze depfile.classes.yml --formatter-graphviz=0

test-dep-class-graph:
	./deptrac analyze depfile.classes.yml --formatter-graphviz-dump-image=var/deptrac_class.png --formatter-graphviz-dump-dot=var/deptrac_class.dot

# We use phpdbg because is part of the core and so that we don't need to install xdebug just to get the coverage.
# Furthermore, phpdbg gives us more info in certain conditions, ie if the memory_limit has been reached.
test_cov:
	ENV='tst' phpdbg -qrr vendor/bin/phpunit --coverage-text --coverage-clover=${COVERAGE_REPORT_PATH}


