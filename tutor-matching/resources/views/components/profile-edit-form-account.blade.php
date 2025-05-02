<div class="form-bottom-space" style="margin-top:2.2rem;">
    <div class="mb-3 text-center">
        <label for="email" class="form-label w-100 form-label-top-space" style="display:block; text-align:center;">メールアドレス</label>
        <input id="email" type="email" name="email" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('email', $user->email ?? '') }}">
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-center">
        <label for="postal_code" class="form-label w-100" style="display:block; text-align:center;">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('postal_code', $user->postal_code ?? '') }}" autocomplete="off">
        @error('postal_code')<div class="text-danger small">{{ $message }}</div>@enderror
        <div class="mt-2">
            <span id="prefecture_display" class="badge bg-light text-dark me-2"></span>
            <span id="city_display" class="badge bg-light text-dark"></span>
        </div>
        <input type="hidden" name="prefecture" id="hidden_prefecture" value="{{ old('prefecture', $user->prefecture ?? '') }}">
        <input type="hidden" name="address1" id="hidden_address1" value="{{ old('address1', $user->address1 ?? '') }}">
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const postalInput = document.getElementById('postal_code');
        const prefDisp = document.getElementById('prefecture_display');
        const cityDisp = document.getElementById('city_display');
        const hiddenPostal = document.getElementById('hidden_postal_code');
        function fetchAddress(zip) {
            const hiddenPref = document.getElementById('hidden_prefecture');
            const hiddenAddr1 = document.getElementById('hidden_address1');
            if (!zip || zip.length !== 7) {
                prefDisp.textContent = '';
                cityDisp.textContent = '';
                if (hiddenPref) hiddenPref.value = '';
                if (hiddenAddr1) hiddenAddr1.value = '';
                return;
            }
            fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + encodeURIComponent(zip))
                .then(res => res.ok ? res.json() : null)
                .then(data => {
                    if (data && data.results && data.results[0]) {
                        const pref = data.results[0].address1 || '';
                        const city = data.results[0].address2 || '';
                        prefDisp.textContent = pref;
                        cityDisp.textContent = city;
                        if (hiddenPref) hiddenPref.value = pref;
                        if (hiddenAddr1) hiddenAddr1.value = city;
                    } else {
                        prefDisp.textContent = '';
                        cityDisp.textContent = '';
                        if (hiddenPref) hiddenPref.value = '';
                        if (hiddenAddr1) hiddenAddr1.value = '';
                    }
                })
                .catch(() => {
                    prefDisp.textContent = '';
                    cityDisp.textContent = '';
                    if (hiddenPref) hiddenPref.value = '';
                    if (hiddenAddr1) hiddenAddr1.value = '';
                });
        }
        postalInput.addEventListener('input', function () {
            if (hiddenPostal) hiddenPostal.value = this.value;
            fetchAddress(this.value.replace(/[^0-9]/g, ''));
        });
        // 初期値があればhiddenにもセット&表示
        if (postalInput.value) {
            if (hiddenPostal) hiddenPostal.value = postalInput.value;
            fetchAddress(postalInput.value.replace(/[^0-9]/g, ''));
        }
    });
    </script>


    <div class="mb-3 text-center">
        <label for="phone" class="form-label w-100" style="display:block; text-align:center;">電話番号</label>
        <input id="phone" type="text" name="phone" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('phone', $user->phone ?? '') }}">
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

</div>
