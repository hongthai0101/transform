<div class="card">
    <div class="card-header">
        <h3 class="card-title">Test Transform Data @if(!$validInput) (<span style="color: red">{{ $messageInput }}</span>) @endif</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <x-adminlte-textarea class="{{ $validInput ? 'is-valid' : 'is-invalid' }}" name="taMsg" label="Input" rows=5 igroup-size="sm" wire:model.defer="inputs"
                                     label-class="text-primary" placeholder="Enter input data..." disable-feedback rows="15">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-lg fa-comment-dots text-primary"></i>
                        </div>
                    </x-slot>
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="primary" label="Transform" wire:click="transform"/>
                    </x-slot>
                </x-adminlte-textarea>
            </div>
            <div class="col-6">
                <x-adminlte-textarea name="taMsg" label="Output" rows=5 igroup-size="sm" wire:model.defer="outputs"
                                     label-class="text-success" placeholder="waiting output data..." disabled="" rows="15">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-lg fa-comment-dots text-primary"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </div>
        </div>
    </div>

</div>


