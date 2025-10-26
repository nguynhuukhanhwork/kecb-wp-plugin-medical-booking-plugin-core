<?php

namespace MedicalBooking\Infrastructure\Database;

use wpdb;

abstract class BaseTable
{
    protected wpdb $db;
    protected string $table_prefix;
    protected string $charset_collate;
    protected string $table_name;

    public function __construct(string $table_name) {
        global $wpdb;
        $this->db = $wpdb;
        $this->table_prefix = $wpdb->prefix . 'medical_booking_';
        $this->charset_collate = $wpdb->get_charset_collate();
        $this->table_name = $this->table_prefix . $table_name;
    }

    /**
     * Require method Create Table
     * @return void
     */
    abstract public function create_table(): void;

    public function insert(array $data): int
    {
        $this->db->insert($this->table_name, $data);
        return (int) $this->db->insert_id;
    }

    /**
     * Update 1 row data
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id,array $data): int
    {
        return (bool) $this->db->update($this->table_name, $data, ['id' => $id]);
    }

    /**
     * Delete 1 row
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) $this->db->delete($this->table_name, ['id' => $id]);
    }

    /**
     * Get 1 row object data
     * @param int $id
     * @return object|array
     */
    public function get(int $id): ?object
    {
        $sql = $this->db->prepare(
            "SELECT * FROM {$this->table_name} WHERE id = %d",
            $id
        );
        return $this->db->get_row($sql);
    }

}