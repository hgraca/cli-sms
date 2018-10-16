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

## Make commands available

- `make cs-fix`       Fixes the coding standards
- `make db-setup`     Creates/resets the DB including adding some fixtures to it
- `make db-inspect`   Shows the contents of the DB
- `make dep-install`  Installs the project dependencies
- `make dep_analyzer-install` Install the tool Deptrac
- `make test-dep-graph` Check that the architectural rules are maintained

## Preparation

Get the project prepared:

```sh=
echo "/bootcamp" >> .gitignore
mkdir bootcamp
vagrant up
vagrant ssh
cd bootcamp
git clone git@gitlab.werkspot.com:bootcamp/cli-sms.git
cd cli-sms
git checkout tags/v1.0.0 -b dev
make dep-install
make db-setup
make db-inspect
make dep_analyzer-install
sudo apt-get install -y graphviz
make test-dep-graph
```

We might need to change the DB path here:
- `/.env:17`
- `/Makefile:50`

The logging is configured to also go to the console.
If that annoys you, you can comment out the config block here:
- `config/packages/dev/monolog.yaml:monolog.handlers.stdout`
