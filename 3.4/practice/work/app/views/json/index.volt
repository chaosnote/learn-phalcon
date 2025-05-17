<select id="countries">
{% for key, value in countries %}
    <option value="{{ countries.name }}">+{{ key }}</option>
{% else %}
{% endfor %}
</select>
<script>
    var data = JSON.parse('{{data}}');
    console.log(data) ;
</script>