<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <h1>Sub Content Index</h1>
    <p>This is the main fullpage Livewire componentHI.</p>

    <nav>
        <a href="{{ route('sub-content.create') }}" wire:navigate>Create</a> |
        <a href="{{ route('sub-content.view') }}" wire:navigate>View</a> |
        <a href="{{ route('sub-content.edit') }}" wire:navigate>Edit</a> |
        <a href="{{ route('sub-content.delete') }}" wire:navigate>Delete</a>
    </nav>

    <hr>

    <p>Livewire is working if this page loads through a fullpage component.</p>
</div>
