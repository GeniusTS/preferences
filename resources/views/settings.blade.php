<form method="POST" action="{{ action('SettingsController@update') }}">
    @if($errors->count())
        <div class="alert alert-danger alert-dismissible fade in">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

            <ul>
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <input type="hidden" name="_method" value="PATCH"/>
    {!! csrf_field() !!}

    @if(version_compare($version, '5.3.0') < 0)
        @include('geniusts_preferences::settings_5_2')
    @else
        @include('geniusts_preferences::settings_5_3')
    @endif

    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </div>
    </div>
</form>
