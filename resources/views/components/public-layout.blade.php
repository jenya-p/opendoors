<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <title>{{ config('app.name', 'OpenDoors') }}</title>

    <link rel="icon" href="/favicon.ico" type="image/x-icon" sizes="48x48" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300..600&family=Jura:wght@700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <meta name="description" content='Russian Scholarship Project Association "Global Universities"' />

    <script src="/static/scripts/application.js" defer></script>
    <link rel="stylesheet" href="/static/css/styles.css" />

</head>
<body class="__variable_904c5e flex min-h-screen flex-col bg-[image:var(--background)] bg-cover bg-top font-sans text-gray-dark"
      style="
      --background: url(&#34;data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzgwIiBoZWlnaHQ9IjY3MDMiIGZpbGw9Im5vbmUiPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDBoMTc4MHY2NzAzSDB6Ii8+PHBhdGggZmlsbD0idXJsKCNhKSIgZmlsbC1vcGFjaXR5PSIuMiIgZD0iTTAgMGgxNzgwdjY3MDNIMHoiLz48cGF0aCBmaWxsPSJ1cmwoI2IpIiBkPSJNMCAwaDE3ODB2NjcwM0gweiIvPjxwYXRoIGZpbGw9InVybCgjYykiIGZpbGwtb3BhY2l0eT0iLjIiIGQ9Ik0wIDBoMTc4MHY2NzAzSDB6Ii8+PHBhdGggZmlsbD0idXJsKCNkKSIgZD0iTTAgMGgxNzgwdjY3MDNIMHoiLz48cGF0aCBmaWxsPSJ1cmwoI2UpIiBmaWxsLW9wYWNpdHk9Ii4yIiBkPSJNMCAwaDE3ODB2NjcwM0gweiIvPjxwYXRoIGZpbGw9InVybCgjZikiIGQ9Ik0wIDBoMTc4MHY2NzAzSDB6Ii8+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJhIiB4MT0iMCIgeDI9IjE4ODEiIHkxPSIyOTAyLjUiIHkyPSI0MDAyLjUiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOENCQUZGIiBzdG9wLW9wYWNpdHk9IjAiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM5NUE0RkMiLz48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iYyIgeDE9IjE3ODAiIHgyPSItMTM1NiIgeTE9IjEyNzciIHkyPSIzMDM2LjUiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjRTQyRjgwIiBzdG9wLW9wYWNpdHk9IjAiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNGRjI5MjkiIHN0b3Atb3BhY2l0eT0iLjQiLz48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iZSIgeDE9Ii0xNDQiIHgyPSIxNzgwIiB5MT0iMjIxMSIgeTI9IjAiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOTVBNEZDIiBzdG9wLW9wYWNpdHk9IjAiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM5NUE0RkMiLz48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iZiIgeDE9Ijk2Ny41IiB4Mj0iMCIgeTE9Ijk2Ny41IiB5Mj0iMCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIHN0b3AtY29sb3I9IiNmZmYiIHN0b3Atb3BhY2l0eT0iMCIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2ZmZiIvPjwvbGluZWFyR3JhZGllbnQ+PHJhZGlhbEdyYWRpZW50IGlkPSJiIiBjeD0iMCIgY3k9IjAiIHI9IjEiIGdyYWRpZW50VHJhbnNmb3JtPSJyb3RhdGUoLTEzNSAyMjc4LjIzNyAyOTgyLjg1KSBzY2FsZSgyNTA3LjQpIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjRkZDNjU4IiBzdG9wLW9wYWNpdHk9Ii4wNSIvPjwvcmFkaWFsR3JhZGllbnQ+PHJhZGlhbEdyYWRpZW50IGlkPSJkIiBjeD0iMCIgY3k9IjAiIHI9IjEiIGdyYWRpZW50VHJhbnNmb3JtPSJtYXRyaXgoLTE3NzkuOTk5MzcgLTM0MTUuOTk4MjUgMjczNy43OTg1MiAtMTQyNi42MDQ4NCAxNzgwIDY3MDMpIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuNiIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+PC9yYWRpYWxHcmFkaWVudD48L2RlZnM+PC9zdmc+&#34;);
    ">
<x-layout.header />
{{ $slot }}
<x-layout.footer />
{{$inlineScripts ?? ''}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.6/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script>
    MathJax.Ajax.config.path["mhchem"] = "https://cdnjs.cloudflare.com/ajax/libs/mathjax-mhchem/3.3.2";
    MathJax.Hub.Config({
        showMathMenu: false,
        TeX: {
            extensions: [ "[mhchem]/mhchem.js" ]
        },
        messageStyle: "none",
        tex2jax: {
            preview: "none"
        }
    });
</script>

</body>
</html>
