<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/sub-content', 'pages.main-content.sub-content.index')
    ->name('sub-content.index');

Route::livewire('/sub-content/create', 'pages.main-content.sub-content.create')
    ->name('sub-content.create');

Route::livewire('/sub-content/view', 'pages.main-content.sub-content.view')
    ->name('sub-content.view');

Route::livewire('/sub-content/edit', 'pages.main-content.sub-content.edit')
    ->name('sub-content.edit');

Route::livewire('/sub-content/delete', 'pages.main-content.sub-content.delete')
    ->name('sub-content.delete');