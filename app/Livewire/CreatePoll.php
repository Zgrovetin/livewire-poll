<?php

namespace App\Livewire;

use App\Models\Poll;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePoll extends Component
{
    #[Validate('required', message: 'Please provide a Poll title')]
    #[Validate('min:3', message: 'This title is too short')]
    #[Validate('max:300', message: 'This title is too long')]
    public $title;

    #[Validate([
        'options' => 'required|array|min:1|max:10',
        'options.*' => 'required|min:1|max:255'
    ], message: [
        'options.required' => 'Enter at least 1 option.',
        'options.min' => 'You must have at least one option.',
        'options.max' => 'You cannot enter more than 10 options.',
        'options.*.required' => 'The option name is missing.',
        'options.*.min' => 'The option name must be at least 1 character in length.',
        'options.*.max' => 'The option name must be no more than 255 characters in length.',
        ])]
    public $options = ['First'];


    public function render()
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll()
    {
        $this->validate();

        Poll::create([
           'title' => $this->title
        ])->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
            );

        $this->reset(['title', 'options']);

        $this->dispatch('pollCreated');
    }

//    public function mount()
//    {
//
//    }
}
