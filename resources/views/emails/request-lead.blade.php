<h2>New Equipment Request</h2>

<p><strong>Name:</strong> {{ $lead['name'] }}</p>

<p><strong>Email:</strong> {{ $lead['email'] }}</p>

<p><strong>Phone:</strong> {{ $lead['phone'] }}</p>

<p><strong>Message:</strong> {{ $lead['message'] }}</p>

<p><strong>Requested Items:</strong></p>

<ul>
    @foreach($lead['items'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>