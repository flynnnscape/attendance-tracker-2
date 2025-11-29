@props(['url'])
<tr>
<td class="header" style="text-align:center;">
    <a href="{{ $url }}" style="display: inline-block;">

        <img src="{{ asset('images/favicon.ico') }}"
             alt="Logo"
             width="60"
             style="border-radius:12px; display:block; margin:0 auto 8px;">

        <span style="font-size: 18px; font-weight: bold;">
            {{ config('app.name') }}
        </span>

    </a>
</td>
</tr>
