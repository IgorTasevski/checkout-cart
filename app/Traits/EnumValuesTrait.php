<?php
namespace App\Traits;

trait EnumValuesTrait
{
    /**
     * Get all values from the enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get all names from the enum.
     *
     * @return array
     */
    public static function names(): array
    {
        return array_map(fn($case) => $case->name, self::cases());
    }
}
