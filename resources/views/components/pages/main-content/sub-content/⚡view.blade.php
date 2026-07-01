<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <h1>View Sub Content</h1>

    <a href="{{ route('sub-content.index') }}" wire:navigate>Back to Index</a>

    <hr>

    <p>This page represents the View page in the Livewire CRUD structure.</p>
</div>