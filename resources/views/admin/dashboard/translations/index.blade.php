@extends('admin.dashboard.master')

@section('main_content')
<style>
.toast-msg {
    min-width: 220px;
    font-weight: 500;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.toast-msg .toast-icon {
    font-size: 1.1rem;
}
</style>

<section role="main" class="content-body" style="background-color:#fff;">
<div class="container-fluid">
    <h1>Translation Management</h1>
    
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group">
                @foreach(['frontend', 'backend', 'validation', 'emails'] as $group)
                <a href="?group={{ $group }}" 
                   class="list-group-item {{ request('group') == $group ? 'active' : '' }}">
                    {{ ucfirst($group) }} Translations
                </a>
                @endforeach
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Translations for {{ request('group', 'frontend') }}</h5>
                    <button class="btn btn-sm btn-primary" onclick="addNewTranslation()">
                        <i class="fa fa-plus"></i> Add New
                    </button>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Key</th>
                                @foreach(available_languages() as $lang)
                                <th>
                                    <span class="flag-icon flag-icon-{{ $lang->flag_icon }}"></span>
                                    {{ $lang->name }}
                                </th>
                                @endforeach
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($translations as $translation)
                            <tr>
                                <td>{{ $translation->key }}</td>
                                @foreach(available_languages() as $lang)
                                <td>
                                    <input type="text"
                                           class="form-control form-control-sm translation-input"
                                           data-id="{{ $translation->id }}"
                                           data-lang="{{ $lang->code }}"
                                           value="{{ $translation->getValue($lang->code) }}">
                                </td>
                                @endforeach
                                <td class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success"
                                            onclick="saveTranslation({{ $translation->id }}, this)">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <button class="btn btn-sm btn-info"
                                            onclick="autoTranslate({{ $translation->id }}, this)">
                                        <i class="fa fa-robot"></i> Auto Translate
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $translations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index:1055;"></div>
</section>

<script>
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-msg p-2 mb-2 rounded shadow d-flex align-items-center gap-2';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.5s, transform 0.5s';
    toast.style.transform = 'translateX(100%)';
    toast.style.color = '#fff';

    let icon = '';
    switch(type) {
        case 'success': toast.style.backgroundColor = '#28a745'; icon='✔️'; break;
        case 'error': toast.style.backgroundColor = '#dc3545'; icon='❌'; break;
        case 'info': toast.style.backgroundColor = '#17a2b8'; icon='ℹ️'; break;
    }
    toast.innerHTML = `<span class="toast-icon">${icon}</span> <span>${message}</span>`;
    toastContainer.appendChild(toast);

    setTimeout(() => { toast.style.opacity='1'; toast.style.transform='translateX(0)'; }, 50);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(100%)'; setTimeout(()=>toast.remove(),500); }, 3000);
}

// Save translation
function saveTranslation(translationId, btn) {
    const inputs = document.querySelectorAll(`.translation-input[data-id="${translationId}"]`);
    const data = {};
    inputs.forEach(input => data[input.dataset.lang] = input.value);

    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

    fetch("{{ route('translations.update') }}", {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify({ translation_id: translationId, translations: data })
    })
    .then(res => {
        if (!res.ok) throw res;
        return res.json();
    })
    .then(data => {
        if (data.success) showToast(data.message ?? 'Translation saved!', 'success');
        else showToast(data.message ?? 'Save failed!', 'error');
    })
    .catch(async (err) => {
        let msg='Server error!';
        try { const json = await err.json(); if(json.message) msg=json.message; } catch{}
        showToast(msg,'error');
    })
    .finally(()=> { btn.disabled=false; btn.innerHTML=originalText; });
}

// Auto-translate
function autoTranslate(translationId, btn) {
    const sourceInput = document.querySelector(`.translation-input[data-id="${translationId}"][data-lang="en"]`);
    if(!sourceInput || !sourceInput.value){ showToast('Please enter English text first','error'); return; }

    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Translating...';

    fetch("{{ route('admin.translations.auto') }}", {
        method:'POST',
        headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify({ translation_id: translationId, source_text: sourceInput.value })
    })
    .then(res => { if(!res.ok) throw res; return res.json(); })
    .then(data => {
        if(data.success){
            Object.keys(data.translations).forEach(lang=>{
                const input=document.querySelector(`.translation-input[data-id="${translationId}"][data-lang="${lang}"]`);
                if(input && data.translations[lang]) input.value=data.translations[lang];
            });
            showToast('Auto-translation completed!','success');
        } else showToast(data.message ?? 'Auto-translation failed!','error');
    })
    .catch(async (err)=>{
        let msg='Server error!';
        try{ const json=await err.json(); if(json.message) msg=json.message; }catch{}
        showToast(msg,'error');
    })
    .finally(()=>{ btn.disabled=false; btn.innerHTML=originalText; });
}
</script>
@endsection
