<?php

namespace RayzenAI\GoldSilverPrice\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use RayzenAI\GoldSilverPrice\GoldSilverPriceServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            GoldSilverPriceServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up SQLite in-memory database for testing
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set gold-silver-price config
        $app['config']->set('gold-silver-price.table_name', 'gold_prices');
        $app['config']->set('gold-silver-price.source_url', 'https://fenegosida.org');
    }

    protected function setUpDatabase(): void
    {
        $tableName = config('gold-silver-price.table_name', 'gold_prices');

        // Run the gold_prices table migration
        $this->app['db']->connection()->getSchemaBuilder()->create($tableName, function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('gold_per_tola')->comment('Gold price per tola in Rs.');
            $table->integer('gold_per_10g')->comment('Gold price per 10 grams in Rs.');
            $table->integer('silver_per_tola')->comment('Silver price per tola in Rs.');
            $table->integer('silver_per_10g')->comment('Silver price per 10 grams in Rs.');
            $table->timestamps();
        });
    }
}
