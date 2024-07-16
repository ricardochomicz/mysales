<div>
    <x-text-area name="content" id="content" wire:model="content"></x-text-area>

    <button type="button" class="btn btn-primary btn-sm mt-1" wire:click="comment">Comentar</button>

    <div class="comment p-2" style="margin: 0 auto; max-height: calc(50vh - 100px); overflow-y: auto;">
        @foreach($comments as $comment)
        <div >
            <div >

                <small class="float-right mr-3">
                    {{Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i')}}
                </small>
                <p class="font14">{{$comment->content}}</p>
                <hr/>
            </div>
        </div>
        @endforeach
    </div>
</div>
