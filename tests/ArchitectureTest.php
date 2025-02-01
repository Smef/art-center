<?php

/*
|--------------------------------------------------------------------------
| Architecture Testing
|
| https://pestphp.com/docs/arch-testing
|
| The architecture testing feature allows you to define the rules of your
| architecture and then ensure that your codebase follows those rules.
| Use this file or you can create multiple files under the Architecture/ directory.
|--------------------------------------------------------------------------
 */

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();
