<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FactusToken;
use App\Services\AuthFactusService;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthApiFactusMiddleware;

class Facturas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.facturas';
}
