<h2>Hello from Mode 0</h2>

{% for item in list %}
    <p>output: {{ item.date }}: {{ item.message }}</p>
{% else %}
    <p>no data</p>
{% endfor %}
<hr>
{% for key, value in map %}
    <p>{{ key }}: {{ value.date }} : {{ value.message }}</p>
{% else %}
    <p>no data</p>
{% endfor %}