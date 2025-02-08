build:
	podman build -t arpegx/bacup:latest .

run:
	podman run -it localhost/arpegx/bacup

release:
	./release && chmod u+x ./build/bacup.phar

clean:
	rm -rf ./build 2>&1 > /dev/null