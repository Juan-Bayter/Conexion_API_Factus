<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CrearFactura extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.crear-factura';
}
