<?php

namespace App\Livewire\Pages\Orders\OrderComments;

use App\Models\Comment;
use Livewire\Component;

class FormComment extends Component
{
    public $opportunity;
    public string $content;

    public function mount($opportunity)
    {
        $this->opportunity = $opportunity;
    }

    public function render()
    {
        $comments = Comment::where('tenant_id', auth()->user()->tenant->id)
            ->where('opportunity_id', $this->opportunity)
            ->where('type', 'order')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('livewire.pages.orders.order-comments.form-comment',[
            'comments' => $comments
        ]);
    }

    public function comment()
    {
        Comment::create([
            'tenant_id' => auth()->user()->tenant->id,
            'opportunity_id' => $this->opportunity,
            'user_id' => auth()->user()->id,
            'type' => 'order',
            'content' => $this->content
        ]);
    }
}
