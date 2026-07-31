@if($errors->any())
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 text-sm space-y-1">
    @foreach($errors->all() as $err)
        <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> {{ $err }}</div>
    @endforeach
</div>
@endif
