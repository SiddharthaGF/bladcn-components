<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Tests\Support;

use AiluraCode\Bladcn\Support\ClassResolver;
use PHPUnit\Framework\TestCase;

final class ClassResolverTest extends TestCase
{
    public function test_add_builds_class_string(): void
    {
        $classes = (new ClassResolver())
            ->add('px-2')
            ->add('py-1')
            ->add(null);

        $this->assertSame('px-2 py-1', (string) $classes);
    }

    public function test_add_skips_false_empty_and_non_string_values(): void
    {
        $classes = (new ClassResolver())
            ->add(false)
            ->add('')
            ->add('   ')
            ->add(123)
            ->add('rounded');

        $this->assertSame('rounded', (string) $classes);
    }

    public function test_add_trims_whitespace(): void
    {
        $classes = (new ClassResolver())->add('  px-2  ');

        $this->assertSame('px-2', (string) $classes);
    }

    public function test_as_child_escapes_class_attribute(): void
    {
        $resolver = new ClassResolver;

        $this->assertSame(
            'foo&quot; bar',
            $resolver->asChild(['class' => 'foo" bar']),
        );
    }

    public function test_as_child_casts_scalar_class_values(): void
    {
        $resolver = new ClassResolver;

        $this->assertSame('42', $resolver->asChild(['class' => 42]));
    }

    public function test_as_child_returns_empty_string_when_class_is_missing(): void
    {
        $resolver = new ClassResolver;

        $this->assertSame('', $resolver->asChild());
    }
}
