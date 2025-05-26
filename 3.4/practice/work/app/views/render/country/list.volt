<div>from <span>list.volt</span></div>
<div>{{message}}</div>
<div>
    {% if countries is not empty %}
        <select id="countries">
        {% for key, value in countries %}
            <option value="{{ countries.name }}">+{{ key }}</option>
        {% else %}
        {% endfor %}
        </select>
    {% endif %}
</div>