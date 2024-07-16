@php
    use Carbon\Carbon;

    function blockIsVisible($data): bool {
        if($data['is_hidden']) {
            return false;
        }

        if(isset($data['visible_from']) && Carbon::make($data['visible_from']) > Carbon::now()) {
            return false;
        }

        if(isset($data['visible_until']) && Carbon::make($data['visible_until']) < Carbon::now()) {
            return false;
        }

        return true;
    }
@endphp

@push('head')
    <title>{{ config('app.name') . ' - ' . $page->title }}</title>
@endpush

<div class="w-full">
    @foreach($page->elements as $element)
        @php
            $block = new $element['type']();
            $data = $element['data'];

            if(!blockIsVisible($data)) {
                continue;
            }

            $group_elements = array_filter($page->elements, function($item) use ($data) {
                return $item['data']['group_id'] === $data['group_id'];
            });

            $visible_elements = array_filter($group_elements, function($item) {
                return blockIsVisible($item['data']) && !$item['data']['is_default'];
            });

            if(isset($data['is_default']) && $data['is_default'] && count($visible_elements) > 0) {
                continue;
            }
        @endphp

        {{ view($block->getView(), ['data' => $data]) }}
    @endforeach
</div>
