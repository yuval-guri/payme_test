<table>
    <tr>
        <td>Time</td>
        <td>Sale Number</td>
        <td>Description</td>
        <td>Amount</td>
        <td>Currency</td>
        <td>Payment Link</td>
    </tr>
    @foreach ($sales as $sale)
        <tr>
        <td>{{ $sale->time }}</td>
        <td>{{ $sale->id }}</td>
        <td>{{ $sale->description }}</td>
        <td>{{ $sale->amount }}</td>
        <td>{{ $sale->currency }}</td>
        <td>{{ $sale->payment_link }}</td>
        </tr>
    @endforeach
</table>