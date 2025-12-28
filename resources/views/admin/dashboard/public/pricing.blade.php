@extends('admin.dashboard.master')

@section('title','Pricing')

@section('main_content')
<br>
<section role="main" class="content-body" style="background-color: #fff;">
<section class="pricing-dark">

<!-- HERO -->
<div class="container text-center">
    <h1 class="display-5">
        Flexible Pricing for Every Business
    </h1>
    <p class="fs-4 text-muted mb-4">
        Scale your fleet with confidence
    </p>

    <!-- TOGGLE -->
    <div class="billing-toggle">
        <span class="toggle-label active" data-type="monthly">Monthly</span>
        <div class="toggle-switch" id="billingToggle"></div>
        <span class="toggle-label" data-type="yearly">
            Yearly <small>(Save 20%)</small>
        </span>
    </div>
</div>

<!-- PRICING -->
<div class="container">
<div class="row g-5 justify-content-center">

<!-- STARTER -->
<div class="col-lg-4">
<div class="pricing-card dark">
<h5 class="plan-name">Starter</h5>

<div class="price" data-monthly="2000" data-yearly="19200">
    ৳<span>2000</span>
</div>
<div class="billing-text">per month</div>

<ul class="features">
    <li>✔ Up to 5 Vehicles</li>
    <li>✔ 3 Users</li>
    <li>✔ Fuel & Maintenance</li>
    <li class="muted">✖ API Access</li>
</ul>

<a href="{{ route('register') }}" class="btn btn-outline-light w-100">
    Start Free Trial
</a>
</div>
</div>

<!-- BUSINESS -->
<div class="col-lg-4">
<div class="pricing-card dark popular">
<span class="badge-popular">MOST POPULAR</span>

<h5 class="plan-name">Business</h5>

<div class="price" data-monthly="5000" data-yearly="48000">
    ৳<span>5000</span>
</div>
<div class="billing-text">per month</div>

<ul class="features">
    <li>✔ Up to 25 Vehicles</li>
    <li>✔ 10 Users</li>
    <li>✔ Advanced Reports</li>
    <li>✔ Priority Support</li>
</ul>

<a href="{{ route('register') }}" class="btn btn-primary w-100">
    Get Started
</a>
</div>
</div>

<!-- ENTERPRISE -->
<div class="col-lg-4">
<div class="pricing-card dark">
<h5 class="plan-name">Enterprise</h5>

<div class="price custom">
    Custom
</div>
<div class="billing-text">Let’s Talk</div>

<ul class="features">
    <li>✔ Unlimited Vehicles</li>
    <li>✔ Unlimited Users</li>
    <li>✔ API & Integrations</li>
    <li>✔ Dedicated Manager</li>
</ul>

<a href="#" class="btn btn-dark w-100">
    Contact Sales
</a>
</div>
</div>

</div>
</div>

</section>
</section>

<!-- STYLES -->
<style>
.pricing-dark {
    background: radial-gradient(circle at top, #111827, #020617);
    color: #fff;
    min-height: 100vh;
        margin-left: -30px;
}

.billing-toggle {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: #020617;
    padding: 10px 20px;
    border-radius: 50px;
    border: 1px solid #1e293b;
}

.toggle-label {
    font-size: 1.1rem;
    cursor: pointer;
    color: #94a3b8;
}

.toggle-label.active {
    color: #fff;
    font-weight: 600;
}

.toggle-switch {
    width: 50px;
    height: 26px;
    background: #1e293b;
    border-radius: 20px;
    position: relative;
    cursor: pointer;
}

.toggle-switch::after {
    content: '';
    width: 20px;
    height: 20px;
    background: #0d6efd;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 4px;
    transition: .3s;
}

.toggle-switch.yearly::after {
    transform: translateX(22px);
}

.pricing-card.dark {
    background: linear-gradient(180deg, #020617, #020617);
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 40px;
    text-align: center;
    height: 100%;
    box-shadow: 0 0 40px rgba(13,110,253,.15);
}

.pricing-card.popular {
    border: 2px solid #0d6efd;
    transform: scale(1.05);
}

.badge-popular {
    background: linear-gradient(90deg,#0d6efd,#6610f2);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: .85rem;
    display: inline-block;
    margin-bottom: 15px;
}

.plan-name {
    font-size: 1.3rem;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.price {
    font-size: 3.2rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.billing-text {
    color: #94a3b8;
    margin-bottom: 25px;
}

.features {
    list-style: none;
    padding: 0;
    text-align: left;
    font-size: 1.1rem;
}

.features li {
    margin-bottom: 12px;
}

.features .muted {
    color: #64748b;
}
</style>

<!-- SCRIPT -->
<script>
const toggle = document.getElementById('billingToggle');
const prices = document.querySelectorAll('.price');
const labels = document.querySelectorAll('.toggle-label');
let yearly = false;

toggle.addEventListener('click', () => {
    yearly = !yearly;
    toggle.classList.toggle('yearly');

    labels.forEach(l => l.classList.remove('active'));
    document.querySelector(`[data-type="${yearly ? 'yearly' : 'monthly'}"]`).classList.add('active');

    prices.forEach(p => {
        if (!p.dataset.monthly) return;
        p.querySelector('span').innerText = yearly ? p.dataset.yearly : p.dataset.monthly;
        p.nextElementSibling.innerText = yearly ? 'per year' : 'per month';
    });
});
</script>

@endsection
