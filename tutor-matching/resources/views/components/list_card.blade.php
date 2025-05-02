{{-- 共通リストカード: resources/views/components/list_card.blade.php --}}
<div class="card mb-3 shadow mx-auto bg-white position-relative" style="width:98%; max-width:700px; border-radius:1rem; overflow:hidden;">
    <div class="card-body p-0 position-relative">

        <table class="table table-borderless mb-0">
            <tr>
                <td class="py-3 px-4 text-center position-relative" style="width:100px; vertical-align:top;" rowspan="{{ count($fields) }}">
                    @php
                        use Illuminate\Support\Facades\Storage;
                        $cardImageUrl = null;
                        if (isset($image) && is_string($image) && !str_contains($image, 'http') && Storage::disk('public')->exists($image)) {
                            $cardImageUrl = Storage::url($image);
                        } elseif (isset($image) && is_string($image) && str_contains($image, 'storage/')) {
                            $cardImageUrl = $image;
                        } else {
                            $cardImageUrl = '/images/default.png';
                        }
                    @endphp
                    <img src="{{ $cardImageUrl }}" alt="画像" style="width:80px; height:80px; object-fit:cover; border-radius:50%; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                    @if (isset($bookmarkButton))
                        <div class="card-bookmark-slot">
                            {!! $bookmarkButton !!}
                        </div>
                    @endif
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
        @if(isset($env_imgs) && is_array($env_imgs) && count($env_imgs) > 0)
            <div class="my-3" style="display:flex;gap:12px;justify-content:center;">
                @foreach($env_imgs as $img)
                    @php
                        $envImgUrl = null;
                        if ($img && is_string($img) && !str_contains($img, 'http') && Illuminate\Support\Facades\Storage::disk('public')->exists($img)) {
                            $envImgUrl = Illuminate\Support\Facades\Storage::url($img);
                        } elseif ($img && is_string($img) && str_contains($img, 'storage/')) {
                            $envImgUrl = $img;
                        }
                    @endphp
                    <img src="{{ $envImgUrl ?? asset('images/default.png') }}" alt="環境画像" style="width:120px; height:120px; object-fit:cover; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                @endforeach
            </div>
        @endif
    </div>
    <style>
        .card-bookmark-slot {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 20;
            pointer-events: auto;
            border: 2px solid red; /* デバッグ用 */
            border-radius: 50%;
            background: rgba(255,255,255,0.7);
            padding: 2px 2px 0 2px;
            min-width: 36px;
            min-height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <div class="card-footer bg-light py-2 px-3 text-center">
        @if (isset($bookmarkButton))
            {!! $bookmarkButton !!}
        @endif
        <style>
            .btn-blue-box {
                background: #2563eb;
                color: #fff !important;
                font-weight: 600;
                border: none;
                border-radius: 0.7em;
                min-width: 110px;
                padding: 10px 0;
                box-shadow: 0 2px 6px rgba(37,99,235,0.08);
                font-size: 1.1rem;
                transition: background 0.15s, color 0.15s;
                display: inline-block;
            }
            .btn-gray-box {
                background: #adb5bd;
                color: #fff !important;
                font-weight: 600;
                border: none;
                border-radius: 0.7em;
                min-width: 110px;
                padding: 10px 0;
                box-shadow: 0 2px 6px rgba(160,160,160,0.08);
                font-size: 1.1rem;
                transition: background 0.15s, color 0.15s;
                display: inline-block;
                text-decoration: none;
                cursor: pointer;
            }
            .btn-gray-box:hover, .btn-gray-box:focus {
                background: #868e96;
                color: #fff !important;
                text-decoration: none;
            }
            @media (max-width: 600px) {
                .btn-gray-box { min-width: 90px; font-size: 0.95rem; }
            
                text-decoration: none;
            }
            .btn-blue-box:hover, .btn-blue-box:focus {
                background: #1e40af;
                color: #fff !important;
                text-decoration: none;
            }
            @media (max-width: 600px) {
                .btn-blue-box { min-width: 90px; font-size: 0.95rem; }
            }
        </style>
        @foreach($buttons as $btn)
            @if(isset($btn['custom']) && $btn['custom'] && isset($btn['html']))
                {!! $btn['html'] !!}
            @elseif(isset($btn['disabled']) && $btn['disabled'])
                <span class="btn-blue-box mx-2" style="background:#adb5bd; border-color:#adb5bd; color:#fff; cursor:not-allowed; pointer-events:none; opacity:0.85;">{{ $btn['label'] }}</span>
            @elseif(isset($btn['form']) && $btn['form'] && isset($btn['url']))
                <form method="POST" action="{{ $btn['url'] }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="{{ $btn['class'] ?? 'btn-blue-box mx-2' }}" {!! isset($btn['onclick']) ? 'onclick="'.$btn['onclick'].'"' : '' !!}>{{ $btn['label'] }}</button>
                </form>
            @elseif(isset($btn['url']))
                <a href="{{ $btn['url'] }}" class="{{ $btn['class'] ?? 'btn-blue-box mx-2' }}" {!! isset($btn['onclick']) ? 'onclick="'.$btn['onclick'].'"' : '' !!}>{{ $btn['label'] }}</a>
            @endif
        @endforeach
    </div>
</div>
