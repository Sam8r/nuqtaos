<?php

namespace App\Filament\Custom;

use Filament\Infolists\Components\TextEntry as BaseTextEntry;

class TextEntry extends BaseTextEntry
{
    protected function setUp(): void
    {
        parent::setUp();

        // Customize TextEntry value to use primary color
        $this->color('primary');
    }
}
