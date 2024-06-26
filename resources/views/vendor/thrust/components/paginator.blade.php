@if ( method_exists($data, 'links')  && $data->lastPage() != 1)
    <div class="mt4">
        {{ $data->appends(Illuminate\Support\Arr::except(request()->query(), ['page']))->links('components.pagination') }}
    </div>
    @if(isset($popupLinks))
    <script>
        $(".page-link").on('click', function (e) {
            e.preventDefault();
            loadPopup($(this).attr('href'));
        })
    </script>
    @endif
@endif