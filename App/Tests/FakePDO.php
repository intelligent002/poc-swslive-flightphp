<?php

    namespace App\Tests;

    use App\Infra\DBConnection;

    final class FakePDO implements DBConnection
    {
        public function prepare( string $query ) : FakeStatement
        {
            return new FakeStatement( $query );
        }
    }
