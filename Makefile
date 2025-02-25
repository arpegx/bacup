image = arpegx/bacup:latest
oci = oci_bacup
phar = build/bacup.phar
extract_dir = build/extract

.PHONY: build run ssh test release extract clean

build: clean
	podman build -t ${image} .

run: build
	podman run --name ${oci} -d ${image} -c "sleep infinity"

ssh: build
	podman run -it --name ${oci} ${image}

test: run
	podman exec -t ${oci} bash -c "./vendor/bin/pest --no-output"

release: run
	podman exec ${oci} bash -c "./release && chmod u+x ${phar}"

extract: release
	podman exec -t ${oci} -c "mkdir -p ${extract_dir} && php -r '(new Phar(\"${phar}\"))->extractTo(\"${extract_dir}\");' && tree -L 3 build/extract"

clean:
	@podman --out=/dev/null kill ${oci}; \
	podman --out=/dev/null stop ${oci}; \
	podman --out=/dev/null rm ${oci}; \
	podman --out=/dev/null image prune --force; \
	podman --out=/dev/null image rm ${image}
