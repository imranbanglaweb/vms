@extends('admin.dashboard.master')

@section('main_content')

<style>
    .settings-container {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .settings-title {
        font-size: 22px;
        font-weight: 600;
        color: #2a2a2a;
    }

    .tab-btn {
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .tab-btn.active {
        background: #4A90E2 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(74,144,226,0.4);
    }

    .settings-card {
        background: #f9fafc;
        padding: 25px;
        border-radius: 12px;
        transition: 0.3s;
        border: 1px solid #e3e6ea;
    }

    .settings-card:hover {
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-primary.saved {
        padding: 10px 30px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 16px;
    }
</style>

<section role="main" class="content-body">
    <div class="settings-container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="settings-title">⚙️ Website Settings</h2>
            <a class="btn btn-outline-primary" href="{{ route('home') }}">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- TAB BUTTONS --}}
        <div class="mb-4">
            <button class="btn btn-light tab-btn site_menu active">
                <i class="fa fa-globe me-1"></i> Site Settings
            </button>
            <button class="btn btn-light tab-btn admin_menu">
                <i class="fa fa-user-shield me-1"></i> Admin Settings
            </button>
        </div>

        <hr>

        {{-- FORM --}}
        {!! Form::open(['method'=>'POST','enctype'=>'multipart/form-data', 'id'=>'upload-image-form']) !!}

        <div class="row">

            {{-- SITE SETTINGS CARD --}}
            <div class="col-md-12 site_settings settings-card">

                <div class="form-group mb-3">
                    <label>Site Title:</label>
                    {!! Form::text('site_title', $settings->site_title ?? null, ['class'=>'form-control']) !!}
                </div>

                <div class="form-group mb-3">
                    <label>Site Description:</label>
                    {!! Form::textarea('site_description', $settings->site_description ?? null, ['class'=>'form-control']) !!}
                </div>

                <div class="form-group mb-3">
                    <label>Site Copyright:</label>
                    {!! Form::text('site_copyright_text', $settings->site_copyright_text ?? null, ['class'=>'form-control']) !!}
                </div>

                <label>Site Logo:</label>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <input type="file" name="site_logo">
                    @if(!empty($settings->site_logo))
                        <img src="{{ asset('admin_resource/assets/images/'.$settings->site_logo) }}" width="80">
                    @endif
                </div>

            </div>

            {{-- ADMIN SETTINGS CARD --}}
            <div class="col-md-12 admin_settings settings-card">

                <div class="form-group mb-3">
                    <label>Admin Title:</label>
                    {!! Form::text('admin_title', $settings->admin_title ?? null, ['class'=>'form-control']) !!}
                </div>

                <div class="form-group mb-3">
                    <label>Admin Description:</label>
                    {!! Form::text('admin_description', $settings->admin_description ?? null, ['class'=>'form-control']) !!}
                </div>

                <label>Admin Logo:</label>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <input type="file" name="admin_logo">
                    @if(!empty($settings->admin_logo))
                        <img src="{{ asset('admin_resource/assets/images/'.$settings->admin_logo) }}" width="80">
                    @endif
                </div>

            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary saved">Save Changes</button>
            </div>

        </div>

        {!! Form::close() !!}

    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Default: show SITE settings
    $(".admin_settings").hide();

    $(".admin_menu").click(function() {
        $(".tab-btn").removeClass("active");
        $(this).addClass("active");

        $(".site_settings").fadeOut(200);
        $(".admin_settings").delay(200).fadeIn(200);
    });

    $(".site_menu").click(function() {
        $(".tab-btn").removeClass("active");
        $(this).addClass("active");

        $(".admin_settings").fadeOut(200);
        $(".site_settings").delay(200).fadeIn(200);
    });

    // AJAX SAVE
    $('#upload-image-form').submit(function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            type:'POST',
            url:"{{ route('settings.store') }}",
            data: formData,
            contentType: false,
            processData: false,

            success: (response) => {
                Swal.fire({
                    icon: "success",
                    title: "Settings Updated",
                    html: "<span style='color:green;'>Your changes have been saved successfully.</span>",
                    confirmButtonColor: "#4A90E2"
                }).then(() => {
                    location.reload();
                });
            },

            error: function(err){
                console.log(err);
            }
        });
    });
</script>

@endsection
