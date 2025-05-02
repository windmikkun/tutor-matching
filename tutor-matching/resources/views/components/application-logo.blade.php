<style>
.fuwafuwa-logo-bg {
    width: 140px;
    height: 140px;
    background: #111;
    border-radius: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
    animation: fuwafuwa 2.4s ease-in-out infinite;
}
.fuwafuwa-logo {
    height: 120px;
    width: auto;
    display: block;
}
@keyframes fuwafuwa {
    0%   { transform: translateY(0); }
    50%  { transform: translateY(-18px); }
    100% { transform: translateY(0); }
}
</style>
<div class="fuwafuwa-logo-bg">
  <img src="{{ asset('images/logo.png') }}" alt="サービスロゴ" class="fuwafuwa-logo">
</div>
