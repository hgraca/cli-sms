# CLI SMS

A project to create a small CLI application to guide and experiment with:

- Creating a project structure that reflects the architecture
   - Ports & Adapters Arch.
   - Onion Arch.
   - Screaming Arch.
- DDD artefacts implementation
   - Entities
   - ValueObjects
   - Events
   - Repositories
   - QueryObjects
   - ...
- DDD modelling
   - Context Map
   - Bounded contexts
   - Core Domain
   - Supporting Subdomains
   - Generic Subdomains
   - Shared Kernel
   - Anti-Corruption Layer
   - ...

Find the guide at [https://hackmd.io/sgPXPuP8Tiq0-mFt1nNPsQ](https://hackmd.io/sgPXPuP8Tiq0-mFt1nNPsQ)

## Most relevant Make commands available at the moment of this writing

These commands are ll run from the host, but execute inside a docker container.

If you want to login to the docker container shell and run these commands from there, you to add a dot (.) before the target name.

```shell
   help                           Show this help
   shell                          Start the docker container and get a shell inside
   setup                          Install all project dependencies for development and testing
   cs-fix                         Fix coding standards
   db-migrate                     Run the migrations
   db-setup                       Setup the initial DB
   db-inspect                     Show the contents of the DB
   dep-install                    Install all dependencies, including dev dependencies
   test                           Run all tests
   test-dep                       Test the architecture dependencies
   test-dep-graph                 Test the architecture dependencies and generate graphs
   test-unit                      Run unit tests
   coverage                       Run unit tests, and generate coverage report
```

## Preparation

If you are on Mac, you might need to install Xcode Command-line Tools: `xcode-select --install`

```shell
git clone https://github.com/hgraca/cli-sms.git
cd cli-sms
make setup
```
At the end of the setup, you will be shown an IP address. Save it for later.

## Integration with PHPStorm

Configure PHPStorm according to the screenshots in `docs/IDE/PHPStorm`.

If you want, you can try using my own PHPStorm settings: `docs/IDE/PHPStorm/settings.zip`.

### Setting up Xdebug

You need to set the Docker host IP, given at the end of the setup, in config 'xdebug.remote_host'
in files 'build/container/xdebug.ini' and 'build/container/docker-compose.yml', so that Xdebug can connect to your IDE.

You can turn Xdebug off/on by commenting/uncommenting the first line in the file `build/container/xdebug.ini`.

Xdebug should work when you set a break point on `tests/TestCase/Presentation/Console/Component/User/AddUserCliCommandTest.php:47`
and run the tests either using the PHPStorm toolbar "Debug" button, or when running `make test-unit`.

In linux, it might be needed to open port 9003 for xdebug to connect to PHPStorm:

```shell
sudo iptables -A INPUT -p tcp -d 0/0 -s 0/0 --dport 9003 -j ACCEPT
```

### Permission issues
If the files created in the container are not owned by you on the host, you can need to
duplicate the file `Makefile.defaults.mk`, name it `Makefile.defaults.custom.mk`, and change the
variable HOST_USER_ID to the correct value.
This way the files will be created by the same user both on the host and the
guest, avoiding permissions issues.

### DB path
If you want to change the DB path, you can change it here:
- `/.env:26`
- `/Makefile.defaults.mk:4`

## Logging

The logging is configured to also go to the console, for level NOTICE and above.

You can tweak it in `config/packages/dev/monolog.yaml:monolog.handlers.stdout.level`.

It is also configured to go to the file `var/log/dev.log`, for level DEBUG and above.

You can tweak it in `config/packages/dev/monolog.yaml:monolog.handlers.main`.
