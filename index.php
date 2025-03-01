<?php

# Увімкнення суворої типізації
declare(strict_types=1);

# Визначаємо кореневий каталог сайту
const ROOT_DIR = __DIR__;

# Підключаємо файли системного ядра
require ROOT_DIR . '/nucleus/engine.php';
require ROOT_DIR . '/nucleus/router.php';
