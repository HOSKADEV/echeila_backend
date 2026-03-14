<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\FirebaseException;

class FirestoreService
{
    private $firestore;

    public function __construct()
    {
        $this->firestore = Firebase::firestore()->database();
    }

    /**
     * Get a single document by ID or all documents in a collection.
     *
     * @param  string      $collection
     * @param  string|null $documentId
     * @param  array       $filters    Each filter: ['field' => '', 'operator' => '', 'value' => '']
     * @param  int|null    $limit
     * @return array|null
     */
    public function get(string $collection, ?string $documentId = null, array $filters = [], ?int $limit = null): ?array
    {
        if ($documentId) {
            $snapshot = $this->firestore->collection($collection)->document($documentId)->snapshot();

            if (!$snapshot->exists()) {
                return null;
            }

            return array_merge(['id' => $snapshot->id()], $snapshot->data());
        }

        $query = $this->firestore->collection($collection);

        foreach ($filters as $filter) {
            if (isset($filter['field'], $filter['operator'], $filter['value'])) {
                $query = $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        if ($limit !== null) {
            $query = $query->limit($limit);
        }

        $results = [];

        foreach ($query->documents() as $document) {
            if ($document->exists()) {
                $results[] = array_merge(['id' => $document->id()], $document->data());
            }
        }

        return $results;
    }

    /**
     * Create a new document in a collection.
     * If $documentId is null, Firestore will auto-generate an ID.
     *
     * @param  string      $collection
     * @param  array       $data
     * @param  string|null $documentId
     * @return array
     */
    public function create(string $collection, array $data, ?string $documentId = null): array
    {
        if ($documentId) {
            $docRef = $this->firestore->collection($collection)->document($documentId);
            $docRef->set($data);
        } else {
            $docRef = $this->firestore->collection($collection)->add($data);
        }

        return array_merge(['id' => $docRef->id()], $data);
    }

    /**
     * Update an existing document (merges fields by default).
     *
     * @param  string $collection
     * @param  string $documentId
     * @param  array  $data
     * @param  bool   $merge      When true, only provided fields are updated; when false, document is overwritten.
     * @return array
     */
    public function update(string $collection, string $documentId, array $data, bool $merge = true): array
    {
        $docRef = $this->firestore->collection($collection)->document($documentId);

        if ($merge) {
            $docRef->set($data, ['merge' => true]);
        } else {
            $docRef->set($data);
        }

        $snapshot = $docRef->snapshot();

        return array_merge(['id' => $snapshot->id()], $snapshot->data());
    }

    /**
     * Delete a document from a collection.
     *
     * @param  string $collection
     * @param  string $documentId
     * @return bool
     */
    public function delete(string $collection, string $documentId): bool
    {
        $this->firestore->collection($collection)->document($documentId)->delete();

        return true;
    }
}
