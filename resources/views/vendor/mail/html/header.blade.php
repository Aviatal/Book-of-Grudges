@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('images/logo-mark.png') }}" class="logo" width="72" height="72" alt="Book of Grudges"><br>
{!! strtoupper($slot) !!}
</a>
</td>
</tr>
