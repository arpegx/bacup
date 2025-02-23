image = arpegx/bacup:latest
oci = oci_bacup
phar = build/bacup.phar
extract_dir = build/extract

.PHONY: build run ssh test release extract clean

build:
	podman image rm ${image} 2&>1 > /dev/null || \
	podman build -t ${image} .

run: build
	podman run --name ${oci} -d ${image} -c "sleep infinity"

ssh: build
	podman run -it --name ${oci} ${image}

test: run
	podman exec -t ${oci} "./vendor/bin/pest" && make clean

release: run
	podman exec ${oci} bash -c "./release && chmod u+x ${phar}"

extract: release
	podman exec -t ${oci} -c "mkdir -p ${extract_dir} && php -r '(new Phar(\"${phar}\"))->extractTo(\"${extract_dir}\");' && tree -L 3 build/extract"

clean:
	@podman kill ${oci} 2>&1 > /dev/null && \
	podman rm ${oci} 2>&1 > /dev/null && \
	podman image prune --force