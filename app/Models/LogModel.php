<?php 
namespace App\Models;
use CodeIgniter\Model;

class LogModel extends Model{
    protected $table = 'log';
    protected $primaryKey = 'Id';

    protected $allowedFields = ['Message', 'Password', 'File', 'CreatedAt', 'Expire'];
}
?>