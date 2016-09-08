<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        @foreach($preferences->domains as $domain)
            <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                <a href="#{{ $domain->key }}"
                   aria-controls="home"
                   role="tab"
                   data-toggle="tab"
                >
                    {{ $domain->getDisplayedName() }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        @foreach($preferences->domains as $domain)
            <div role="tabpanel"
                 class="tab-pane {{ $loop->first ? 'active' : '' }}"
                 id="{{ $domain->key }}"
            >
                {!! $domain->view->render() !!}
            </div>
        @endforeach
    </div>

</div>
