@php
    use App\Models\Language;
    use Illuminate\Support\Facades\Cache;

    $currentLocale = app()->getLocale();

    $languages = Cache::remember('available_languages', 3600, function () {
        return Language::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    });

    $currentLanguage = $languages->where('code', $currentLocale)->first()
        ?? $languages->where('is_default', 1)->first()
        ?? $languages->first();
@endphp

@if($languages->count() > 1)
<li class="dropdown">
    <a href="#"
       class="dropdown-toggle"
       data-toggle="dropdown"
       role="button"
       aria-expanded="false">

        <span class="fi fi-{{ $currentLanguage->flag_icon ?? 'us' }}"></span>
        <span class="hidden-xs">{{ strtoupper($currentLocale) }}</span>
        <i class="fa fa-angle-down"></i>
    </a>

    <ul class="dropdown-menu">
        <li class="dropdown-header">
            <i class="fa fa-language"></i> Select Language
        </li>

        @foreach($languages as $language)
            @if($language->code !== $currentLocale)
                <li>
                    <a href="javascript:void(0)"
                       class="language-switch"
                       data-lang="{{ $language->code }}">

                        <span class="fi fi-{{ $language->flag_icon }}"></span>
                        {{ $language->native_name }}
                        <small class="text-muted">({{ strtoupper($language->code) }})</small>

                        @if($language->direction === 'rtl')
                            <span class="label label-info pull-right">RTL</span>
                        @endif
                    </a>
                </li>
            @else
                <li class="active">
                    <a href="#">
                        <span class="fi fi-{{ $language->flag_icon }}"></span>
                        <strong>{{ $language->native_name }}</strong>
                        <i class="fa fa-check text-success pull-right"></i>
                    </a>
                </li>
            @endif
        @endforeach

        <li class="divider"></li>

        <li>
            <a href="{{ route('admin.translations') }}">
                <i class="fa fa-cog"></i> Manage Translations
            </a>
        </li>
    </ul>
</li>
@endif
	<!-- Core JS Files - Use only one version of jQuery -->
	<script src="{{ asset('public/admin_resource/assets/vendor/jquery/jquery.js') }}"></script>
	<!-- <script src="{{ asset('public/admin_resource/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script> -->
<script>
$(function () {

    // VERY IMPORTANT: stop Bootstrap dropdown auto-close
    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    // Language click
    $(document).on('click', '.language-switch', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var locale = $(this).data('lang');

        $.ajax({
            url: "{{ route('admin.language.switch') }}",
            type: "POST",
            data: {
                language: locale,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.success) {
                    notify('Language switched successfully!', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 600);
                } else {
                    notify(res.message || 'Failed to switch language', 'danger');
                }
            },
            error: function () {
                notify('Server error while switching language', 'danger');
            }
        });
    });

});

// Porto notify
function notify(message, type) {
    if ($.notify) {
        $.notify({ message: message }, {
            type: type,
            delay: 2000,
            placement: { from: "top", align: "right" }
        });
    } else {
        alert(message);
    }
}
</script>
