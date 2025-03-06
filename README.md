<pre>
                          __                                         
                         /\ \                                        
                         \ \ \____     __      ___   __  __  _____   
                          \ \ '__`\  /'__`\   /'___\/\ \/\ \/\ '__`\ 
                           \ \ \L\ \/\ \L\.\_/\ \__/\ \ \_\ \ \ \L\ \
                            \ \_,__/\ \__/.\_\ \____\\ \____/\ \ ,__/
                             \/___/  \/__/\/_/\/____/ \/___/  \ \ \/ 
                                                               \ \_\ 
                                                                \/_/ 

</pre>

Backup CLI written based on Laravel Prompts

# Setup

For safety reasons it is recommended to run and develop this application in a sandboxed environment, for though syscalls involving generic deletions are executed.

Therefore a **Containerfile** is provided expecting the usage of **Podman** to build the application on top of the already existing image php:8.2-cli . To simplify the workflow of continuoues rebuilding and testing, a **Makefile** is provided.

**Prerequisites**:

- composer
- make
- podman

## Get Started

To get the repository up and running just clone it and install all dependencies via composer as well generating your autoloader.

```shell
composer install && composer dump-autoload -o
```

To build your image and get a running container the Makefile provides the target run

```shell
make run
```

at top of the Makefile various variables let you define the build process, which you should at least take a look at to not get in naming collisions.

# Workflow

The way the Makefile provides targets it is expected that you are in charge of either build, run or kill and prune a container. **Rebuild** the image and provide a running container via

```shell
make run
```

For **accessing** the container you can use

```shell
make ssh
```

on an already running container. All changes done on the host system are “swapped” into the containers workdir via the update target each time you are using ssh access or execute the testing target. This way there is a guarantee that host and container are in sync on execution, while skipping the time-intensive build process.

**Testing** might be done either remotely via

```shell
make test
```

or for **debugging** : first open the container , example given… in VSCode configured to “Listen for Xdebug” via Dev Containers Extension and run directly

```shell
./vendor/bin/pest --no-output --dont-report-useless-tests --coverage --profile
```

when your all done execute to **free** your resources

```shell
make clean
```

# Thanks

for every feedback, all pieces of advice,inpiring user stories you might have and last but not least to

Laravel Prompts https://github.com/laravel/prompts … beautiful and user-friendly

Podman : https://podman.io/

and all those other people making software public.
