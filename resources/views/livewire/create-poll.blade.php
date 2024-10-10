<div>
    <form>
        <label>Poll title</label>

        <input type="text" wire:model.live="title" />

        Current title: {{ $title }}
    </form>
</div>
