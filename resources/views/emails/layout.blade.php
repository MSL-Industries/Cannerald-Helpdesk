<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:300,500');
        body {
            margin: 20px;
            font-family: Lato, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            font-weight: 100;
        }
    </style>
</head>
<body>
    @yield('body')

    <div style="margin-top:40px">
        Helpdesk by <a href="{{ env('APP_FOOTER_LINK') }}">{{ env('APP_COMPANY_NAME') }}</a>
    </div>

</body>
</html>