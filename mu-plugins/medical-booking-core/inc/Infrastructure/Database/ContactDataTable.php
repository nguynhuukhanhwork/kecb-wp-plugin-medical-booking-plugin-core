<?php

namespace TravelBooking\Infrastructure\Database;

class ContactDataTable extends BaseTable
{
    private static ?self $instance = null;
    protected static function TABLE_NAME(): string
    {
        return 'contact_data';
    }

    protected static function ID_COLUMN_NAME(): string {
        return 'id';
    }

    protected function validFormatData(): array{
        return [
            'id',
            'form_type',
            'form_data',
            'created_at'
        ];
    }
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    protected function getSchema(): string
    {
        $table = $this->getTableName();
        $id_name = self::ID_COLUMN_NAME();
        $charset_collate = $this->getCharsetCollate();

       return "
       CREATE TABLE IF NOT EXISTS $table (
           $id_name BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
           form_type VARCHAR(20) NOT NULL default 'contact',
           form_data TEXT NOT NULL,
           created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
       ) $charset_collate;";
    }

    public function updateRow(int $id, array $data)
    {
        // TODO: Implement updateRow() method.
    }


}