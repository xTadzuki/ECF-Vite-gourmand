<?php
// app/Core/OrderStatus.php

final class OrderStatus
{
    public const CREATED = 'créée';
    public const ACCEPTED = 'accepté';
    public const PREPARING = 'en préparation';
    public const MATERIAL_RETURN = 'en attente du retour de matériel';
    public const DELIVERED = 'livré';
    public const CANCELLED = 'annulé';

    /**
     * Liste complète des statuts
     */
    public static function all(): array
    {
        return [
            self::CREATED,
            self::ACCEPTED,
            self::PREPARING,
            self::MATERIAL_RETURN,
            self::DELIVERED,
            self::CANCELLED,
        ];
    }
}
