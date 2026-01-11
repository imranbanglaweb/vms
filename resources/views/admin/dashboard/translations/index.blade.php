@extends('admin.dashboard.master')

@section('main_content')

<style>
/* ================= PREMIUM UI ================= */
body { color:#000; font-family: 'Segoe UI', sans-serif; }
.table { border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
.table th, .table td { font-weight:600; font-size:15px; vertical-align:middle !important; padding:12px 10px; }
.table thead { background:#f5f5f5; }
.row-checkbox { width:20px; height:20px; margin:auto; cursor:pointer; }
.row-highlight { background:#f0fff0 !important; transition: background 0.4s ease; }
.btn-premium { font-weight:700; font-size:14px; color:#fff !important; border-radius:6px; padding:6px 12px; transition: all 0.3s ease; }
.btn-premium:hover { opacity:0.9; }
.btn-primary-premium { background:#007bff; }
.btn-success-premium { background:#28a745; }
.btn-warning-premium { background:#ffc107; }
.translation-input { font-weight:600; font-size:14px; color:#000; border:1px solid #ddd; border-radius:4px; padding:6px; width:100%; transition: all 0.3s ease; }
.translation-input.changed { border-color:#28a745; background:#f4fff6; }
.loader-row td { text-align:center; padding:40px; font-size:16px; color:#555; }
button:disabled { opacity:.6; cursor:not-allowed; }
.pagination li { display:inline-block; margin:0 3px; }
.pagination li a { padding:5px 10px; border:1px solid #ddd; cursor:pointer; text-decoration:none; border-radius:4px; }
.pagination li.active a { background:#007bff; color:#fff; border-color:#007bff; }
/* Modal Overlay */
.modal-overlay { position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); z-index: 9999; display:flex; justify-content:center; align-items:center; }
/* Modal Container */
.modal-container { background:#fff; border-radius:8px; padding:20px; width:400px; max-width:90%; box-shadow:0 4px 12px rgba(0,0,0,0.25); animation:fadeIn 0.3s ease; }
/* Modal Header */
.modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; }
.modal-header h3 { margin:0; font-weight:700; }
.modal-close { cursor:pointer; font-size:22px; }
/* Modal Body */
.modal-body { max-height:400px; overflow-y:auto; margin-bottom:15px; }
.modal-body .form-group { margin-bottom:10px; }
.modal-body label { font-weight:600; }
/* Modal Footer */
.modal-footer { display:flex; justify-content:flex-end; gap:10px; }
@keyframes fadeIn { from{opacity:0; transform:translateY(-20px);} to{opacity:1; transform:translateY(0);} }
.page-header{
    /* margin-bottom:20px; */
    padding: 0 20px;
}
.page-header h1{
    font-size:28px;
    font-weight:700;
    color:#fff;
    padding-top:6px;
    margin:0;
}
</style>

<section class="content-body">
<div class="main-container">

<div class="page-header mb-3">
    <h1><i class="fa fa-language"></i> Translation Management</h1>
</div>

{{-- HEADER --}}
<div class="content-header d-flex justify-content-between align-items-center mb-3">
    <h2 class="content-title"><i class="fa fa-list"></i> Translations</h2>
    <div class="action-buttons d-flex gap-2">
        <input type="text" id="search-input" class="form-control" placeholder="Search translation..." style="width:280px;font-weight:700;">
        <button class="btn-premium btn-primary-premium" onclick="addNewTranslation()"><i class="fa fa-plus"></i> ADD</button>
        <button class="btn-premium btn-success-premium" onclick="loadAllTranslations()"><i class="fa fa-download"></i> Load All</button>
        <button class="btn-premium btn-warning-premium" onclick="bulkAutoTranslate()"><i class="fa fa-language"></i> Bulk Auto-Translate</button>
    </div>
</div>

{{-- BULK ACTIONS --}}
<div class="bulk-actions d-flex align-items-center gap-3 mb-3">
    <label>
        <input type="checkbox" id="select-all"> Select All
    </label>
    <button class="btn-premium btn-success-premium" id="bulk-save-btn" onclick="bulkSave()" disabled>
        <i class="fa fa-save"></i> BULK SAVE
    </button>
</div>

{{-- TABLE --}}
<div class="translation-table">
<table class="table table-bordered">
<thead>
<tr>
    <th width="60">✔</th>
    <th>Key</th>
    <th width="120">Group</th>
    @foreach(available_languages() as $lang)
        <th>{{ strtoupper($lang->code) }}</th>
    @endforeach
    <th width="180">Action</th>
</tr>
</thead>
<tbody id="translations-table-body">
<tr class="loader-row">
    <td colspan="100%"><i class="fa fa-spinner fa-spin"></i><br>Loading translations...</td>
</tr>
</tbody>
</table>
</div>

<div id="pagination-wrapper" class="pagination-wrapper mt-3"></div>
</div>

<!-- ================= ADD TRANSLATION MODAL ================= -->
<div id="addTranslationModal" class="modal-overlay" style="display:none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Add New Translation</h3>
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Key <span style="color:red;">*</span></label>
                <input type="text" id="new-key" class="form-control" placeholder="Enter translation key">
            </div>
            <div class="form-group">
                <label>Group</label>
                <input type="text" id="new-group" class="form-control" placeholder="Enter group (optional)">
            </div>
            @foreach(available_languages() as $lang)
            <div class="form-group">
                <label>{{ strtoupper($lang->code) }} Value</label>
                <input type="text" class="form-control new-lang-input" data-lang="{{ $lang->code }}" placeholder="Enter {{ strtoupper($lang->code) }}">
            </div>
            @endforeach
        </div>
        <div class="modal-footer">
            <button class="btn-premium btn-success-premium" onclick="submitNewTranslation()"><i class="fa fa-save"></i> Save</button>
            <button class="btn-premium btn-primary-premium" onclick="closeAddModal()">Cancel</button>
        </div>
    </div>
</div>

</section>

<script>
let selected = new Set();
let changed  = new Set();

/* ================= MODAL ================= */
function addNewTranslation(){
    document.getElementById('addTranslationModal').style.display='flex';
    document.getElementById('new-key').value='';
    document.getElementById('new-group').value='';
    document.querySelectorAll('.new-lang-input').forEach(i=>i.value='');
}
function closeAddModal(){ document.getElementById('addTranslationModal').style.display='none'; }
function submitNewTranslation(){
    const key = document.getElementById('new-key').value.trim();
    const group = document.getElementById('new-group').value.trim();
    if(!key){ alert('Key is required'); return; }
    let translations = {};
    document.querySelectorAll('.new-lang-input').forEach(i=>translations[i.dataset.lang]=i.value.trim());
    const btn = event.target; btn.disabled = true;
    fetch(`{{ route('translations.create') }}`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({ key, group, translations })
    }).then(res=>res.json()).then(res=>{
        if(res.success){ closeAddModal(); prependRow(res.translation); alert('Translation added successfully!'); }
        else alert(res.message||'Error creating translation');
    }).finally(()=>{ btn.disabled=false; });
}

/* ================= AJAX LOAD ================= */
document.addEventListener('DOMContentLoaded', () => {
    loadTranslations();
    document.getElementById('search-input').addEventListener('keyup', debounce(()=>loadTranslations(1), 400));
    document.getElementById('select-all').addEventListener('change', function(){
        document.querySelectorAll('.row-checkbox').forEach(cb=>{ cb.checked=this.checked; toggleSelect(cb.value, cb.checked); });
    });
});

function loadTranslations(page=1){
    const q = document.getElementById('search-input').value;
    const body = document.getElementById('translations-table-body');
    body.innerHTML = `<tr class="loader-row"><td colspan="100%"><i class="fa fa-spinner fa-spin"></i><br>Loading translations...</td></tr>`;
    fetch(`{{ route('admin.translations.ajax') }}?page=${page}&search=${encodeURIComponent(q)}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(res=>res.json())
    .then(res=>{ renderRows(res.translations||[]); renderPagination(res.pagination||null); })
    .catch(()=>{ body.innerHTML=`<tr><td colspan="100%" class="text-center">Error loading translations</td></tr>`; });
}

function loadAllTranslations(){
    const body = document.getElementById('translations-table-body');
    body.innerHTML = `<tr class="loader-row"><td colspan="100%"><i class="fa fa-spinner fa-spin"></i><br>Loading all translations...</td></tr>`;
    fetch(`{{ route('admin.translations.ajax') }}?all=1`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(res=>res.json())
    .then(res=>{ renderRows(res.translations||[]); document.getElementById('pagination-wrapper').innerHTML=''; })
    .catch(()=>{ body.innerHTML=`<tr><td colspan="100%" class="text-center">Error loading translations</td></tr>`; });
}

/* ================= RENDER ROW ================= */
// function renderRows(rows=[]){
//     const body=document.getElementById('translations-table-body');
//     if(!rows.length){ body.innerHTML=`<tr><td colspan="100%" class="text-center">No translations found</td></tr>`; return; }
//     let html='';
//     rows.forEach(row=>{
//         html+=`<tr id="row-${row.id}">
//             <td><input type="checkbox" class="row-checkbox" value="${row.id}"></td>
//             <td><strong>${row.key}</strong></td>
//             <td>${row.group||''}</td>`;
//         @foreach(available_languages() as $lang)
//         html+=`<td><input type="text" class="form-control translation-input" data-id="${row.id}" data-lang="{{ $lang->code }}" value="${row.values?.['{{ $lang->code }}']??''}"></td>`;
//         @endforeach
//         html+=`<td>
//             <button class="btn btn-success btn-sm" onclick="saveOne(${row.id}, this)"><i class="fa fa-save"></i></button>
//             <button class="btn btn-warning btn-sm ml-1" onclick="autoTranslateRow(${row.id})"><i class="fa fa-language"></i></button>
//         </td></tr>`;
//     });
//     body.innerHTML=html;
//     document.querySelectorAll('.row-checkbox').forEach(cb=>cb.onchange=()=>toggleSelect(cb.value, cb.checked));
//     document.querySelectorAll('.translation-input').forEach(i=>{ i.dataset.old=i.value; i.oninput=()=>markChanged(i); });
//     selected.clear(); changed.clear(); updateBulkBtn();
// }
function renderRows(rows=[]){
    const body=document.getElementById('translations-table-body');
    if(!rows.length){
        body.innerHTML=`<tr><td colspan="100%" class="text-center">No translations found</td></tr>`;
        return;
    }
    let html='';
    rows.forEach(row=>{
        html+=`<tr id="row-${row.id}">
            <td><input type="checkbox" class="row-checkbox" value="${row.id}"></td>
            <td><strong>${row.key}</strong></td>
            <td>${row.group||''}</td>`;

        @foreach(available_languages() as $lang)
            {
                let langVal = '';
                try{
                    if(row.values){
                        // If values is JSON string, parse it
                        let vals = typeof row.values === 'string' ? JSON.parse(row.values) : row.values;
                        langVal = vals['{{ $lang->code }}'] || '';
                    }
                }catch(e){ console.error(e); langVal = ''; }
                html+=`<td>
                    <input type="text" class="form-control translation-input"
                           data-id="${row.id}" data-lang="{{ $lang->code }}"
                           value="${langVal}">
                </td>`;
            }
        @endforeach

        html+=`<td>
            <button class="btn btn-success btn-sm" onclick="saveOne(${row.id}, this)">
                <i class="fa fa-save"></i>
            </button>
            <button class="btn btn-warning btn-sm ml-1" onclick="autoTranslateRow(${row.id})">
                <i class="fa fa-language"></i>
            </button>
        </td></tr>`;
    });

    body.innerHTML=html;
    document.querySelectorAll('.row-checkbox').forEach(cb=>cb.onchange=()=>toggleSelect(cb.value, cb.checked));
    document.querySelectorAll('.translation-input').forEach(i=>{ i.dataset.old=i.value; i.oninput=()=>markChanged(i); });
    selected.clear(); changed.clear(); updateBulkBtn();
}


function prependRow(row){
    const tbody=document.getElementById('translations-table-body'); const tr=document.createElement('tr'); tr.id='row-'+row.id;
    let html=`<td><input type="checkbox" class="row-checkbox" value="${row.id}"></td><td><strong>${row.key}</strong></td><td>${row.group||''}</td>`;
    @foreach(available_languages() as $lang)
    html+=`<td><input type="text" class="form-control translation-input" data-id="${row.id}" data-lang="{{ $lang->code }}" value="${row.values['{{ $lang->code }}']||''}"></td>`;
    @endforeach
    html+=`<td><button class="btn btn-success btn-sm" onclick="saveOne(${row.id}, this)"><i class="fa fa-save"></i></button>
           <button class="btn btn-warning btn-sm ml-1" onclick="autoTranslateRow(${row.id})"><i class="fa fa-language"></i></button></td>`;
    tr.innerHTML=html; tbody.prepend(tr);
    tr.querySelector('.row-checkbox').onchange=()=>toggleSelect(row.id, tr.querySelector('.row-checkbox').checked);
    tr.querySelectorAll('.translation-input').forEach(i=>{ i.dataset.old=i.value; i.oninput=()=>markChanged(i); });
}

/* ================= PAGINATION ================= */
function renderPagination(p){ if(!p||p.last_page<=1){ document.getElementById('pagination-wrapper').innerHTML=''; return; }
    let html='<ul class="pagination">'; for(let i=1;i<=p.last_page;i++){ html+=`<li class="${i==p.current_page?'active':''}"><a href="#" onclick="event.preventDefault(); loadTranslations(${i})">${i}</a></li>`; } html+='</ul>'; document.getElementById('pagination-wrapper').innerHTML=html;
}

/* ================= STATE ================= */
function markChanged(i){ if(i.value!==i.dataset.old){ i.classList.add('changed'); document.getElementById(`row-${i.dataset.id}`).classList.add('row-highlight'); changed.add(i.dataset.id); } else { i.classList.remove('changed'); document.getElementById(`row-${i.dataset.id}`).classList.remove('row-highlight'); changed.delete(i.dataset.id); } updateBulkBtn(); }
function toggleSelect(id, checked){ checked?selected.add(id):selected.delete(id); updateBulkBtn(); }
function updateBulkBtn(){ document.getElementById('bulk-save-btn').disabled = ![...selected].some(id=>changed.has(id)); }

/* ================= SAVE ================= */
function saveOne(id, btn){
    const inputs=document.querySelectorAll(`.translation-input[data-id="${id}"]`); let data={}; inputs.forEach(i=>data[i.dataset.lang]=i.value); btn.disabled=true;
    fetch(`{{ route('translations.update') }}`,{ method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({translation_id:id, translations:data}) })
    .then(r=>r.json()).then(r=>{
        if(r.success){ inputs.forEach(i=>{i.dataset.old=i.value;i.classList.remove('changed');}); document.getElementById(`row-${id}`).classList.remove('row-highlight'); changed.delete(id); updateBulkBtn(); } else alert(r.message||'Error saving translation');
    }).finally(()=>{ btn.disabled=false; });
}
function bulkSave(){ selected.forEach(id=>{ if(changed.has(id)){ const btn=document.querySelector(`button[onclick="saveOne(${id}, this)"]`); if(btn) saveOne(id, btn); } }); }

/* ================= AUTO-TRANSLATE ================= */
async function autoTranslateRow(id){
    const inputs=document.querySelectorAll(`.translation-input[data-id="${id}"]`);
    const source=document.querySelector(`.translation-input[data-id="${id}"][data-lang="en"]`)?.value||'';
    if(!source){ alert('English source text is empty'); return; }
    for(const i of inputs){ if(!i.value.trim() && i.dataset.lang!=='en'){ try{ const res=await fetch(`https://translation.googleapis.com/language/translate/v2?key=YOUR_GOOGLE_API_KEY&q=${encodeURIComponent(source)}&source=en&target=${i.dataset.lang}`,{method:'POST'}); const data=await res.json(); i.value=data.data.translations[0].translatedText||source; markChanged(i); } catch(e){ console.error(e); i.value=source; markChanged(i); } } }
    alert('Row auto-translated!');
}

// ================= BULK AUTO-TRANSLATE =================
function bulkAutoTranslate(){
    if(selected.size===0){ alert('No rows selected'); return; }
    [...selected].forEach(id=>autoTranslateRow(id));
}

/* ================= UTIL ================= */
function debounce(fn,d){ let t; return ()=>{ clearTimeout(t); t=setTimeout(fn,d); } }
</script>

@endsection
