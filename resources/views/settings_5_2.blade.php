<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php $x = 0 ?>
        @foreach($preferences->domains as $domain)
            <li role="presentation" class="{{ $x === 0 ? 'active' : '' }}">
                <a href="#{{ $domain->key }}"
                   aria-controls="home"
                   role="tab"
                   data-toggle="tab"
                >
                    {{ $domain->getDisplayedName() }}
                </a>
            </li>
            <?php $x++ ?>
        @endforeach
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <?php $x = 0 ?>
        @foreach($preferences->domains as $domain)
            <div role="tabpanel"
                 class="tab-pane {{ $x === 0 ? 'active' : '' }}"
                 id="{{ $domain->key }}"
            >
                @foreach($domain->elements as $element)
                    {!! $element->view->render() !!}
                @endforeach
            </div>
            <?php $x++ ?>
        @endforeach
    </div>

</div>
