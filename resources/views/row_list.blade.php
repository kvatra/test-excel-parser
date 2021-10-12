<table>
    <colgroup span="2"></colgroup>
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Item data</th>
        </tr>
    </thead>
    <tbody>
    @foreach($groups as $date => $items)
        <tr>
            <td> {{ $date }} </td>
            @foreach($items as $item)
                <td>{{ json_encode($item) }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>