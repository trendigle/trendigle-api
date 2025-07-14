<?php

namespace App\Services\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class CelebrityService
{
    protected $client;
    protected $marshaler;
    protected $table;

    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'endpoint' => env('DYNAMODB_LOCAL_ENDPOINT', null),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        $this->marshaler = new Marshaler();
        $this->table = env('DYNAMODB_CELEBRITIES_TABLE');
    }

    public function create(array $data): array
    {
        $item = $this->marshaler->marshalItem($data);

        $this->client->putItem([
            'TableName' => $this->table,
            'Item' => $item,
        ]);

        return $data;
    }

    public function get(string $id): ?array
    {
        $result = $this->client->getItem([
            'TableName' => $this->table,
            'Key' => $this->marshaler->marshalItem(['id' => $id]),
        ]);

        if (!isset($result['Item'])) {
            return null;
        }

        return $this->marshaler->unmarshalItem($result['Item']);
    }

    public function update(string $id, array $data): ?array
    {
        $updateExpression = [];
        $expressionAttributeValues = [];
        $expressionAttributeNames = [];
        $i = 0;

        foreach ($data as $key => $value) {
            $updateExpression[] = "#attr{$i} = :val{$i}";
            $expressionAttributeNames["#attr{$i}"] = $key;
            $expressionAttributeValues[":val{$i}"] = $this->marshaler->marshalValue($value);
            $i++;
        }

        $updateExpressionString = 'SET ' . implode(', ', $updateExpression);

        try {
            $result = $this->client->updateItem([
                'TableName' => $this->table,
                'Key' => $this->marshaler->marshalItem(['id' => $id]),
                'UpdateExpression' => $updateExpressionString,
                'ExpressionAttributeNames' => $expressionAttributeNames,
                'ExpressionAttributeValues' => $expressionAttributeValues,
                'ReturnValues' => 'ALL_NEW',
            ]);

            return $this->marshaler->unmarshalItem($result['Attributes']);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->client->deleteItem([
                'TableName' => $this->table,
                'Key' => $this->marshaler->marshalItem(['id' => $id]),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
