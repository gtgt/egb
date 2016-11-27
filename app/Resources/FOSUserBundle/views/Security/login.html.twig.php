{% extends "FOSUserBundle::layout.html.twig" %}

{% trans_default_domain 'FOSUserBundle' %}


{% block title %}Please Sign In{% endblock %}


{% block fos_user_content %}
{% if error %}
    <div class="alert alert-danger" role="alert">
        {{ error.messageKey|trans(error.messageData, 'security') }}
    </div>
{% endif %}

    <form action="{{ path("fos_user_security_check") }}" method="post" class="form-signin">
        <input type="hidden" name="_csrf_token" value="{{ csrf_token }}" />

        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="username" class="sr-only">{{ 'security.login.username'|trans }}</label>
        <input type="text" id="username" name="_username" value="{{ last_username }}" placeholder="Email address" class="form-control" required autofocus />
        <label for="password" class="sr-only">{{ 'security.login.password'|trans }}</label>
        <input type="password" id="password" name="_password" class="form-control" placeholder="Password" required />
        <div class="checkbox">
            <label>
                <input type="checkbox"
                       id="remember_me"
                       name="_remember_me"
                       value="on" /> {{ 'security.login.remember_me'|trans }}
            </label>
        </div>
        <input type="submit"
               class="btn btn-lg btn-primary btn-block"
               id="_submit"
               name="_submit"
               value="{{ 'security.login.submit'|trans }}" />
    </form>

{% endblock fos_user_content %}


{% block stylesheets %}
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
{% endblock %}