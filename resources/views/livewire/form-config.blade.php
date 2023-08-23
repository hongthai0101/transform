@foreach($list as $k => $item)
    @php
        $livewireKeyArr = explode('.', $item['livewireKey']);
        $livewireKey = array_pop($livewireKeyArr);
        $html = '';
        for ($i = 0; $i < count($livewireKeyArr) / 2; $i++) {
            $html .= '<i class="fas fa-step-forward"></i>';
        }
    @endphp
        <div class="form-group col-2">
            @if(!$isChild && $type == 'request')
                <x-adminlte-select
                    name="position"
                    label="Position"
                    wire:model.defer="list.{{$item['livewireKey']}}.position"
                    igroup-size="sm"
                >
                    @foreach(config('transform.position') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-adminlte-select>
            @endif

            @if ($isChild)
                <div class="form-group" style="float: right">
                <label>&nbsp;</label> <br>
                    {!! $html !!}
                </div>
            @endif
        </div>
        <div class="form-group col-2">
            <x-adminlte-select
                name="data_type"
                label="Data Type"
                wire:model.defer="list.{{$item['livewireKey']}}.data_type"
                igroup-size="sm"
            >
                @foreach(config('transform.data_type') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="form-group col-3">
            <x-adminlte-input
                name="from_key[]"
                placeholder="Enter From Key"
                label="From Key"
                enableOldSupport="true"
                wire:model.defer="list.{{$item['livewireKey']}}.from_key"
                required
                igroup-size="sm"
            />
        </div>
        <div class="form-group col-3">
            <x-adminlte-input
                name="to_key[]"
                placeholder="Enter To Key"
                label="To Key"
                enableOldSupport="true"
                wire:model.defer="list.{{$item['livewireKey']}}.to_key"
                required
                igroup-size="sm"
            />
        </div>
        <div class="form-group col-2">
            <div class="form-group">
                <label>&nbsp;</label> <br>
                <x-adminlte-button class="btn-sm" label="Remove" theme="danger" wire:click="remove('{{$item['key']}}', {{$isChild}})"/>
                <x-adminlte-button class="btn-sm" label="Add Child" theme="success" wire:click="addChild('{{$item['key']}}')"/>
            </div>
        </div>
    @if(isset($item['child']) && !empty($item['child']))
        @include('livewire.form-config', ['list' => $item['child'], 'isChild' => true])
    @endif
@endforeach
