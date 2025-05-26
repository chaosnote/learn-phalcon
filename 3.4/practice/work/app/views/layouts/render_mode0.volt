<!DOCTYPE html>
<html>
<head>
    <title>{{ title }}</title>
    {# {{ stylesheet('css/style.css') }} #}
</head>
<body>
    <div class="header">
        <h1>header</h1>
    </div>
    <div class="container">
        {{ content() }}
    </div>
    <div class="footer">
        <p>&copy; footer</p>
    </div>
    {# {{ javascript('js/app.js') }} #}
</body>
</html>