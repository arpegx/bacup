image = arpegx/bacup:latest
phar = build/bacup.phar
extract_dir = build/extract

build:
	podman build -t ${image} .

run: build
	podman run --rm -it ${image}

release: clean
	./release && chmod u+x ${phar}

extract: release
	mkdir ${extract_dir} && php -r '(new Phar("${phar}"))->extractTo("${extract_dir}");'

test:
	./vendor/bin/pest

clean:
	rm -rf ./build 2>&1 > /dev/null

.PHONY: build run release extract clean test