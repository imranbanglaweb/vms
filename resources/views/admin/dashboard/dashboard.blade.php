@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="body;background:#fff;">
<div class="container-fluid p-4 bg-white rounded shadow-sm">
<br>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fa fa-tachometer-alt me-2 text-primary"></i> Dashboard Overview</h4>

        <div class="d-flex align-items-center gap-2">
            <button id="refreshNow" class="btn btn-outline-primary btn-sm"><i class="fa fa-sync"></i> Refresh</button>
            <div class="position-relative">
                <i class="fa fa-bell fa-lg"></i>
                <span id="liveNotifCount" class="badge bg-danger position-absolute" style="top:-8px; right:-8px; display:none;">0</span>
            </div>
        </div>
    </div>

    {{-- Cards --}}
    <div class="row mb-4" id="cardsRow">
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Total</div>
                <h3 id="cardTotal">{{ $total }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Pending</div>
                <h3 id="cardPending">{{ $pending }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Approved</div>
                <h3 id="cardApproved">{{ $approved }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Rejected</div>
                <h3 id="cardRejected">{{ $rejected }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Completed</div>
                <h3 id="cardCompleted">{{ $completed }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm p-3">
                <div class="small text-muted">Cancelled</div>
                <h3 id="cardCancelled">{{ $cancelled ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left column: Monthly + Dept pie --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Monthly Requisitions (Last 12 months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="120"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Department-wise Requests</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="deptPieChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Status Ratio</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="statusDoughnut" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top users --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top Active Users</h6>
                </div>
                <div class="card-body">
                    <canvas id="topUsersChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Right column: Timeline + latest table --}}
        <div class="col-lg-4">

            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Recent Workflow Activity</h6>
                </div>
                <div class="card-body" id="timelineContainer" style="max-height:420px; overflow:auto;">
                    @foreach($timeline as $item)
                        <div class="mb-3">
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y - h:i A') }} by {{ $item->user_name }}</div>
                            <div class="fw-bold">{{ $item->action }}</div>
                            @if($item->remarks)<div class="small text-muted">{{ $item->remarks }}</div>@endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Latest Requisitions</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="latestTableBody">
                            @foreach($latest as $r)
                                <tr>
                                    <td>{{ $r->id }}</td>
                                    <td>{{ $r->requestedBy->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($r->travel_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $r->status == 3 ? 'bg-success' : ($r->status == 4 ? 'bg-danger' : 'bg-warning') }}">
                                            {{ $r->status_text ?? $r->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
<br>
<br>
<br>
<br>
</section>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function(){
    // ---------- INITIAL DATA FROM BLADE (server rendered)
    const monthLabels = {!! json_encode($monthLabels) !!};
    const monthlyData = {!! json_encode($monthlyData) !!};
    const deptData = {!! json_encode($deptData) !!};
    const statusCounts = {!! json_encode($statusCounts) !!};
    const topUsers = {!! json_encode($topUsers) !!};

    // ---------- CHARTS
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Requisitions',
                backgroundColor: '#0d6efd',
                data: monthlyData
            }]
        },
        options: { responsive:true, scales:{ y:{ beginAtZero: true } } }
    });

    const deptLabels = deptData.map(d => d.label);
    const deptValues = deptData.map(d => d.value);
    const deptCtx = document.getElementById('deptPieChart').getContext('2d');
    const deptPieChart = new Chart(deptCtx, {
        type:'pie',
        data: { labels: deptLabels, datasets: [{ data: deptValues }] },
        options: { responsive:true }
    });

    const statusLabels = Object.keys(statusCounts);
    const statusValues = Object.values(statusCounts);
    const statusCtx = document.getElementById('statusDoughnut').getContext('2d');
    const statusDoughnut = new Chart(statusCtx, {
        type:'doughnut',
        data: { labels: statusLabels, datasets: [{ data: statusValues }] },
        options: { responsive:true }
    });

    const topUserLabels = topUsers.map(u => u.name);
    const topUserValues = topUsers.map(u => u.total);
    const topUsersCtx = document.getElementById('topUsersChart').getContext('2d');
    const topUsersChart = new Chart(topUsersCtx, {
        type:'bar',
        data: { labels: topUserLabels, datasets: [{ data: topUserValues, backgroundColor:'#20c997' }] },
        options: { responsive:true, indexAxis: 'y' }
    });

    // ---------- LIVE AJAX REFRESH
    function applyLiveData(data) {
        // cards
        $('#cardTotal').text(data.total);
        $('#cardPending').text(data.pending);
        $('#cardApproved').text(data.approved);
        $('#cardRejected').text(data.rejected);
        $('#cardCompleted').text(data.completed);

        // latest table
        let rows = '';
        data.latest.forEach(function(r){
            rows += `<tr>
                        <td>${r.id}</td>
                        <td>${r.requested_by_name ?? (r.requested_by || 'N/A')}</td>
                        <td>${(new Date(r.travel_date)).toLocaleDateString()}</td>
                        <td><span class="badge ${r.status==3 ? 'bg-success' : (r.status==4 ? 'bg-danger' : 'bg-warning')}">${r.status_text ?? r.status}</span></td>
                    </tr>`;
        });
        $('#latestTableBody').html(rows);

        // dept pie update (simple)
        if (data.deptData && Array.isArray(data.deptData)) {
            deptPieChart.data.labels = data.deptData.map(d=>d.label);
            deptPieChart.data.datasets[0].data = data.deptData.map(d=>d.value);
            deptPieChart.update();
        }
    }

    // fetch live
    function fetchLive(){
        $.get("{{ route('admin.dashboard.data') }}")
            .done(function(res){
                // normalize latest rows: add requested_by_name for client
                res.latest.forEach(function(r){
                    r.requested_by_name = (r.requested_by && r.requested_by.name) ? r.requested_by.name : (r.requested_by_name || '');
                });
                applyLiveData(res);
            })
            .fail(function(){ console.warn('dashboard live fetch failed'); });
    }

    // manual refresh button
    $('#refreshNow').on('click', function(){ fetchLive(); toastr.info('Dashboard refreshed'); });

    // auto poll every 10s
    const autoRefresh = setInterval(fetchLive, 10000);

    // ---------- REALTIME (ECHO) listener, optional
    try {
        if (typeof Echo !== 'undefined') {
            Echo.private('dashboard')
                .listen('RequisitionCreated', (e) => {
                    toastr.info('New requisition created');
                    // update small UI immediately
                    fetchLive();
                })
                .listen('RequisitionStatusUpdated', (e) => {
                    toastr.info('Requisition status updated');
                    fetchLive();
                });
        }
    } catch(err) {
        console.warn('Echo not configured or error: ', err);
    }

    // ---------- Notification bell count (simple)
    function fetchNotifCount() {
        $.get("{{ url('/admin/notifications/unread-count') }}").done(function(res){
            if(res.count && res.count > 0) {
                $('#liveNotifCount').text(res.count).show();
            } else $('#liveNotifCount').hide();
        }).fail(()=>{ $('#liveNotifCount').hide(); });
    }
    fetchNotifCount();
    setInterval(fetchNotifCount, 15000);

}); // document ready
</script>
@endpush
