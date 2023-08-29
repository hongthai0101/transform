<div>
    @if($message !== '')
        <x-adminlte-alert class="bg-teal text-uppercase" icon="fa fa-lg fa-thumbs-up" title="Done" dismissable>
            {{$message}}
        </x-adminlte-alert>
    @endif
    @if($transform->transform_type === 'xml')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h5>Transform Element Root</h5>
            </div>
        </div>
            <div class="card-body">
                <div class="form-group">
                    <x-adminlte-input
                        name="metadata['transform_element_root']"
                        placeholder="Transform Element Root"
                        wire:model.defer="metadata.transform_element_root"
                    />
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" wire:click="saveMetadata">Save</button>
            </div>
    </div>
    @endif
    <form wire:submit.prevent="store">
        @csrf
        @method('PUT')
        <div class="card">
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
    </form>
    @include('livewire.transform-test', ['list' => $list, 'isChild' => false])
</div>
