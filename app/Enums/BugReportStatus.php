<?php

namespace App\Enums;

enum BugReportStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case ANSWERED = 'answered';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Jauns',
            self::IN_PROGRESS => 'Izskatīšanā',
            self::ANSWERED => 'Atbildēts',
            self::CLOSED => 'Slēgts',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::NEW => 'bug-status-badge status-new',
            self::IN_PROGRESS => 'bug-status-badge status-in_progress',
            self::ANSWERED => 'bug-status-badge status-answered',
            self::CLOSED => 'bug-status-badge status-closed',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NEW => 'fa-solid fa-circle-dot',
            self::IN_PROGRESS => 'fa-solid fa-clock',
            self::ANSWERED => 'fa-solid fa-reply',
            self::CLOSED => 'fa-solid fa-check',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->label();
        }
        return $array;
    }
}
