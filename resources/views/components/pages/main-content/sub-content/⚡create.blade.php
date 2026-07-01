<?php

use Livewire\Component;

new class extends Component {
    public string $title = '';
};

?>

<div>
    <h1>Create Sub Content</h1>

    <a href="{{ route('sub-content.index') }}" wire:navigate>Back to Index</a>

    <hr>

    <p>This page represents the Create page in the Livewire CRUD structure.weh?</p>

    <input type="text" wire:model.live="title" placeholder="Type something here...">

    <p>Preview: {{ $title ?? '' }}</p>
</div>
