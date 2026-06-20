<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Tests\Support;

use AiluraCode\Bladcn\Support\Toast;
use AiluraCode\Bladcn\Tests\BladcnTestCase;
use Illuminate\Support\Facades\Session;

final class ToastTest extends BladcnTestCase
{
    public function test_make_builds_payload_with_defaults(): void
    {
        $this->assertSame([
            'title' => 'Saved',
            'variant' => 'default',
        ], Toast::make('Saved'));
    }

    public function test_make_includes_optional_fields(): void
    {
        $this->assertSame([
            'title' => 'Saved',
            'variant' => 'success',
            'description' => 'All good',
            'position' => 'top-right',
            'duration' => 5000,
        ], Toast::make('Saved', [
            'variant' => 'success',
            'description' => 'All good',
            'position' => 'top-right',
            'duration' => 5000,
        ]));
    }

    public function test_make_ignores_invalid_optional_fields(): void
    {
        $this->assertSame([
            'title' => 'Saved',
            'variant' => 'default',
        ], Toast::make('Saved', [
            'variant' => 123,
            'description' => 456,
            'position' => false,
            'duration' => '4000',
        ]));
    }

    public function test_flash_stores_payload_in_session(): void
    {
        Toast::flash('Saved', ['description' => 'All good']);

        $this->assertSame([
            'title' => 'Saved',
            'variant' => 'default',
            'description' => 'All good',
        ], Session::get(Toast::SESSION_KEY));
    }

    public function test_from_session_returns_payload(): void
    {
        Toast::flash('Saved', ['variant' => 'success']);

        $this->assertSame([
            'title' => 'Saved',
            'variant' => 'success',
        ], Toast::fromSession());
    }

    public function test_from_session_returns_null_when_missing(): void
    {
        $this->assertNull(Toast::fromSession());
    }

    public function test_from_session_returns_null_for_non_array_value(): void
    {
        Session::put(Toast::SESSION_KEY, 'invalid');

        $this->assertNull(Toast::fromSession());
    }

    public function test_success_flashes_success_variant(): void
    {
        Toast::success('Done');

        $this->assertSame([
            'title' => 'Done',
            'variant' => 'success',
        ], Toast::fromSession());
    }

    public function test_info_flashes_info_variant(): void
    {
        Toast::info('Heads up');

        $this->assertSame([
            'title' => 'Heads up',
            'variant' => 'info',
        ], Toast::fromSession());
    }

    public function test_warning_flashes_warning_variant(): void
    {
        Toast::warning('Careful');

        $this->assertSame([
            'title' => 'Careful',
            'variant' => 'warning',
        ], Toast::fromSession());
    }

    public function test_error_flashes_destructive_variant(): void
    {
        Toast::error('Failed');

        $this->assertSame([
            'title' => 'Failed',
            'variant' => 'destructive',
        ], Toast::fromSession());
    }
}
