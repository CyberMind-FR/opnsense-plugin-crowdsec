# DO NOT EDIT THIS FILE -- OPNsense auto-generated file
{% if helpers.exists('OPNsense.crowdsec.general.firewall_bouncer_enabled') and OPNsense.crowdsec.general.firewall_bouncer_enabled|default("0") == "1" %}
crowdsec_firewall_enable="YES"
{% else %}
crowdsec_firewall_enable="NO"
{% endif %}