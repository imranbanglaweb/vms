@php
    use App\Models\Language;
    use Illuminate\Support\Facades\Cache;

    $currentLocale = app()->getLocale();

    $languages = Cache::remember('available_languages', 3600, function () {
        return Language::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    });

    $currentLanguage = $languages->where('code', $currentLocale)->first()
        ?? $languages->where('is_default', 1)->first()
        ?? $languages->first();
@endphp

@if($languages->count() > 1)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center"
       href="#"
       id="languageDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">

        <span class="fi fi-{{ $currentLanguage->flag_icon ?? 'us' }} me-1"></span>
        <span class="d-none d-md-inline fw-semibold">
            {{ strtoupper($currentLocale) }}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow"
        aria-labelledby="languageDropdown">

        <li class="dropdown-header">
            <i class="fa fa-language me-2"></i> Select Language
        </li>

        @foreach($languages as $language)
            @if($language->code !== $currentLocale)
                <li>
                    <a class="dropdown-item d-flex align-items-center"
                       href="#"
                       onclick="switchLanguage('{{ $language->code }}'); return false;">

                        <span class="fi fi-{{ $language->flag_icon }} me-2"></span>

                        <span class="flex-grow-1">
                            {{ $language->native_name }}
                            <small class="text-muted ms-1">
                                ({{ strtoupper($language->code) }})
                            </small>
                        </span>

                        @if($language->direction === 'rtl')
                            <span class="badge bg-info ms-2">RTL</span>
                        @endif
                    </a>
                </li>
            @else
                <li>
                    <span class="dropdown-item active d-flex align-items-center">
                        <span class="fi fi-{{ $language->flag_icon }} me-2"></span>
                        <strong class="flex-grow-1">
                            {{ $language->native_name }}
                        </strong>
                        <i class="fas fa-check text-success"></i>
                    </span>
                </li>
            @endif
        @endforeach

        <li><hr class="dropdown-divider"></li>

        <li>
            <a class="dropdown-item" href="{{ route('admin.translations') }}">
                <i class="fas fa-cog me-2"></i> Manage Translations
            </a>
        </li>
    </ul>
</li>
@endif

{{-- ===================== --}}
{{-- Toast Container --}}
{{-- ===================== --}}
<div id="toastContainer"
     class="toast-container position-fixed top-0 end-0 p-3"
     style="z-index: 9999;"></div>

{{-- ===================== --}}
{{-- JavaScript --}}
{{-- ===================== --}}

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (for AJAX only) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Font Awesome 6 -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
function switchLanguage(locale) {
    $.ajax({
        url: "{{ route('admin.language.switch') }}",
        method: "POST",
        data: {
            language: locale,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.success) {
                showToast('Language switched successfully!', 'success');
                setTimeout(() => window.location.reload(), 700);
            } else {
                showToast(res.message || 'Failed to switch language', 'error');
            }
        },
        error: function () {
            showToast('Server error while switching language', 'error');
        }
    });
}

function showToast(message, type = 'info') {
    const toastEl = document.createElement('div');

    toastEl.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : type} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');

    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    `;

    document.getElementById('toastContainer').appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}
</script>

{{-- ===================== --}}
{{-- Styles --}}
{{-- ===================== --}}
<style>
.language-dropdown .dropdown-menu {
    animation: fadeDown 0.25s ease;
}

@keyframes fadeDown {
    from {
        opacity: 0;
        transform: translateY(-6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fi {
    width: 20px;
    height: 15px;
    border-radius: 2px;
}

.dropdown-item:hover {
    background: linear-gradient(90deg, #f8f9fa, #e9ecef);
}

.dropdown-item.active {
    background: linear-gradient(90deg, #e3f2fd, #bbdefb);
    font-weight: 600;
}
</style>
