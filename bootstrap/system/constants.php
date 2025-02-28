<?php

# Визначаємо повний URL адрес запитаної сторінки
define('REQUEST_URI', isset($_SERVER["REQUEST_URI"]) ? _filter($_SERVER["REQUEST_URI"]) : '/');
