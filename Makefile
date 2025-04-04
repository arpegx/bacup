# definitions
image = arpegx/bacup:latest
oci = oci_bacup
phar = build/bacup.phar
extract_dir = build/extract
command = 'rm -rf ~/.config/bacup && ./bacup init 2>&1 > /dev/null && ./bacup track target="app"'

.PHONY: build run ssh update test release clean

build: clean
	podman build -t ${image} .

run: build
	podman run --network=host -p 9003:9003 --replace --name ${oci} -d ${image} -c "sleep infinity"

ssh: update
	@podman exec -it ${oci} bash;

exec: update
	@podman exec -it ${oci} bash -c ${command}

update: 
	@podman exec -t ${oci} bash -c "rm -rf /usr/src/bacup/*" && \
	podman cp . ${oci}:/usr/src/

test: update
	@podman exec -t ${oci} bash -c "./vendor/bin/pest --no-output --dont-report-useless-tests --coverage --profile"

release: run
	podman exec ${oci} bash -c "./release && chmod u+x ${phar}"; \
	podman exec -t ${oci} -c "mkdir -p ${extract_dir} && php -r '(new Phar(\"${phar}\"))->extractTo(\"${extract_dir}\");' && tree -L 3 build/extract"; \

clean:
	@podman --out=/dev/null kill ${oci}; \
	podman --out=/dev/null rm ${oci} --force; \
	podman --out=/dev/null image prune --force; \
	podman --out=/dev/null image rm ${image} --force 