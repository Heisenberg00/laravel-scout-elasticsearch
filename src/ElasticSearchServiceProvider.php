<?php

declare(strict_types=1);

namespace Matchish\ScoutElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

use Aws\ElasticsearchService\ElasticsearchPhpHandler;
use Illuminate\Support\Facades\Config;
use Aws\Credentials\CredentialProvider;
use Aws\ElasticsearchService\ElasticsearchServiceClient;
use InvalidArgumentException;
use Laravel\Scout\EngineManager;


final class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register2(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/elasticsearch.php', 'elasticsearch');

        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()->setHosts([config('elasticsearch.host')])->build();
        });

        $this->app->bind(
            'Matchish\ScoutElasticSearch\ElasticSearch\HitsIteratorAggregate',
            'Matchish\ScoutElasticSearch\ElasticSearch\EloquentHitsIteratorAggregate'
        );
    }
    
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/elasticsearch.php', 'elasticsearch');
        
        $this->app->bind(Client::class, function () {

            $aws_enabled = Config::get('elasticscout.connection.hosts.0.aws_enable');

            $hosts = [config('elasticsearch.host')];
            $client = ClientBuilder::create();

            if ($aws_enabled) {
                $hosts = [Config::get('elasticscout.connection.hosts.0.host') . ':' . Config::get('elasticscout.connection.hosts.0.port')];
                $provider = CredentialProvider::defaultProvider();
                $handler = new ElasticsearchPhpHandler('eu-west-3', $provider);
                $client->setHandler($handler);
            }
            $client->setHosts($hosts);
            return $client->build();
        });

        $this->app->bind(
            'Matchish\ScoutElasticSearch\ElasticSearch\HitsIteratorAggregate',
            'Matchish\ScoutElasticSearch\ElasticSearch\EloquentHitsIteratorAggregate'
        );
    }
    
    

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/elasticsearch.php' => config_path('elasticsearch.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/../config/elasticscout.php' => config_path('elasticscout.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return [Client::class];
    }
}
