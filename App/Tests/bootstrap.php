<?php

    require __DIR__ . '/../../vendor/autoload.php';

    use App\Infra\DB;
    use App\Tests\FakePDO;

    // fake PDO injected here
    DB::set( new FakePDO() );
