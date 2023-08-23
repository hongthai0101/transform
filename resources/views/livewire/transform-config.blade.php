<div>
    <form wire:submit.prevent="store">
        @csrf
        @method('PUT')
        <div class="card">
            <div>
                @if($message !== '')
                    <x-adminlte-alert class="bg-teal text-uppercase" icon="fa fa-lg fa-thumbs-up" title="Done" dismissable>
                        {{$message}}
                    </x-adminlte-alert>
                @endif
                <div class="card-header">
                    <div class="card-title">
                        <x-adminlte-button label="Add Row" theme="success" wire:click="add"/>
                    </div>
                </div>
                <div class="card-body row">
                    @include('livewire.form-config', ['list' => $list, 'isChild' => false])
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    @include('livewire.transform-test', ['list' => $list, 'isChild' => false])
</div>
