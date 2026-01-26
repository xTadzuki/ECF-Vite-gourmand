<?php
// app/Services/MongoService.php

class MongoService
{
    private static function isAvailable(): bool
    {
        return class_exists('\MongoDB\Client');
    }

    /**
     * Retourne un client MongoDB (si disponible).
     */
    private static function client(): ?\MongoDB\Client
    {
        if (!self::isAvailable()) return null;

        try {
            return new \MongoDB\Client("mongodb://localhost:27017");
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Retourne une collection MongoDB (si dispo), sinon null.
     */
    private static function collection(string $collectionName = 'menu_stats'): ?\MongoDB\Collection
    {
        $client = self::client();
        if (!$client) return null;

        // DB: vite_gourmand / Collection: menu_stats
        return $client->vite_gourmand->$collectionName;
    }

    /**
     * Retourne les stats depuis MongoDB si dispo, sinon tableau vide.
     * Compatible avec MongoService::all() sans argument.
     */
    public static function all(?string $collection = 'menu_stats'): array
    {
        $collectionName = $collection ?: 'menu_stats';
        $col = self::collection($collectionName);

        if (!$col) return [];

        try {
            $cursor = $col->find([], ['sort' => ['_id' => -1], 'limit' => 200]);
            return $cursor->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Incrémente les statistiques d'un menu à chaque commande.
     * - upsert = crée le doc s'il n'existe pas
     * - orders_count +1
     * - revenue += total
     *
     * Si Mongo n'est pas disponible, la méthode ne fait rien (pas d'erreur).
     */
    public static function incrementMenu(int $menuId, string $title, float $total, string $collection = 'menu_stats'): void
    {
        $col = self::collection($collection);
        if (!$col) return;

        try {
            $col->updateOne(
                ['menu_id' => $menuId],
                [
                    '$setOnInsert' => [
                        'menu_id'     => $menuId,
                        'title'       => $title,
                        'created_at'  => new \MongoDB\BSON\UTCDateTime(),
                    ],
                    '$inc' => [
                        'orders_count' => 1,
                        'revenue'      => (float)$total,
                    ],
                    '$set' => [
                        'updated_at' => new \MongoDB\BSON\UTCDateTime(),
                    ],
                ],
                ['upsert' => true]
            );
        } catch (\Throwable $e) {
            // Mongo installé mais service non lancé / erreur réseau : on ignore pour ne pas bloquer l'app
            return;
        }
    }
}

