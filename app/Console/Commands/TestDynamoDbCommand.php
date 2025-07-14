<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDynamoDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamodb:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests connectivity to DynamoDB and lists tables.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $client = new \Aws\DynamoDb\DynamoDbClient([
                'region' => config('database.connections.dynamodb.region'),
                'version' => config('database.connections.dynamodb.version'),
                'endpoint' => config('database.connections.dynamodb.endpoint'),
                'credentials' => [
                    'key'    => config('database.connections.dynamodb.key'),
                    'secret' => config('database.connections.dynamodb.secret'),
                ]
            ]);

            $result = $client->listTables();

            $this->info('Successfully connected to DynamoDB!');
            $this->info('Tables:');
            foreach ($result['TableNames'] as $tableName) {
                $this->line('- ' . $tableName);
            }
        } catch (\Exception $e) {
            $this->error('Failed to connect to DynamoDB: ' . $e->getMessage());
        }
    }
}
