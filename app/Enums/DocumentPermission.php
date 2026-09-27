<?php

namespace App\Enums;

enum DocumentPermission: string
{
    case View = 'view';
    case Download = 'download';
    case Edit = 'edit';

    public function label(): string
    {
        return match ($this) {
            self::View => 'View only',
            self::Download => 'View & download',
            self::Edit => 'View, download & upload new versions',
        };
    }

    /**
     * Whether this permission level satisfies a required minimum level,
     * e.g. Edit::atLeast(Download) === true.
     */
    public function atLeast(self $minimum): bool
    {
        $order = [
            self::View->value => 1,
            self::Download->value => 2,
            self::Edit->value => 3,
        ];

        return $order[$this->value] >= $order[$minimum->value];
    }
}
