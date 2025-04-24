{{-- 共通リストカード: resources/views/components/list_card.blade.php --}}
<div class="card mb-3 shadow mx-auto bg-white" style="width:98%; max-width:700px; border-radius:1rem; overflow:hidden;">
    <div class="card-body p-0">
        <table class="table table-borderless mb-0">
            <tr>
                <td class="py-3 px-4 text-center" style="width:100px; vertical-align:top;" rowspan="{{ count($fields) }}">
                    <img src="{{ $image ?? asset('images/default.png') }}" alt="画像" style="width:80px; height:80px; object-fit:cover; border-radius:50%; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                </td>
                <td class="py-2 ps-0">
                    <span class="text-secondary small">{{ $fields[0]['label'] ?? '' }}：</span>
                    <span class="fw-bold" style="font-size:1.1rem;">{{ $fields[0]['value'] ?? '' }}</span>
                </td>
            </tr>
            @for($i = 1; $i < count($fields); $i++)
            <tr>
                <td class="py-2 ps-0">
                    <span class="text-secondary small">{{ $fields[$i]['label'] ?? '' }}：</span>
                    <span>{{ $fields[$i]['value'] ?? '' }}</span>
                </td>
            </tr>
            @endfor
        </table>
    </div>
    <div class="card-footer bg-light py-2 px-3 text-center">
        <style>
            .btn-blue-box {
                background: #4dabf7;
                border: 1.5px solid #4dabf7;
                color: #fff !important;
                font-weight: 500;
                min-width: 110px;
                padding: 10px 0;
                border-radius: 0.4rem;
                box-shadow: 0 2px 8px rgba(77,171,247,0.13);
                font-size: 1rem;
                transition: background 0.15s, color 0.15s;
                display: inline-block;
            }
            .btn-blue-box:hover, .btn-blue-box:focus {
                background: #339af0;
                color: #fff !important;
                text-decoration: none;
            }
            @media (max-width: 600px) {
                .btn-blue-box { min-width: 90px; font-size: 0.95rem; }
            }
        </style>
        @foreach($buttons as $btn)
            <a href="{{ $btn['url'] }}" class="btn-blue-box mx-2">{{ $btn['label'] }}</a>
        @endforeach
    </div>
</div>
