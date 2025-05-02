<style>
  .search-flex-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    justify-content: center;
    margin-bottom: 1.5rem;
  }
  .search-flex-row .search-col {
    flex: 1 1 220px;
    min-width: 160px;
    max-width: 300px;
  }
  @media (max-width: 600px) {
    .search-flex-row {
      flex-direction: column;
      gap: 8px;
    }
    .search-flex-row .search-col {
      max-width: 100%;
    }
  }
</style>
<div class="card shadow-sm mb-4" style="border-radius:1.2rem; background:#fff; max-width:700px; margin:32px auto 2rem auto;">
  <form method="GET" action="{{ $action ?? url()->current() }}" class="p-3" style="max-width:650px; margin:0 auto;">
    <div class="search-flex-row">
      <div class="search-col">
        <label for="prefecture" class="form-label mb-1 small">都道府県</label>
        <select id="prefecture" name="prefecture" class="form-control">
          <option value="">選択してください</option>
          @php
            $prefs = [
              '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
              '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
              '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
              '岐阜県', '静岡県', '愛知県', '三重県',
              '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
              '鳥取県', '島根県', '岡山県', '広島県', '山口県',
              '徳島県', '香川県', '愛媛県', '高知県',
              '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
            ];
            $selectedPref = request('prefecture');
          @endphp
          @foreach($prefs as $pref)
            <option value="{{ $pref }}" @if($selectedPref == $pref) selected @endif>{{ $pref }}</option>
          @endforeach
        </select>
      </div>
      <div class="search-col">
        <label for="address1" class="form-label mb-1 small">市区町村</label>
        <input type="text" id="address1" name="address1" class="form-control" value="{{ request('address1') }}">
      </div>
    </div>
    <div class="text-center">
      <button type="submit" class="btn-blue-box" style="min-width:180px;">検索</button>
    </div>
  </form>
</div>
