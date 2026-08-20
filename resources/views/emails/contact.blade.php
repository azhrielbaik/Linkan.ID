<h2>New Contact Message</h2>
<p><strong>Name:</strong> {{ $data['name'] ?? 'User' }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
@if(!empty($data['phone']))
<p><strong>Phone:</strong> {{ $data['phone'] }}</p>
@endif
<p><strong>Message:</strong><br>{!! nl2br(e($data['message'])) !!}</p>
