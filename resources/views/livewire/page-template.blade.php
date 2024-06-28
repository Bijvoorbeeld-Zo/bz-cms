@push('head')
    <title>{{ config('app.name') . ' - ' . $page->title }}</title>
@endpush

<div>
    @foreach($page->elements as $element)
        @php
            $block = new $element['type']();
        @endphp
        {{ view($block->getView(), ['data' => $element['data']]) }}
    @endforeach
</div>
