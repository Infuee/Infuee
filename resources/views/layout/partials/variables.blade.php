@php
$user = auth()->user();
@endphp
@if($user)
<script nonce="2726c7f26c" type="text/javascript">
var logged_in_user = {{$user['id']}};
var SOCKET_URL = "{{ env('SOCKET_URL') }}";

</script>
@endif