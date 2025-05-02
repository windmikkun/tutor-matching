{{-- 応募者拒否用フォームコンポーネント --}}
<form method="POST" action="{{ route('applicant.reject', ['id' => $id]) }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn-blue-box mx-2" onclick="return confirm('本当にこの応募者を拒否しますか？');">
        拒否
    </button>
</form>
