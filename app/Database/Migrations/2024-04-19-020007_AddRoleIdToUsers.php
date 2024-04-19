<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;

class AddRoleIdToUsers extends Migration
{
    /**
     * @var string[]
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }
    public function up()
    {
        $fields = [
            'role' => ['type' => 'ENUM', 'constraint' => array('employee', 'admin', 'superadmin'), 'default' => 'employee', 'null' => false],
        ];
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'role',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
