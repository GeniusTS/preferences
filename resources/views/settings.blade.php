@if(version_compare($version, '5.3.0') < 0)
    @include('geniusts_preferences::settings_5_2')
@else
    @include('geniusts_preferences::settings_5_3')
@endif
