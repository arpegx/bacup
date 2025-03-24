<?php

declare(strict_types=1);

test("Architecture: Track::handle fn exists", function () {
    expect('Arpegx\Bacup\Command\Track')->toHaveMethod('handle');
});
