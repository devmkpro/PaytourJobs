<?php

describe('Simple Tests', function () {
    it('can run a basic test', function () {
        expect(true)->toBeTrue();
    });

    it('can test basic math', function () {
        expect(2 + 2)->toBe(4);
    });

    it('can test arrays', function () {
        $array = [1, 2, 3];
        expect($array)->toHaveCount(3)
            ->and($array[0])->toBe(1);
    });
});
