@isset($error)
    <span style="color: red">Error: {{ $error }}</span>
@else
    <IFRAME src="{{ $sale_url }}"></IFRAME>
@endisset